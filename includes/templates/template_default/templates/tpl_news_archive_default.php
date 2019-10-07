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
?>
<div class="centerColumn" id="newsArchiveDefault">
  <h1><?php echo HEADING_TITLE; ?></h1>
<?php
if (count ($news) == 0) {
?>
  <div><p><?php echo TEXT_NO_NEWS_CURRENTLY; ?></p></div>
<?php
} else {
?>
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
}
?>
  </div>
  <div class="clearBoth"></div>
  
  <div class="navSplitPagesLinks forward"><?php echo TEXT_RESULT_PAGE . ' ' . $news_split->display_links (MAX_DISPLAY_PAGE_LINKS, zen_get_all_get_params (array ('page', 'info', 'x', 'y', 'main_page'))); ?></div>
  <div class="navSplitPagesResult"><?php echo $news_split->display_count (TEXT_DISPLAY_NUMBER_OF_NEWS_ARTICLES); ?></div>
  
  <div class="buttonRow back"><?php echo zen_back_link() . zen_image_button (BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>
</div>