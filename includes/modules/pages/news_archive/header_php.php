<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.1 and later by lat9.
// Copyright (C) 2015, Vinos de Frutas Tropicales
//
// +----------------------------------------------------------------------+
// | Do Not Remove: Coded for Zen-Cart by geeks4u.com                     |
// | Dedicated to Memory of Amelita "Emmy" Abordo Gelarderes              |
// +----------------------------------------------------------------------+
//
$_SESSION['navigation']->remove_current_page();

require(DIR_WS_MODULES . zen_get_module_directory ('require_languages.php'));
$breadcrumb->add (NAVBAR_TITLE);

$max_news_items = NEWS_BOX_SHOW_ARCHIVE;
$news_box_content_length = NEWS_BOX_CONTENT_LENGTH_ARCHIVE;
$news_box_use_split = true;

include (DIR_WS_MODULES . zen_get_module_directory (FILENAME_NEWS_BOX_FORMAT));