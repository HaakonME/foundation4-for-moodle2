<?php

// Custom CSS Settings lib
require_once(dirname(__FILE__).'/lib/css_postprocessor.php');

// Load Moodle jQuery
function theme_sometheme_page_init(moodle_page $page) {
    $page->requires->jquery();
}
?>