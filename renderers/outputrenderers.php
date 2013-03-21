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

        if (during_initial_install()) {
            return '';
        }

        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }

        $loginpage = ((string)$this->page->url === get_login_url());
        $course = $this->page->course;
        
        $loginurl = get_login_url();
        $logouturl = $CFG->wwwroot . '/login/logout.php?sesskey=' . sesskey();
        $profileurl = $CFG->wwwroot . '/user/profile.php?id=' . $USER->id;
        
        if (session_is_loggedinas()) {
            $realuser = session_get_realuser();
            $fullname = fullname($realuser, true);
            if ($withlinks) {
                $realuserprofileurl = $CFG->wwwroot . '/course/loginas.php?id=' . $course->id . '&amp;sesskey=' . sesskey();
                $realuserinfo = '<a href="' . $realuserprofileurl . '">' . $fullname . '</a>';
                $realuserinfonolink = $fullname;
            } else {
                $realuserinfo = $fullname;
            }
        } else {
            $realuserinfo = '';
        }

        if (empty($course->id)) {
            // Installation in progress
            return '';
        } else if (isloggedin()) {
            // User is logged in
            $context = context_course::instance($course->id);

            $fullname = fullname($USER, true);
            // Since Moodle 2.0 this link always goes to the public profile page (not the course profile page)
            if ($withlinks) {
                $username = '<a href="' . $profileurl . '">' . $fullname . '</a>';
            } else {
                $username = $fullname;
            }

            if (is_mnet_remote_user($USER) and $idprovider = $DB->get_record('mnet_host', array('id'=>$USER->mnethostid))) {
                // Logged in via MNET
                if ($withlinks) {
                    $providerurl = $idprovider->wwwroot;
                    $providername = $idprovider->name;
                    $username .= '<a href="' . $providerurl . '">' . $providername . '</a>';
                } else {
                    $username .= $providername;
                }
            } else {
                $mnetuser = '';
            }

            if (isguestuser()) {
                // Logged in as Guest User
                $loggedinas = '';
                if (!$loginpage) {
                    // Don't show the User name on the login page - it just takes you to the login page...
                    $loggedinas .= html_writer::empty_tag('li', array('class'=>'divider'));
                    $loggedinas .= html_writer::start_tag('li');
                    // Don't provide a link to the "Guest" profile page ($fullname instead of $username)
                    $loggedinas .= html_writer::tag('a', $fullname, array('href'=>'#'));
                    $loggedinas .= html_writer::end_tag('li');
                }
                if (!$loginpage && $withlinks) {
                    // Add a "login" button (switch to authenticated user)
                    $loggedinas .= html_writer::empty_tag('li', array('class'=>'divider'));
                    $loggedinas .= html_writer::start_tag('li', array('class'=>'has-form'));
                    $loggedinas .= html_writer::tag('a', get_string('login'), array('href'=>$loginurl, 'class'=>'button'));
                    $loggedinas .= html_writer::end_tag('li');
                    // Add a "logout" button (end "Guest" session)
                    $loggedinas .= html_writer::empty_tag('li', array('class'=>'divider'));
                    $loggedinas .= html_writer::start_tag('li', array('class'=>'has-form'));
                    $loggedinas .= html_writer::tag('a', get_string('logout'), array('href'=>$logouturl, 'class'=>'button'));
                    $loggedinas .= html_writer::end_tag('li');
                }
            } else if (is_role_switched($course->id)) { // Has switched roles
                $rolename = '';
                if ($role = $DB->get_record('role', array('id'=>$USER->access['rsw'][$context->path]))) {
                    $rolename = ': '.format_string($role->name);
                }
                $loggedinas = $username.$rolename;
                if ($withlinks) {
                    $url = new moodle_url('/course/switchrole.php', array('id'=>$course->id,'sesskey'=>sesskey(), 'switchrole'=>0, 'returnurl'=>$this->page->url->out_as_local_url(false)));
                    $loggedinas .= '('.html_writer::tag('a', get_string('switchrolereturn'), array('href'=>$url)).')';
                        // Add a "logout" button
                        $loggedinas .= html_writer::empty_tag('li', array('class'=>'divider'));
                        $loggedinas .= html_writer::start_tag('li', array('class'=>'has-form'));
                        $loggedinas .= html_writer::tag('a', get_string('logout'), array('href'=>$logouturl, 'class'=>'button'));
                        $loggedinas .= html_writer::end_tag('li');
                }
            } else {
                if (empty($realuserinfo) && empty($mnetuser)) {
                    // Normal User
                    $loggedinas = html_writer::empty_tag('li', array('class'=>'divider'));
                    $loggedinas .= html_writer::start_tag('li');
                    $loggedinas .= $username;
                    $loggedinas .= html_writer::end_tag('li');
                    if ($withlinks) {
                        // Add a "logout" button
                        $loggedinas .= html_writer::empty_tag('li', array('class'=>'divider'));
                        $loggedinas .= html_writer::start_tag('li', array('class'=>'has-form'));
                        $loggedinas .= html_writer::tag('a', get_string('logout'), array('href'=>$logouturl, 'class'=>'button'));
                        $loggedinas .= html_writer::end_tag('li');
                    }
                } else {
                    // Loggedin as somebody else (or mnet?)
                    // Show their user name
                    $loggedinas = html_writer::empty_tag('li', array('class'=>'divider'));
                    $loggedinas .= html_writer::start_tag('li', array('class'=>'has-dropdown'));
                    $loggedinas .= $username;
                    // Show the Real user name
                    if ($withlinks) {
                        $loggedinas .= html_writer::start_tag('ul', array('class'=>'dropdown'));
                        $loggedinas .= html_writer::start_tag('li');
                        $loggedinas .= html_writer::tag('label', get_string('returntooriginaluser', '', $realuserinfonolink));
                        $loggedinas .= html_writer::tag('li', $realuserinfo);
                        $loggedinas .= html_writer::end_tag('li');
                        $loggedinas .= html_writer::end_tag('ul');
                    }
                    $loggedinas .= html_writer::end_tag('li');
                    if ($withlinks) {
                        // Add a "logout" button
                        $loggedinas .= html_writer::empty_tag('li', array('class'=>'divider'));
                        $loggedinas .= html_writer::start_tag('li', array('class'=>'has-form'));
                        $loggedinas .= html_writer::tag('a', get_string('logout'), array('href'=>$logouturl, 'class'=>'button'));
                        $loggedinas .= html_writer::end_tag('li');
                    }
                }
            }
        } else {
            // User is not logged in don't display any text
            $loggedinas = '';
            if (!$loginpage && $withlinks) {
                // Add a "login" button
                $loggedinas = html_writer::empty_tag('li', array('class'=>'divider'));
                $loggedinas .= html_writer::start_tag('li', array('class'=>'has-form'));
                $loggedinas .= html_writer::tag('a', get_string('login'), array('href'=>$loginurl, 'class'=>'button'));
                $loggedinas .= html_writer::end_tag('li');
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
        global $CFG, $OUTPUT, $USER, $PAGE;
        
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
        $content .= html_writer::start_tag('ul', array('class'=>'left'));
        
        foreach ($menu->get_children() as $item) {
            // Add dividers to top level items
            $content .= html_writer::empty_tag('li', array('class'=>'divider'));
            // Render each child
            $content .= $this->render_custom_menu_item($item);
        }
        $content .= html_writer::end_tag('ul');
        
        // Start the right hand items
        $content .= html_writer::start_tag('ul', array('class'=>'right'));
        
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