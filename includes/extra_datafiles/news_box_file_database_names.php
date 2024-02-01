<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.8a and later by lat9.
// Copyright (C) 2015-2019, Vinos de Frutas Tropicales
//
// +----------------------------------------------------------------------+
// | Do Not Remove: Coded for Zen-Cart by geeks4u.com                     |
// | Dedicated to Memory of Amelita "Emmy" Abordo Gelarderes              |
// +----------------------------------------------------------------------+
//
define('TABLE_BOX_NEWS', DB_PREFIX . 'box_news');
define('TABLE_BOX_NEWS_CONTENT', DB_PREFIX . 'box_news_content');

// -----
// "Legacy" pages.  Starting with v3.0.0, the 'more_news' page simply performs
// a redirect-permanent to the 'article' page and the 'news_archive' page
// performs a redirect to the 'all_articles' page.
//
define('FILENAME_MORE_NEWS', 'more_news');
define('FILENAME_NEWS_ARCHIVE', 'news_archive');

// -----
// Common formatting script.
//
define('FILENAME_NEWS_BOX_FORMAT', 'news_box_format');

// -----
// New pages, introduced in v3.0.0.
//
define('FILENAME_ALL_ARTICLES', 'all_articles');
define('FILENAME_ARTICLE', 'article');
