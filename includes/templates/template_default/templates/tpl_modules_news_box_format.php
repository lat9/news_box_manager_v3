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
$max_news_items = (int)NEWS_BOX_SHOW_CENTERBOX;
$news_box_content_length = NEWS_BOX_CONTENT_LENGTH_CENTERBOX;
if ($max_news_items > 0) {
  $news_box_use_split = false;
  include (DIR_WS_MODULES . zen_get_module_directory (FILENAME_NEWS_BOX_FORMAT));
  
  if (count ($news) > 0) {
?>
<div class="centerBoxWrapper" id="newsBoxManager">
  <h2 class="centerBoxHeading"><?php echo BOX_HEADING_NEWS_BOX; ?> <a href="<?php echo zen_href_link (FILENAME_NEWS_ARCHIVE); ?>"><?php echo TEXT_ALL_NEWS; ?></a></h2>
  <div id="news-info"><?php echo TEXT_NEWS_BOX_INFO; ?></div>
  <div id="news-table">
    <div class="news-row news-heading">
      <div class="news-cell"><?php echo NEWS_BOX_HEADING_DATES; ?></div>
      <div class="news-cell"><?php echo NEWS_BOX_HEADING_TITLE; ?></div>
    </div>
<?php
    $row_class = 'rowEven';
    foreach ($news as $news_id => $news_item) {
      $news_content = '';
      if (isset ($news_item['news_content'])) {
        $news_content = ' <div class="news-content">' . $news_item['news_content'] . '</div>';
        
      }
?>
    <div class="news-row <?php echo $row_class; ?>">
      <div class="news-cell"><?php echo $news_item['start_date'] . ((isset ($news_item['end_date'])) ? ( NEWS_DATE_SEPARATOR . $news_item['end_date']) : ''); ?></div>
      <div class="news-cell"><a href="<?php echo zen_href_link (FILENAME_MORE_NEWS, 'news_id=' . $news_id); ?>"><?php echo $news_item['title']; ?></a><?php echo $news_content; ?></div>
    </div>
<?php
      $row_class = ($row_class == 'rowEven') ? 'rowOdd' : 'rowEven';
    
    }
?>
  </div>
  <div class="clearBoth"></div>
</div>
<?php
  }
} 