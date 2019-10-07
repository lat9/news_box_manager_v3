<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.6 and later by lat9.
// Copyright (C) 2015-2019, Vinos de Frutas Tropicales
//
// +----------------------------------------------------------------------+
// | Do Not Remove: Coded for Zen-Cart by geeks4u.com                     |
// | Dedicated to Memory of Amelita "Emmy" Abordo Gelarderes              |
// +----------------------------------------------------------------------+
//
// -----
// The following variables have been set by /includes/modules/news_box_sidebox_base.php:
//
// - $news_sidebox_layout ... Identifies the 'type' of layout to apply to the sidebox information.
//
if ($news_sidebox_layout == 'List') {
    $content = '<div class="sideBoxContent newsBoxList"><ol>' . PHP_EOL;  
    while (!$news_box_query->EOF) {
        $news_id = $news_box_query->fields['box_news_id'];
        $news_title = $news_box_query->fields['news_title'];
        $news_content_type = $news_box_query->fields['news_content_type'];
        $content_class = "nb-t$news_content_type";
        $content .= '<li><a href="' . zen_href_link(FILENAME_ARTICLE, "p=$news_id") . '" class="' . $content_class . '">' . $news_title. '</a></li>' . PHP_EOL; 
        $news_box_query->MoveNext();
    }
    $content .= '</ol></div>' . PHP_EOL;
} else {
    $content = '<div class="sideBoxContent newsBoxGrid">' . PHP_EOL;
    while (!$news_box_query->EOF) {
        $news_id = $news_box_query->fields['box_news_id'];
        $news_title = $news_box_query->fields['news_title'];
        $news_content_type = $news_box_query->fields['news_content_type'];
        
        $news_content = ($news_sidebox_layout == 'GridTitleDateDesc') ? $news_box_query->fields['news_content'] : '';
        $news_content = zen_trunc_string($news_content);
        
        $news_start_date = $news_box_query->fields['news_start_date'];
        $news_end_date = $news_box_query->fields['news_end_date'];
        $news_date_range = '';
        if (!empty($news_start_date)) {
            $news_date_range = (NEWS_BOX_SIDEBOX_DATE_FORMAT == 'short') ? zen_date_short($news_start_date) : zen_date_long($news_start_date);
            if (!empty($news_end_date)) {
                $news_date_range .= (NEWS_DATE_SEPARATOR . ((NEWS_BOX_SIDEBOX_DATE_FORMAT == 'short') ? zen_date_short($news_end_date) : zen_date_long($news_end_date)));
            }
        }
        
        $content_class = "nb-t$news_content_type";
        
        $content .= '<div class="nb-grid-inner ' . $content_class . '">' . PHP_EOL;
        $content .= '   <a href="' . zen_href_link(FILENAME_ARTICLE, "p=$news_id") . '" class="' . $content_class . '">' . $news_title. '</a>' . PHP_EOL;
        $content .= '   <div class="nb-dates">' . $news_date_range . '</div>' . PHP_EOL;
        $content .= '   <div class="nb-content">' . $news_content . '</div>' . PHP_EOL;
        $content .= '</div>' . PHP_EOL;
        $news_box_query->MoveNext();
    }
    $content .= '</div>' . PHP_EOL;
}
