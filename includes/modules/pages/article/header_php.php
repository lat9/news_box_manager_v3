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
$zco_notifier->notify('NOTIFY_HEADER_ARTICLE_START');

$_SESSION['navigation']->remove_current_page();

require DIR_WS_MODULES . zen_get_module_directory('require_languages.php');
$breadcrumb->add(NAVBAR_TITLE);

$news_id = (isset($_GET['p'])) ? (int)$_GET['p'] : 0;
$languages_id = (int)$_SESSION['languages_id'];

// -----
// NOTE:  The results of this query are also used by the plugin's auto-loaded observer
// that manages a news item's metatags!
//
$news_box_query = $db->Execute(
    "SELECT *
       FROM " . TABLE_BOX_NEWS_CONTENT . " nc
            INNER JOIN " . TABLE_BOX_NEWS . " n 
                ON nc.box_news_id = n.box_news_id
      WHERE nc.box_news_id = $news_id 
        AND nc.languages_id = $languages_id 
        AND n.news_status = 1 
        AND now() >= n.news_start_date
        AND (n.news_end_date IS NULL OR now() <= n.news_end_date) 
      LIMIT 1"
);
if ($news_box_query->EOF) {
    $messageStack->add_session(TEXT_NEWS_ARTICLE_NOT_FOUND, 'caution');
    zen_redirect(zen_href_link(FILENAME_ALL_ARTICLEs));
}

$news_title = $news_box_query->fields['news_title'];
$news_content = $news_box_query->fields['news_content'];
$news_type = $news_box_query->fields['news_content_type'];

$end_date = (!empty($news_box_query->fields['news_end_date']) && $news_box_query->fields['news_end_date'] != '0001-01-01 00:00:00') ? $news_box_query->fields['news_end_date'] : '';
$start_date = $news_box_query->fields['news_start_date'];

if (NEWS_BOX_DATE_FORMAT == 'short' || (NEWS_BOX_DATE_FORMAT == 'MdY' && !empty($end_date))) {
    $start_date = zen_date_short($start_date);
} elseif (NEWS_BOX_DATE_FORMAT == 'MdY') {
    $start_date = explode(' ', date('M d Y', strtotime($start_date)));
} else {
    $start_date = zen_date_long($start_date);
}

if (!empty($end_date)) {
    $end_date = (NEWS_BOX_DATE_FORMAT == 'short') ? zen_date_short($news_box_query->fields['news_end_date']) : zen_date_long($news_box_query->fields['news_end_date']);
}

$canonicalLink = zen_href_link(FILENAME_ARTICLE, "p=$news_id");

$zco_notifier->notify('NOTIFY_HEADER_ARTICLE_END');
