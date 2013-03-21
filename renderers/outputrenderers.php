<?php

class theme_foundation_core_renderer extends core_renderer {

    /**
     * Return the navbar content so that it can be echoed out by the layout
     *
     * @return string XHTML navbar
     */
    public function navbar() {
        $items = $this->page->navbar->get_items();

        $htmlblocks = array();
        // Iterate the navarray and display each node
        $itemcount = count($items);
        for ($i=0;$i < $itemcount;$i++) {
            $item = $items[$i];
            $item->hideicon = true;
            $content = html_writer::tag('li', $this->render($item));
            $htmlblocks[] = $content;
        }

        //accessibility: heading for navbar list  (MDL-20446)
        $navbarcontent = html_writer::tag('span', get_string('pagepath'), array('class'=>'accesshide'));
        $navbarcontent .= html_writer::tag('ul', join('', $htmlblocks), array('role'=>'navigation'));
        // XHTML
        return $navbarcontent;
    }   

    /**
     * Returns the custom menu if one has been set
     *
     * A custom menu can be configured by browsing to
     *    Settings: Administration > Appearance > Themes > Theme settings
     * and then configuring the custommenu config setting as described.
     *
     * @param string $custommenuitems - custom menuitems set by theme instead of global theme settings
     * @return string
     */
    public function custom_menu($custommenuitems = '') {
        global $CFG;
        
        if (empty($custommenuitems) && !empty($CFG->custommenuitems)) {
            $custommenuitems = $CFG->custommenuitems;
        }
        if (empty($custommenuitems)) {
            return '';
        }
        $custommenu = new custom_menu($custommenuitems, current_language());
        return $this->render_custom_menu($custommenu);
    }

    /**
     * Renders a custom menu object (located in outputcomponents.php)
     *
     * The custom menu this method produces makes use of the foundation
     * top-bar element and requires specific markup and classes
     *
     * @staticvar int $menucount
     * @param custom_menu $menu
     * @return string
     */
    protected function render_custom_menu(custom_menu $menu) {
        global $CFG, $OUTPUT, $USER;
        
        $site = get_site();
        $sitename = $site->fullname;
        $siteurl = $CFG->wwwroot;

        // If the menu has no children return an empty string
        if (!$menu->has_children()) {
            return '';
        }

        // Start the title area that contains the Site Name Menu icon
        $content = html_writer::start_tag('ul', array('class'=>'title-area'));
        $content .= html_writer::start_tag('li', array('class'=>'name'));
        $content .= html_writer::start_tag('h1');
        $content .= html_writer::tag('a', $sitename, array('href'=>$siteurl));
        $content .= html_writer::end_tag('h1');
        $content .= html_writer::end_tag('li');
        $content .= html_writer::start_tag('li', array('class'=>'toggle-topbar menu-icon'));
        $content .= html_writer::start_tag('a', array('href'=>'#'));
        $content .= html_writer::empty_tag('span');
        $content .= html_writer::end_tag('a');
        $content .= html_writer:: end_tag('li');
        $content .= html_writer::end_tag('ul');

        // Start the custommenu items
        $content .= html_writer::start_tag('section', array('class'=>'top-bar-section'));
        $content .= html_writer::start_tag('ul');
        
        foreach ($menu->get_children() as $item) {
            // Add dividers to top level items
            $content .= html_writer::empty_tag('li', array('class'=>'divider'));
            // Render each child
            $content .= $this->render_custom_menu_item($item);
        }
        
        // Close the open tags
        $content .= html_writer::end_tag('ul');
        $content .= html_writer::end_tag('section');
        
        // Return the custom menu
        return $content;
    }

    /**
     * Renders a custom menu node as part of a submenu
     *
     * The custom menu this method produces makes use of the YUI3 menunav widget
     * and requires very specific html elements and classes.
     *
     * @see core:renderer::render_custom_menu()
     *
     * @staticvar int $submenucount
     * @param custom_menu_item $menunode
     * @return string
     */
    protected function render_custom_menu_item(custom_menu_item $menunode) {

        if ($menunode->has_children()) {
            // If the child has menus render it as a sub menu
            $content = html_writer::start_tag('li', array('class'=>'has-dropdown'));
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#';
            }
            $content .= html_writer::link($url, $menunode->get_text(), array('title'=>$menunode->get_title()));
            $content .= html_writer::start_tag('ul', array('class'=>'dropdown'));
            foreach ($menunode->get_children() as $menunode) {
                // If the child has menus render it as a sub menu (it's a loop!)
                $content .= $this->render_custom_menu_item($menunode);
            }
            $content .= html_writer::end_tag('ul');
            $content .= html_writer::end_tag('li');
        } else {
            // The node doesn't have children so produce a menuitem
            $content = html_writer::start_tag('li');
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#';
            }
            $content .= html_writer::link($url, $menunode->get_text(), array('title'=>$menunode->get_title()));
            $content .= html_writer::end_tag('li');
        }
        // Return the sub menu
        return $content;
    }


}