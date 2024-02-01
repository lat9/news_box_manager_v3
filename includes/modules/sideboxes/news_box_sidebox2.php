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
// This "instance" of the news sidebox gathers only items configured for the sidebox#2
// display.
//
$news_sidebox_num = 2;
$news_sidebox_content = NEWS_BOX_SHOW_NEWS_CAT_SB2;
$news_sidebox_show_max = NEWS_BOX_SHOW_NEWS_SB2;
$news_sidebox_layout = NEWS_BOX_LAYOUT_SB2;

$title = '';
$title_link = '';

require DIR_WS_MODULES . zen_get_module_directory('sideboxes/news_box_sidebox.php');
