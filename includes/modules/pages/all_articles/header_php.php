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

$breadcrumb->add(sprintf(NAVBAR_TITLE, $news_type_name));

$max_news_items = NEWS_BOX_SHOW_ARCHIVE;
$news_box_content_length = NEWS_BOX_CONTENT_LENGTH_ARCHIVE;
$news_box_use_split = true;
$news_box_format = 'Individual';

require DIR_WS_MODULES . zen_get_module_directory(FILENAME_NEWS_BOX_FORMAT);

$zco_notifier->notify('NOTIFY_ALL_ARTICLES_HEADER_END');
