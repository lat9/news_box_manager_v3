<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.6 and later by lat9.
// Copyright (C) 2015-2021, Vinos de Frutas Tropicales
//
// +----------------------------------------------------------------------+
// | Do Not Remove: Coded for Zen-Cart by geeks4u.com                     |
// | Dedicated to Memory of Amelita "Emmy" Abordo Gelarderes              |
// +----------------------------------------------------------------------+
//
$max_news_items = (((int)$max_news_items) <= 0) ? 10 : $max_news_items;
$news_box_content_length = (int)$news_box_content_length;

$news_and_clause = (empty($news_and_clause)) ? '' : $news_and_clause;

$languages_id = (int)$_SESSION['languages_id'];

if ($news_box_format == 'Individual') {
    $news_limit = " LIMIT $max_news_items";
    $news_box_query_raw =
        "SELECT n.box_news_id, nc.news_title, nc.news_content, n.news_start_date, n.news_end_date, n.news_content_type
           FROM " . TABLE_BOX_NEWS_CONTENT . " nc
                INNER JOIN " . TABLE_BOX_NEWS . " n 
                    ON n.box_news_id = nc.box_news_id
          WHERE n.news_status = 1 
            AND nc.languages_id = $languages_id 
            $news_and_clause
            AND now() >= n.news_start_date
            AND (n.news_end_date IS NULL OR now() <= n.news_end_date)
          ORDER BY n.news_start_date DESC, n.box_news_id DESC";
} else {
    $news_limit = '';
    $news_box_query_raw =
        "SELECT n.*, nc.news_title, nc.news_content
           FROM " . TABLE_BOX_NEWS . " n
                INNER JOIN (
                    SELECT news_content_type, MAX(news_start_date) as last_updated
                      FROM " . TABLE_BOX_NEWS . "
                     WHERE news_status = 1
                       AND now() >= news_start_date
                       AND (news_end_date IS NULL OR now() <= news_end_date)
                     GROUP BY news_content_type) AS r
                INNER JOIN " . TABLE_BOX_NEWS_CONTENT . " AS nc
                    ON nc.box_news_id = n.box_news_id
                    AND nc.languages_id = $languages_id
          WHERE n.news_content_type = r.news_content_type
            AND n.news_start_date = r.last_updated
            $news_and_clause";
}

if ($news_box_use_split) {
    $news_split = new splitPageResults($news_box_query_raw, $max_news_items);
    $news_info = $db->Execute($news_split->sql_query);
} else {
    $news_info = $db->Execute($news_box_query_raw . $news_limit);
}

$news = array();
while (!$news_info->EOF) {
    $news_box_id = $news_info->fields['box_news_id'];
    $news[$news_box_id] = array(
        'title' => nl2br($news_info->fields['news_title']),
        'start_date' => (NEWS_BOX_DATE_FORMAT == 'short') ? zen_date_short($news_info->fields['news_start_date']) : zen_date_long($news_info->fields['news_start_date']),
        'start_date_raw' => $news_info->fields['news_start_date'],
        'type' => $news_info->fields['news_content_type'],
    );
    if ($news_box_content_length != 0) {
        if ($news_box_content_length == -1) {
            $news[$news_box_id]['news_content'] = $news_info->fields['news_content'];
        } elseif ($news_box_content_length > 0) {
            $news[$news_box_id]['news_content'] = zen_trunc_string(zen_clean_html($news_info->fields['news_content']), $news_box_content_length);
        }
    }
    if (!empty($news_info->fields['news_end_date'])) {
        $news[$news_box_id]['end_date'] = (NEWS_BOX_DATE_FORMAT == 'short') ? zen_date_short($news_info->fields['news_end_date']) : zen_date_long($news_info->fields['news_end_date']);
    }
    $news_info->MoveNext();
}
unset($news_info, $news_and_clause, $max_news_items);
