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
$zco_notifier->notify('NOTIFY_ALL_ARTICLES_HEADER_START');
require DIR_WS_MODULES . zen_get_module_directory('require_languages.php');

// -----
// Determine the sub-content type name, if specified.
//
$news_type_name = '';
$news_and_clause = '';
$news_type = $_GET['t'] ?? '';
switch ($news_type) {
    case '1':
    case '2':
    case '3':
    case '4':
        $news_box_name_define = "BOX_NEWS_NAME_TYPE" . $news_type;
        $news_and_clause = ' AND n.news_content_type = ' . $news_type;
        $news_type_name = (defined($news_box_name_define)) ? constant($news_box_name_define) : "Unknown [$news_type]";
        $canonicalLink = zen_href_link(FILENAME_ALL_ARTICLES, "t=$news_type");
        break;
    default:
        $canonicalLink = zen_href_link(FILENAME_ALL_ARTICLES);
        break;
}

$nb_sort_order = (int)($_GET['sort'] ?? 0);
switch ($nb_sort_order) {
    case 2:
        $nb_order_by = 'n.news_start_date ASC, n.box_news_id ASC';
        break;
    case 3:
        $nb_order_by = 'nc.news_title ASC, n.box_news_id ASC';
        break;
    case 4:
        $nb_order_by = 'nc.news_title DESC, n.box_news_id DESC';
        break;
    default:
        $nb_order_by = 'n.news_start_date DESC, n.box_news_id DESC';
        if ($nb_sort_order !== 1) {
            $nb_sort_order = 1;
            unset($_GET['sort']);
        }
        break;
}

$breadcrumb->add(sprintf(NAVBAR_TITLE, $news_type_name));

$max_news_items = NEWS_BOX_SHOW_ARCHIVE;
$news_box_content_length = NEWS_BOX_CONTENT_LENGTH_ARCHIVE;
$news_box_use_split = true;
$news_box_format = 'Individual';

require DIR_WS_MODULES . zen_get_module_directory(FILENAME_NEWS_BOX_FORMAT);

$zco_notifier->notify('NOTIFY_ALL_ARTICLES_HEADER_END');
