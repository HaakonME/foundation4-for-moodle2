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
 * Library functions for theme_foundation
 *
 * @package    theme
 * @subpackage foundation
 * @copyright  2013 Danny Wahl  {@link http://iyware.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Custom CSS Settings lib
require_once(dirname(__FILE__).'/lib/css_postprocessor.php');

// Load Moodle jQuery
function theme_foundation_page_init(moodle_page $page) {
    $page->requires->jquery();
}
?>