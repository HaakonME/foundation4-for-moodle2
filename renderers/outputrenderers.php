<?php

class theme_foundation_core_renderer extends core_renderer {

    /**
     * Return the standard string that says whether you are logged in (and switched
     * roles/logged in as another user).
     * @param bool $withlinks if false, then don't include any links in the HTML produced.
     * If not set, the default is the nologinlinks option from the theme config.php file,
     * and if that is not set, then links are included.
     * @return string HTML fragment.
     */
    public function login_info($withlinks = null) {
        global $USER, $CFG, $DB, $SESSION;
        
        if (right_to_left()) {
            $direction = array('left-side' => 'right', 'right-side' => 'left');
            $dir = 'right';
            
        } else {
            $direction = array('left-side' => 'left', 'right-side' => 'right');
            $dir = '';
        }

        // Check Page layout options for links
        // Obscure, but whatever...
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }

        // Setup a check for if we're on the login page
        $loginurl = get_login_url();
        $loginpage = ((string)$this->page->url === $loginurl);
        $logouturl = $CFG->wwwroot . '/login/logout.php'; // This should be overridden with sesskey() info
        $logouturl = $CFG->wwwroot . '/login/logout.php?sesskey=' . sesskey();

        $course = $this->page->course;
        
        if (during_initial_install() || empty($course->id)) {
            // $course->id is not defined during installation
            // Logins don't exist yet...
            return '';
        }

        // Assume they're not logged in
        $loggedinas = '';

        // Build some general output components

        // Divider
        $divider = html_writer::empty_tag('li', array('class'=>'divider ' . $dir));
        
        // Start li
        $startli = html_writer::start_tag('li', array('class'=>$dir));
        $startdropdownli = html_writer::start_tag('li', array('class'=>'has-dropdown ' . $dir));
        
        // End li
        $endli = html_writer::end_tag('li');
        // Login button
        $loginbutton = $divider;
        $loginbutton .= html_writer::start_tag('li', array('class'=>'has-form'));
        $loginbutton .= html_writer::tag('a', get_string('login'), array('href'=>$loginurl, 'class'=>'button'));
        $loginbutton .= html_writer::end_tag('li');
        
        // Logout button
        $logoutbutton = html_writer::empty_tag('li', array('class'=>'divider'));
        $logoutbutton .= html_writer::start_tag('li', array('class'=>'has-form'));
        $logoutbutton .= html_writer::tag('a', get_string('logout'), array('href'=>$logouturl, 'class'=>'button'));
        $logoutbutton .= html_writer::end_tag('li');

