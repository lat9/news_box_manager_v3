<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.6 and later by lat9.
// Copyright (C) 2015-2024, Vinos de Frutas Tropicales
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
if ($news_sidebox_layout === 'List') {
    $content = '<div class="sideBoxContent newsBoxList"><ol>' . PHP_EOL;  
    foreach ($news_box_query as $next_news) {
        $news_sidebox_id = $next_news['box_news_id'];
        $news_sidebox_title = $next_news['news_title'];
        $news_content_type = $next_news['news_content_type'];
        $content_class = "nb-t$news_content_type";
        $content .= '<li class="py-1"><a href="' . zen_href_link(FILENAME_ARTICLE, "p=$news_sidebox_id") . '" class="' . $content_class . '">' . $news_sidebox_title. '</a></li>' . PHP_EOL; 
        $news_box_query->MoveNext();
    }
    $content .= '</ol></div>' . PHP_EOL;
} else {
    $content = '<div class="sideBoxContent newsBoxGrid">' . PHP_EOL;
    foreach ($news_box_query as $next_news) {
        $news_sidebox_id = $next_news['box_news_id'];
        $news_sidebox_title = $next_news['news_title'];
        $news_content_type = $next_news['news_content_type'];
        
        $news_sidebox_content = ($news_sidebox_layout === 'GridTitleDateDesc') ? $next_news['news_content'] : '';
        $news_sidebox_content = zen_trunc_string($news_sidebox_content);

        $news_start_date = $next_news['news_start_date'];
        $news_end_date = $next_news['news_end_date'];
        $news_date_range = '';
        if (!empty($news_start_date)) {
            $news_date_range = (NEWS_BOX_SIDEBOX_DATE_FORMAT === 'short') ? zen_date_short($news_start_date) : zen_date_long($news_start_date);
            if (!empty($news_end_date)) {
                $news_date_range .= (NEWS_DATE_SEPARATOR . ((NEWS_BOX_SIDEBOX_DATE_FORMAT === 'short') ? zen_date_short($news_end_date) : zen_date_long($news_end_date)));
            }
        }

        $content_class = "nb-t$news_content_type";

        $content .= '<div class="nb-grid-inner ' . $content_class . '">' . PHP_EOL;
        $content .= '   <a href="' . zen_href_link(FILENAME_ARTICLE, "p=$news_sidebox_id") . '" class="' . $content_class . '">' . $news_sidebox_title. '</a>' . PHP_EOL;
        $content .= '   <div class="nb-dates">' . $news_date_range . '</div>' . PHP_EOL;
        $content .= '   <div class="nb-content">' . $news_sidebox_content . '</div>' . PHP_EOL;
        $content .= '</div>' . PHP_EOL;
        $news_box_query->MoveNext();
    }
    $content .= '</div>' . PHP_EOL;
}
