<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.8a and later by lat9.
// Copyright (C) 2015-2024, Vinos de Frutas Tropicales
//
// +----------------------------------------------------------------------+
// | Do Not Remove: Coded for Zen-Cart by geeks4u.com                     |
// | Dedicated to Memory of Amelita "Emmy" Abordo Gelarderes              |
// +----------------------------------------------------------------------+
//
// -----
// This processing, common to the 'news_box_sidebox' (aka News Box Sidebox #1) and 'news_box_sidebox2'
// processing.
//
// Determine, first, if this request is for Sidebox #1 or Sidebox #2, initializing the
// Sidebox #1 values if needed.
//
if (empty($news_sidebox_num)) {
    $news_sidebox_num = 1;
    $news_sidebox_content = NEWS_BOX_SHOW_NEWS_CAT_SB1;
    $news_sidebox_show_max = NEWS_BOX_SHOW_NEWS_SB1;
    $news_sidebox_layout = NEWS_BOX_LAYOUT_SB1;

    $title = '';
    $title_link = '';
}

if (!empty($news_sidebox_show_max) && ((int)$news_sidebox_show_max) > 0) {
    $news_sidebox_show_max = (int)$news_sidebox_show_max;
    
    if (empty($news_sidebox_content)) {
        $news_sidebox_content = 'All';
    }
    switch ($news_sidebox_content) {
        case '1':
        case '2':
        case '3':
        case '4':
            $news_and_clause = " AND n.news_content_type = $news_sidebox_content";
            $news_box_params = "t=$news_sidebox_content";
            $news_box_name_define = "BOX_NEWS_NAME_TYPE$news_sidebox_content";
            $news_box_name = (defined($news_box_name_define)) ? constant($news_box_name_define) : "Unknown [$news_sidebox_content]";
            break;
        default:
            $news_and_clause = '';
            $news_box_params = '';
            $news_box_name = BOX_NEWS_NAME_ALL;
            break;
    }

    $languages_id = (int)$_SESSION['languages_id'];
    $news_box_query = $db->Execute(
        "SELECT nc.news_title, nc.news_content, n.*
           FROM " . TABLE_BOX_NEWS_CONTENT . " nc
                INNER JOIN " . TABLE_BOX_NEWS . " n
                    ON n.box_news_id = nc.box_news_id
                   AND nc.languages_id = $languages_id
          WHERE n.news_status = 1
            $news_and_clause
            AND now() >= n.news_start_date
            AND (n.news_end_date IS NULL OR now() <= n.news_end_date)
       ORDER BY n.news_start_date DESC, n.box_news_id DESC
          LIMIT $news_sidebox_show_max"
    );
    if (!$news_box_query->EOF) {
        if (empty($news_box_layout)) {
            $news_box_layout = 'List';
        }
        require $template->get_template_dir('tpl_news_box_sidebox.php', DIR_WS_TEMPLATE, $current_page_base, 'sideboxes') . '/tpl_news_box_sidebox.php';

        $title_link = '';
        if (empty($title)) {
            $title = sprintf(BOX_HEADING_NEWS_BOX_CATEGORY, $news_box_name) . '&nbsp;&nbsp;<a href="' . zen_href_link(FILENAME_ALL_ARTICLES, $news_box_params) . '">' . TEXT_ALL_NEWS . '</a>';
        }
        require $template->get_template_dir($column_box_default, DIR_WS_TEMPLATE, $current_page_base, 'common') . '/' . $column_box_default;
    }
}

// -----
// "Reset" the news-sidebox number, in case multiple sidebox versions are available, so that we'll
// render the base (SideBox #1) if it's lower in the sort-order than previous versions.
//
unset($news_sidebox_num, $news_and_clause, $max_news_items);