        if(!$loginpage) {
        // Don't show any login info on the login page
            if (isloggedin()) {
            // Logged in users (MNET, guest, switched role, loggedinas, normal)
                $fullname = fullname($USER, true);
    
                $mnetuser = (is_mnet_remote_user($USER) && $idprovider = $DB->get_record('mnet_host', array('id'=>$USER->mnethostid)));
                $mnetuserpanel = '';
    
                $roleswitched = (is_role_switched($course->id));
                $roleswitchedpanel = '';
                
                $loggedinasuser = (session_is_loggedinas());
                $loggedinasuserpanel = '';

                if($mnetuser) {
                    $providerurl = $idprovider->wwwroot;
                    $providername = $idprovider->name;
                    $providerlink = html_writer::tag('a', $providername, array('href'=>$providerurl));
                    ($roleswitched || $loggedinasuser) ? $mnetuserpanel .= $divider : null;
                    if ($withlinks) {
                        $mnetuserpanel .= $startli;
                        $mnetuserpanel .= html_writer::tag('label', get_string('yourhost', 'mnet') . ':');
                        $mnetuserpanel .= html_writer::tag('li', $providerlink);
                        $mnetuserpanel .= $endli;
                    } else {
                        $mnetuserpanel .= $startli;
                        $mnetuserpanel .= html_writer::tag('label', $providername);
                        $mnetuserpanel .= $endli;
                    }
                }

                if ($roleswitched) {
                    $rolename = '';
                    $context = context_course::instance($course->id);
                    if ($role = $DB->get_record('role', array('id'=>$USER->access['rsw'][$context->path]))) {
                        $rolename = format_string($role->name);
                    }
                    if (empty($rolename)) {
                    // Specially for Admins - they have no original role Title...
                        $rolename = get_string('admin');
                    }
                    $returnrolelinkparams = array(
                                        'id'=>$course->id,
                                        'sesskey'=>sesskey(),
                                        'switchrole'=>0,
                                        'returnurl'=>$this->page->url->out_as_local_url(false)
                                      );
                    $returnrolelink = new moodle_url('/course/switchrole.php', $returnrolelinkparams);
                    $returnrolelink = html_writer::tag('a', $rolename, array('href'=>$returnrolelink));
                    // Add a divider if the user is also role switched or MNET
                    ($mnetuser || $loggedinasuser) ? $roleswitchedpanel .= $divider : null;
                    if ($withlinks) {
                        $roleswitchedpanel .= $startli;
                        $roleswitchedpanel .= html_writer::tag('label', get_string('switchrolereturn'));
                        $roleswitchedpanel .= $endli;
                        $roleswitchedpanel .= html_writer::tag('li', $returnrolelink);
                    } else {
                        $roleswitchedpanel .= $startli;
                        $roleswitchedpanel .= html_writer::tag('label', get_string('role') . ': ' . $rolename);
                        $roleswitchedpanel .= $endli;
                    }
                }

                if ($loggedinasuser) {
                    $realuser = session_get_realuser();
                    $realuser = fullname($realuser, true);
                    $realuserprofilelink = $CFG->wwwroot . '/course/loginas.php?id=' . $course->id . '&sesskey=' . sesskey();
                    $realuserprofile = html_writer::tag('a', $realuser, array('href'=>$realuserprofilelink));
                    // Add a divider if the user is also role switched or MNET
                    ($mnetuser || $roleswitched) ? $loggedinasuserpanel .= $divider : null;
                    if ($withlinks) {
                        $loggedinasuserpanel .= $startli;
                        $loggedinasuserpanel .= html_writer::tag('label', get_string('returntooriginaluser', '', $realuser));
                        $loggedinasuserpanel .= $endli;
                        $loggedinasuserpanel .= html_writer::tag('li', $realuserprofile);
                    } else {
                        $loggedinasuserpanel .= $startli;
                        $loggedinasuserpanel .= html_writer::tag('label', get_string('loggedinas', '', $realuser));
                        $loggedinasuserpanel .= $endli;
                    }
                }

                $hasdropdown = ($mnetuser || $roleswitched || $loggedinasuser);
                $dropdown = $mnetuserpanel . $roleswitchedpanel . $loggedinasuserpanel;
                $dropdown = html_writer::tag('ul', $dropdown, array('class'=>'dropdown'));
    
                if (isguestuser()) {
                // Guest user
                    $fullname = html_writer::tag('span', $fullname);
                    $loggedinas = $divider . $startli . $fullname . $endli; //@TODO: Write a style to swap tag for span
                    if ($withlinks) {
                        $loggedinas .= $loginbutton;
                    }
                } else {
                // Normal User
                    if ($withlinks) {
                        //Link to profile page
                        $userprofilelink = $CFG->wwwroot . '/user/profile.php?id=' . $USER->id; 
                        $userprofile = html_writer::tag('a', $fullname, array('href'=>$userprofilelink));
                        // Check to see if we need a dropdown
                        if ($hasdropdown) {
                            $loggedinas = $divider . $startdropdownli . $userprofile . $dropdown . $endli . $logoutbutton;
                        } else {
                            // Normal User
                            $loggedinas = $divider . $startli . $userprofile . $endli . $logoutbutton;
                        }
                    } else {
                        $fullname = html_writer::tag('a', $fullname, array('href'=>'#')); //@ TODO Write SPAN rules to show dropdown menu
                        if ($hasdropdown) {
                            $loggedinas = $divider . $startdropdownli . $fullname . $dropdown . $endli;
                        } else {
                            $loggedinas = $divider . $startli . $fullname . $endli;
                        }
                    }
                }
            } else {
            // All not logged in users
                if ($withlinks) {
                    // Add a "login" button
                    $loggedinas = $loginbutton;           
                } else {
                    // Don't need to output anything
                    $loggedinas = '';
                }
            }
        }

