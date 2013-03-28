<?php

/* Instantiate some render/layout variables */
$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidepre = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-pre', $OUTPUT));
$haslangmenu = (!empty($PAGE->layout_options['langmenu']));

$showsidepre = ($hassidepre && !$PAGE->blocks->region_completely_docked('side-pre', $OUTPUT));

$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

$courseheader = $coursecontentheader = $coursecontentfooter = $coursefooter = '';
if (empty($PAGE->layout_options['nocourseheaderfooter'])) {
    $courseheader = $OUTPUT->course_header();
    $coursecontentheader = $OUTPUT->course_content_header();
}
if (empty($PAGE->layout_options['nocoursefooter'])) {
    $coursecontentfooter = $OUTPUT->course_content_footer();
    $coursefooter = $OUTPUT->course_footer();
}

$bodyclasses = array();
$bodyclasses[] = ($showsidepre) ? 'side-pre-only' : 'content-only';
$bodyclasses[] = ($hascustommenu) ? 'has_custom_menu' : null;
$bodyclasses = $PAGE->bodyclasses . ' ' . join(' ', $bodyclasses);

/* HTML5, yo! */
echo $OUTPUT->doctype();

/* Start the HTML tag */
echo "<html" . $OUTPUT->htmlattributes() . ">"; // Can't use html_writer with this tag...

/* Start the HEAD tag */
echo html_writer::start_tag('head');

/* Output the TITLE tag */
echo html_writer::tag('title', $PAGE->title);

/* Output apple-touch-icons and favicon */
$size = array("144x144", "114x114", "72x72", "57x57");
foreach ($size as $value) {
    $icon = $OUTPUT->pix_url('foundation/favicons/apple-touch-icon-' . $value . '-precomposed', 'theme');
    echo html_writer::empty_tag('link', array('href'=>$icon, 'rel'=>'apple-touch-icon-precomposed', 'sizes'=>$value));
}

$favicon = $OUTPUT->pix_url('favicon', 'theme');
echo html_writer::empty_tag('link', array('href'=>$favicon, 'rel'=>'icon', 'type'=>'image/x-icon'));

/* Output core CSS and JS */
echo $OUTPUT->standard_head_html();

echo html_writer::end_tag('head'); ?>
<body id="<?php p($PAGE->bodyid); ?>" class="<?php p($bodyclasses); ?>">
	<?php echo $OUTPUT->standard_top_of_body_html(); ?>
	<?php if ($hasheading || $hasnavbar || !empty($courseheader) || $hascustommenu) { ?>
	    <?php if ($hascustommenu) { ?>
        	<nav id="custommenu" class="top-bar"><?php echo $custommenu; ?></nav>
        <?php } ?>
		<header>
            <div class="row">
                <?php if ($hasheading) { ?>
	        		<div class="<?php if(right_to_left()) { echo "push-4"; } ?> large-8 columns">
	        			<h1 class="headermain"><?php echo $PAGE->heading; ?></h1>
	        		</div>
	        	<?php } ?>
	        	<div class="<?php if (!$hasheading) { echo "large-offset-8"; } if(right_to_left()) { echo "pull-8"; } ?> large-4 columns headermenu">
	        		<?php if ($haslangmenu) { echo $OUTPUT->lang_menu(); } ?>
	        		<h6 class="subheader"><?php echo $PAGE->headingmenu; ?></h6>
	        	</div>
            </div>
		</header>
        <?php if ($hasnavbar) { ?>
            <div>
                <nav class="row navbar">
                    <div class="large-12 columns breadcrumb">
                    	<div class="breadcrumbs">
                    	   <?php echo $OUTPUT->navbar(); ?>
                    	   <div class="secondary <?php if(right_to_left()) { echo "left"; } else { echo "right"; } ?>">
                    	       <?php echo $PAGE->button; ?>
                    	   </div>
                        </div>
                    	<hr>
                    </div>
                </nav>
            </div>
        <?php } ?>
		<?php if (!empty($courseheader)) { ?>
            <div>
    			<header class="row">
    	           	<div id="course-header" class="<?php if ($showsidepre) { echo "large-9 large-offset-3"; } else { echo "large-12"; } ?> columns"><?php echo $courseheader; ?></div>
    	        </header>
            </div>
	    <?php } ?>
	<?php } ?>
	<div>
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
				<div class="region-content section-container accordion">
					<?php echo $OUTPUT->blocks_for_region('side-pre'); ?>
				</div>
			</aside>
		<?php } ?>
	</div>
	</div>
    <?php if(!empty($coursefooter)) { ?>
        <footer>
            <div class="row">
            	<div id="course-footer" class="<?php if ($showsidepre) { echo "large-9 large-offset-3"; } else { echo "large-12"; } ?> columns">
            		<?php echo $coursefooter; ?>
            	</div>
            </div>
        </footer>
    <?php } ?>
    <?php if ($hasfooter) { ?>
        <footer>
            <div class="row">
            	<div id="page-footer" class="large-12 columns">
                    <hr>
    	        	<p class="helplink"><?php echo page_doc_link(get_string('moodledocslink')) ?></p>
    	        	<?php
    		        echo $OUTPUT->home_link();
    		        echo $OUTPUT->standard_footer_html();
    		        ?>
    		    </div>
            </div>
		</footer>
	<?php } ?>
	<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>