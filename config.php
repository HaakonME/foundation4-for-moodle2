<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Configuration for Moodle's foundation theme.
 *
 * This theme is special, and implements a minimalist theme with only
 * basic layout. It is intended as a foundation for other themes to build upon.
 * It is not recommend to actually choose this theme for production sites!
 *
 * DO NOT COPY THIS TO START NEW THEMES!
 * Start with another theme, like "standard".
 *
 * For full information about creating Moodle themes, see:
 *  http://docs.moodle.org/dev/Themes_2.0
 *
 * @package   moodlecore
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$THEME->name = 'foundation';

$THEME->parents = array('base');

$THEME->parents_exclude_sheets  = array(
    'base'=>array(
	    'dock'
    )
);

$THEME->plugins_exclude_sheets = array(
    'block' => array(
        ''
    )
);

$THEME->sheets = array(
    'foundation/foundation',
    //'offcanvas/offcanvas',
    //'responsive-tables/responsive-tables'
    'debug'
);

$THEME->editor_sheets = array('tinymce/editor');

$THEME->layouts = array(
    // Most backwards compatible layout without the blocks - this is the layout used by default
    'base' => array(
        'file' => 'general.php',
        'regions' => array(),
    ),

    // Standard layout with blocks, this is recommended for most pages with general information
    'standard' => array(
        'file' => 'general.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre'
    ),

    // Main course page
    'course' => array(
        'file' => 'general.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nofooter'=>true)
    ),

    'coursecategory' => array(
        'file' => 'general.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre'
    ),

    // part of course, typical for modules - default page layout if $cm specified in require_login()
    'incourse' => array(
        'file' => 'general.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nofooter'=>true)
    ),

    // The site home page.
    'frontpage' => array(
        'file' => 'general.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar'=>true)
    ),

    // Server administration scripts.
    'admin' => array(
        'file' => 'general.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre'
    ),

    // My dashboard page
    'mydashboard' => array(
        'file' => 'general.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu'=>true)
    ),

    // My public page
    'mypublic' => array(
        'file' => 'general.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre'
    ),

    'login' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('langmenu'=>true, 'nofooter'=>true, 'nonavbar'=>true)
    ),

    // Pages that appear in pop-up windows - no navigation, no blocks, no header.
    'popup' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('noblocks'=>true, 'nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true, 'nologininfo'=>true, 'nocourseheaderfooter'=>true)
    ),
    // No blocks and minimal footer - used for legacy frame layouts only!
    'frametop' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('nofooter'=>true, 'nocoursefooter'=>true)
    ),
    // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible
    'embedded' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true, 'nocourseheaderfooter'=>true)
    ),
    // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
    // This must not have any blocks, and it is good idea if it does not have links to
    // other places - for example there should not be a home link in the footer...
    'maintenance' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('noblocks'=>true, 'nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true, 'nocourseheaderfooter'=>true)
    ),
    // Should display the content and basic headers only.
    'print' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('noblocks'=>true, 'nofooter'=>true, 'nonavbar'=>false, 'nocustommenu'=>true, 'nologinlinks'=>true, 'nocourseheaderfooter'=>true)
    ),
    // The pagelayout used when a redirection is occuring.
    'redirect' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('noblocks'=>true, 'nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true, 'nologinlinks'=>true, 'nocourseheaderfooter'=>true)
    ),
    // The pagelayout used for reports.
    'report' => array(
        'file' => 'general.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre'
    ),
    // The pagelayout used for safebrowser and securewindow.
    'secure' => array(
        'file' => 'general.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true, 'nologinlinks'=>true, 'nocourseheaderfooter'=>true)
    )
);

// We don't want the foundation theme to be shown on the theme selection screen, by setting
// this to true it will only be shown if theme designer mode is switched on.
$THEME->hidefromselector = false;

/** List of javascript files that need to included on each page */
$THEME->javascripts = array(
	'foundation/vendor/custom.modernizr'
);

$THEME->javascripts_footer = array(
	// 'foundation/vendor/zepto', // We are using Moodle's included jQuery- but zepto is still available if you want...
	'foundation/foundation.min',
	'foundation/offcanvas/jquery.offcanvas',
	'foundation/responsive-tables/responsive-tables',
	'call'
);

$THEME->rendererfactory = 'theme_overridden_renderer_factory';

$THEME->doctype = 'html5';