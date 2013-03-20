<?php

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidepre = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-pre', $OUTPUT));
$haslogininfo = (empty($PAGE->layout_options['nologininfo']));

$showsidepre = ($hassidepre && !$PAGE->blocks->region_completely_docked('side-pre', $OUTPUT));

$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

$courseheader = $coursecontentheader = $coursecontentfooter = $coursefooter = '';
if (empty($PAGE->layout_options['nocourseheaderfooter'])) {
    $courseheader = $OUTPUT->course_header();
    $coursecontentheader = $OUTPUT->course_content_header();
    if (empty($PAGE->layout_options['nocoursefooter'])) {
        $coursecontentfooter = $OUTPUT->course_content_footer();
        $coursefooter = $OUTPUT->course_footer();
    }
}

$bodyclasses = array();
if ($showsidepre) {
    $bodyclasses[] = 'side-pre-only';
} else {
    $bodyclasses[] = 'content-only';
}
if ($hascustommenu) {
    $bodyclasses[] = 'has_custom_menu';
}

echo $OUTPUT->doctype(); ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $PAGE->title; ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme'); ?>" />
    <?php echo $OUTPUT->standard_head_html(); ?>
</head>
<body id="<?php p($PAGE->bodyid); ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)); ?>">
	<?php echo $OUTPUT->standard_top_of_body_html(); ?>
	<?php if ($hasheading || $hasnavbar || !empty($courseheader) || $hascustommenu) { ?>
	    <?php if ($hascustommenu) { ?>
        	<nav id="custommenu" class="top-bar"><?php echo $custommenu; ?></nav>
        <?php } ?>
		<header class="row">
	        	<?php if ($hasheading) { ?>
	        		<div class="<?php if(right_to_left()) { echo "push-4"; } ?> large-8 columns">
	        			<h1 class="headermain"><?php echo $PAGE->heading; ?></h1>
	        		</div>
	        	<?php } ?>
	        	<div class="<?php if (!$hasheading) { echo "large-offset-8"; } ?> <?php if(right_to_left()) { echo "pull-8"; } ?> large-4 columns headermenu">
	        		<h6 class="subheader"><?php if ($haslogininfo) { echo $OUTPUT->login_info(); } ?></h6>
	        		<?php if (!empty($PAGE->layout_options['langmenu'])) { echo $OUTPUT->lang_menu(); } ?>
	        		<h6 class="subheader"><?php echo $PAGE->headingmenu; ?></h6>
	        	</div>
		</header>
        <?php if ($hasnavbar) { ?>
            <nav class="row navbar">
                <div class="large-12 columns breadcrumb">
                	<div class="breadcrumbs"><?php echo $OUTPUT->navbar(); echo $PAGE->button; ?></div>
                </div>
            </nav>
        <?php } ?>
		<?php if (!empty($courseheader)) { ?>
			<header class="row">
	           	<div id="course-header" class="<?php if ($showsidepre) { echo "large-9 large-offset-3"; } else { echo "large-12"; } ?> columns"><?php echo $courseheader; ?></div>
	        </header>
	    <?php } ?>
	<?php } ?>
	<div class="row">
		<section id="region-main" class="<?php if ($showsidepre) { echo "large-9"; } else { echo "large-12"; } ?> <?php if (!right_to_left() && $showsidepre) { echo "push-3"; } ?> columns">
			<div class="region-content">
				<?php echo $coursecontentheader; ?>
            	<?php echo $OUTPUT->main_content(); ?>
            	<?php echo $coursecontentfooter; ?>
			</div>
		</section>
		<?php if ($showsidepre) { ?>
			<aside id="region-pre" class="large-3 <?php if (!right_to_left()) { echo "pull-9"; } ?> columns block-region">
				<div class="region-content">
					<?php echo $OUTPUT->blocks_for_region('side-pre'); ?>
				</div>
			</aside>
		<?php } ?>
	</div>
    <?php if(!empty($coursefooter)) { ?>
        <footer class="row">
        	<div id="course-footer" class="<?php if ($showsidepre) { echo "large-9 large-offset-3"; } else { echo "large-12"; } ?> columns">
        		<?php echo $coursefooter; ?>
        	</div>
        </footer>
    <?php } ?>
    <?php if ($hasfooter) { ?>
        <footer class="row">
        	<div id="page-footer" class="large-12 columns">
	        	<p class="helplink"><?php echo page_doc_link(get_string('moodledocslink')) ?></p>
	        	<?php
		        echo $OUTPUT->login_info();
		        echo $OUTPUT->home_link();
		        echo $OUTPUT->standard_footer_html();
		        ?>
		    </div>
		</footer>
	<?php } ?>
	<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>