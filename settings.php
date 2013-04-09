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
 * Theme version info
 *
 * @package    theme
 * @subpackage foundation
 * @copyright  2013 Danny Wahl  {@link http://iyware.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    // This is the note box for all the settings pages
    $name = 'theme_foundation/notes';
    $heading = get_string('notes', 'theme_foundation');
    $information = get_string('notesdesc', 'theme_foundation');
    $setting = new admin_setting_heading($name, $heading, $information);
    $settings->add($setting);
    
    // Beg for money
    $name = 'theme_foundation/donate';
    $heading = get_string('donate', 'theme_foundation');
    $information = get_string('donatedesc', 'theme_foundation');
    $setting = new admin_setting_heading($name, $heading, $information);
    $settings->add($setting);
    
    // Customize Foundation settings (compiled via SCSS)
    $name = 'theme_foundation/customize';
    $heading = get_string('customize', 'theme_foundation');
    $information = get_string('customizedesc', 'theme_foundation');
    $setting = new admin_setting_heading($name, $heading, $information);
    $settings->add($setting);
    
    // Set the BODY max-width
    $name = 'theme_foundation/maxwidth';
    $title = get_string('maxwidth', 'theme_foundation');
    $description = get_string('maxwidthdesc', 'theme_foundation');
    $default = get_string('maxwidthdefault', 'theme_foundation');
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_CLEAN, 5);
    $settings->add($setting);
    
    // Set the Global Radius
    $name = 'theme_foundation/radius';
    $title = get_string('radius', 'theme_foundation');
    $description = get_string('radiusdesc', 'theme_foundation');
    $default = get_string('radiusdefault', 'theme_foundation');
    $choices = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10);
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $settings->add($setting);
    
    // Set top-bar breakpoint
    $name = 'theme_foundation/breakpoint';
    $title = get_string('breakpoint', 'theme_foundation');
    $description = get_string('breakpointdesc', 'theme_foundation');
    $default = get_string('breakpointdefault', 'theme_foundation');
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_CLEAN, 5);
    $settings->add($setting);

    //Set primary color
    $name = 'theme_foundation/primarycolor';
    $title = get_string('primarycolor','theme_foundation');
    $description = get_string('primarycolordesc', 'theme_foundation');
	$default = get_string('primarycolordefault', 'theme_foundation');
	$previewconfig = array('selector'=>'a', 'style'=>'color');
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $settings->add($setting);

    //Set secondary color
    $name = 'theme_foundation/secondarycolor';
    $title = get_string('secondarycolor','theme_foundation');
    $description = get_string('secondarycolordesc', 'theme_foundation');
	$default = get_string('secondarycolordefault', 'theme_foundation');
	$setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $settings->add($setting);
}