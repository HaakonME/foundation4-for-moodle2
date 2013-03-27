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
 * foundation theme settings page
 *
 * @package    theme_foundation
 * @copyright  2011 Danny Wahl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    //This is the note box for all the settings pages
    $name = 'theme_foundation/notes';
    $heading = get_string('notes', 'theme_foundation');
    $information = get_string('notesdesc', 'theme_foundation');
    $setting = new admin_setting_heading($name, $heading, $information);
    $settings->add($setting);
    
    //Beg for money
    $name = 'theme_foundation/donate';
    $heading = get_string('donate', 'theme_foundation');
    $information = get_string('donatedesc', 'theme_foundation');
    $setting = new admin_setting_heading($name, $heading, $information);
    $settings->add($setting);
}