        if (isset($SESSION->justloggedin)) {
            unset($SESSION->justloggedin);
            if (!empty($CFG->displayloginfailures)) {
                if (!isguestuser()) {
                    if ($count = count_login_failures($CFG->displayloginfailures, $USER->username, $USER->lastlogin)) {
                        $loggedinas .= '&nbsp;<div class="loginfailures">';
                        if (empty($count->accounts)) {
                            $loggedinas .= get_string('failedloginattempts', '', $count);
                        } else {
                            $loggedinas .= get_string('failedloginattemptsall', '', $count);
                        }
                        if (file_exists("$CFG->dirroot/report/log/index.php") and has_capability('report/log:view', context_system::instance())) {
                            $loggedinas .= ' (<a href="'.$CFG->wwwroot.'/report/log/index.php'.
                                                 '?chooselog=1&amp;id=1&amp;modid=site_errors">'.get_string('logs').'</a>)';
                        }
                        $loggedinas .= '</div>';
                    }
                }
            }
        }

        return $loggedinas;
    }


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
            if ($item->isactive == true) {
                // Current Page
                $content = html_writer::tag('li', $this->render($item), array('class'=>'current'));
            } elseif ($item->action == NULL) {
                // Page without links
                $content = html_writer::tag('li', $this->render($item), array('class'=>'unavailable'));

            } else {
                // Normal Breadcrumb items
                $content = html_writer::tag('li', $this->render($item));

            }
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
        global $CFG, $OUTPUT, $USER, $PAGE;
        
        $site = get_site();
        $sitename = $site->fullname;
        $siteurl = $CFG->wwwroot;
        if (right_to_left()) {
            $direction = array('left-side' => 'right', 'right-side' => 'left');
            $dir = 'right';
            
        } else {
            $direction = array('left-side' => 'left', 'right-side' => 'right');
            $dir = '';
        }

        // If the menu has no children return an empty string
        if (!$menu->has_children()) {
            return '';
        }

        // Start the title area that contains the Site Name Menu icon
        $content = html_writer::start_tag('ul', array('class'=>'title-area ' . $dir));
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
        $content .= html_writer::start_tag('ul', array('class'=> $direction['left-side']));
        
        foreach ($menu->get_children() as $item) {
            // Add dividers to top level items
            $content .= html_writer::empty_tag('li', array('class'=>'divider ' . $dir));
            // Render each child
            $content .= $this->render_custom_menu_item($item);
        }
        $content .= html_writer::end_tag('ul');
        
        // Start the right hand items
        $content .= html_writer::start_tag('ul', array('class'=> $direction['right-side']));
        
        // Render login_info() (if the theme allows it...
        if(empty($PAGE->layout_options['nologininfo'])) {
            $content .= $this->login_info();
        }
        $content .= html_writer::end_tag('ul');
        
        $content .= html_writer::end_tag('section');
        
        // Return the custom menu
        return $content;
    }

    /**
     * Renders a custom menu node as part of a submenu
     *
     * The custom menu this method produces makes use of the foundation
     * top-bar element and requires specific markup and classes
     *
     * @see core:renderer::render_custom_menu()
     *
     * @staticvar int $submenucount
     * @param custom_menu_item $menunode
     * @return string
     */
    protected function render_custom_menu_item(custom_menu_item $menunode) {

        if (right_to_left()) {
            $direction = array('left-side' => 'right', 'right-side' => 'left');
            $dir = 'right';
            
        } else {
            $direction = array('left-side' => 'left', 'right-side' => 'right');
            $dir = '';
        }

        if ($menunode->has_children()) {
            // If the child has menus render it as a sub menu
            $content = html_writer::start_tag('li', array('class'=>'has-dropdown ' . $dir));
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
    
    /**
     * Output the row of editing icons for a block, as defined by the controls array.
     *
     * @param array $controls an array like {@link block_contents::$controls}.
     * @return string HTML fragment.
     */
    public function block_controls($controls) {
        if (empty($controls)) {
            return '';
        }
        $controlshtml = array();
        foreach ($controls as $control) {
            $controlshtml[] = html_writer::tag('a',
                    html_writer::empty_tag('img',  array('src' => $this->pix_url($control['icon'])->out(false), 'alt' => $control['caption'])),
                    array('class' => 'icon ' . $control['class'],'title' => $control['caption'], 'href' => $control['url']));
        }
        return html_writer::tag('div', implode('', $controlshtml), array('class' => 'commands'));
    }

    /**
     * Prints a nice side block with an optional header.
     *
     * The content is described
     * by a {@link core_renderer::block_contents} object.
     *
     * <section id="inst{$instanceid}" class="block_{$blockname} block section">
     *      <header class="header title"></header>
     *      <div class="content">
     *          ...CONTENT...
     *          <footer class="footer">
     *          </footer>
     *      </div>
     *      <div class="annotation">
     *      </div>
     * </section>
     *
     * @param block_contents $bc HTML for the content
     * @param string $region the region the block is appearing in.
     * @return string the HTML to be output.
     */
    public function block(block_contents $bc, $region) {
        $bc = clone($bc); // Avoid messing up the object passed in.
        if (empty($bc->blockinstanceid) || !strip_tags($bc->title)) {
            $bc->collapsible = block_contents::NOT_HIDEABLE;
        }
        $skiptitle = strip_tags($bc->title);
        if ($bc->blockinstanceid && !empty($skiptitle)) {
            $bc->attributes['aria-labelledby'] = 'instance-'.$bc->blockinstanceid.'-header';
        } else if (!empty($bc->arialabel)) {
            $bc->attributes['aria-label'] = $bc->arialabel;
        }
        if ($bc->collapsible == block_contents::HIDDEN) {
            $bc->add_class('hidden');
        }
        if (!empty($bc->controls)) {
            $bc->add_class('block_with_controls');
        }


        if (empty($skiptitle)) {
            $output = '';
            $skipdest = '';
        } else {
            $output = html_writer::tag('a', get_string('skipa', 'access', $skiptitle), array('href' => '#sb-' . $bc->skipid, 'class' => 'skip-block'));
            $skipdest = html_writer::tag('span', '', array('id' => 'sb-' . $bc->skipid, 'class' => 'skip-block-to'));
        }

        $output .= html_writer::start_tag('section', $bc->attributes);

        $output .= $this->block_header($bc);
        $output .= $this->block_content($bc);

        $output .= html_writer::end_tag('section');

        $output .= $this->block_annotation($bc);

        $output .= $skipdest;

        $this->init_block_hider_js($bc);
        return $output;
    }

    /**
     * Produces a header for a block
     *
     * @param block_contents $bc
     * @return string
     */
    protected function block_header(block_contents $bc) {        
        // Check for a title (if it exists)
        $blockid = '';
        if ($bc->blockinstanceid) {
            $blockid = 'instance-'.$bc->blockinstanceid.'-header';
        }

        // There will always be a title
        $title = '';
        if ($bc->title) {
            $title = html_writer::tag('span', $bc->title, array('class'=>'block_action'));
        } elseif ($bc->arialabel) {
            $title = html_writer::tag('span', $bc->arialabel, array('class'=>'block_action'));
        } else {
            $title = html_writer::tag('span', '', array('class'=>'block_action'));
        }

        $output = '';
        $output = html_writer::tag('header', $title, array('id'=>$blockid, 'class' => 'header title'));
        return $output;
    }

    /**
     * Produces the content area for a block
     *
     * @param block_contents $bc
     * @return string
     */
    protected function block_content(block_contents $bc) {
        $output = html_writer::start_tag('div', array('class' => 'content'));
        // Block controls
        $output .= $this->block_controls($bc->controls);
        $output .= $bc->content;
        $output .= $this->block_footer($bc);
        $output .= html_writer::end_tag('div');

        return $output;
    }

    /**
     * Produces the footer for a block
     *
     * @param block_contents $bc
     * @return string
     */
    protected function block_footer(block_contents $bc) {
        $output = '';
        if ($bc->footer) {
            $output .= html_writer::tag('footer', $bc->footer, array('class' => 'footer'));
        }
        return $output;
    }

}