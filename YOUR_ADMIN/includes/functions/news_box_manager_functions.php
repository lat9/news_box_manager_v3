<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.8a and later by lat9.
//
// Last updated: v3.2.2
//
// Copyright (C) 2019-2026, Vinos de Frutas Tropicales
//
function zen_get_news_title(string|int $box_news_id, string|int $language_id = ''): string
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

function zen_get_news_content(string|int $box_news_id, string|int $language_id = ''): string
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

function zen_get_news_info(string|int $box_news_id, string|int $language_id = ''): array
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
