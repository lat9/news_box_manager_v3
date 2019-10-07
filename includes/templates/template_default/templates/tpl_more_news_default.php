<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.1 and later by lat9.
// Copyright (C) 2015-2018, Vinos de Frutas Tropicales
//
// +----------------------------------------------------------------------+
// | Do Not Remove: Coded for Zen-Cart by geeks4u.com                     |
// | Dedicated to Memory of Amelita "Emmy" Abordo Gelarderes              |
// +----------------------------------------------------------------------+
//
?>
<div class="centerColumn" id="moreNewsDefault">
  <h1 id="moreNewsHeading"><?php echo HEADING_TITLE . ' ' . $news_title; ?></h1>

  <div class="newsInfo"><?php echo ($start_date === false) ? TEXT_NEWS_ARTICLE_NOT_FOUND : ('<span class="news_header">' . TEXT_NEWS_PUBLISHED_DATE . '</span><span class="news_date">' . $start_date . ((!empty($end_date)) ? ( NEWS_DATE_SEPARATOR . $end_date) : '') . '</span>'); ?></div>
  <div class="newsContent"><?php echo $news_content; ?></div>

  <div class="buttonRow back"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>
  
</div>
