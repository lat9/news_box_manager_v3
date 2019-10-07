<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.1 and later by lat9.
// Copyright (C) 2015-2018, Vinos de Frutas Tropicales
//
// +----------------------------------------------------------------------+
// | Do Not Remove: Coded for Zen-Cart by geeks4u.com                     |
// | Dedicated to Memory of Amelita "Emmy" Abordo Gelarderes              |
// +----------------------------------------------------------------------+
//
$languages_id = (int)$_SESSION['languages_id'];
$news_box_query = $db->Execute(
    "SELECT nc.news_title, nc.news_content, n.*
       FROM " . TABLE_BOX_NEWS_CONTENT . " nc
            INNER JOIN " . TABLE_BOX_NEWS . " n
                ON n.box_news_id = nc.box_news_id
      WHERE nc.languages_id = $languages_id 
        AND n.news_status = 1 
        AND now() >= n.news_start_date
        AND (n.news_end_date IS NULL OR now() <= n.news_end_date)
   ORDER BY n.news_start_date DESC, n.box_news_id DESC
      LIMIT " . (int)NEWS_BOX_SHOW_NEWS
);
if (!$news_box_query->EOF) {
    require $template->get_template_dir('tpl_news_box_sidebox.php', DIR_WS_TEMPLATE, $current_page_base, 'sideboxes') . '/tpl_news_box_sidebox.php';

    $title = BOX_HEADING_NEWS_BOX;
    $title_link = FILENAME_NEWS_ARCHIVE;

    require $template->get_template_dir($column_box_default, DIR_WS_TEMPLATE, $current_page_base, 'common') . '/' . $column_box_default;
}
