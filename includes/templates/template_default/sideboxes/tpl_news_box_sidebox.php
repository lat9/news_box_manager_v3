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
$layout = 1;  // 0 = Java Script Fader | 1 = Static 10 last news

$content = '<div class="sideBoxContent"><ol>';  
while (!$news_box_query->EOF) {
  $content .= '<li><a href="' . zen_href_link (FILENAME_MORE_NEWS, 'news_id=' . $news_box_query->fields['box_news_id']) . '">' . $news_box_query->fields['news_title']. '</a></li>'; 
  $news_box_query->MoveNext();
  
}
$content .= '</ol></div>';