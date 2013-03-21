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

}