<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.6 and later by lat9.
// Copyright (C) 2019-2024, Vinos de Frutas Tropicales
//
function zen_get_news_title($box_news_id, $language_id = '')
{
    if ($language_id === '') {
        $language_id = $_SESSION['languages_id'];
    }
    $news = $GLOBALS['db']->Execute(
        "SELECT *
           FROM " . TABLE_BOX_NEWS_CONTENT . "
          WHERE box_news_id = " . (int)$box_news_id . "
            AND languages_id = " . (int)$language_id . "
          LIMIT 1"
    );
    return ($news->EOF) ? '' : $news->fields['news_title'];
}

function zen_get_news_content($box_news_id, $language_id = '')
{
    if ($language_id === '') {
        $language_id = $_SESSION['languages_id'];
    }
    $news = $GLOBALS['db']->Execute(
        "SELECT *
           FROM " . TABLE_BOX_NEWS_CONTENT . "
          WHERE box_news_id = " . (int)$box_news_id . "
            AND languages_id = " . (int)$language_id . "
          LIMIT 1"
    );
    return ($news->EOF) ? '' : $news->fields['news_content'];
}

function zen_get_news_info($box_news_id, $language_id = ''): array
{
    if ($language_id === '') {
        $language_id = $_SESSION['languages_id'];
    }
    $news = $GLOBALS['db']->Execute(
        "SELECT *
           FROM " . TABLE_BOX_NEWS_CONTENT . "
          WHERE box_news_id = " . (int)$box_news_id . "
            AND languages_id = " . (int)$language_id . "
          LIMIT 1"
    );
    return ($news->EOF) ? [] : $news->fields;
}
