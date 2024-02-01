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
$max_news_items = (int)NEWS_BOX_SHOW_CENTERBOX;
$news_box_content_length = NEWS_BOX_CONTENT_LENGTH_CENTERBOX;
$news_box_format = NEWS_BOX_HOMEPAGE_DISPLAY;
if ($max_news_items > 0) {
    $news_box_use_split = false;
    require DIR_WS_MODULES . zen_get_module_directory(FILENAME_NEWS_BOX_FORMAT);
  
    if (count($news) > 0) {
        if ($news_box_format === 'Individual') {
?>
<div class="centerBoxWrapper" id="newsBoxManager">
    <h2 class="centerBoxHeading"><?= BOX_HEADING_NEWS_BOX ?> <a href="<?= zen_href_link(FILENAME_ALL_ARTICLES) ?>"><?= TEXT_ALL_NEWS ?></a></h2>
    <div id="news-info"><?= TEXT_NEWS_BOX_INFO ?></div>
    <div id="news-table">
        <div class="news-row news-heading">
            <div class="news-cell"><?= NEWS_BOX_HEADING_DATES ?></div>
            <div class="news-cell"><?= NEWS_BOX_HEADING_TITLE ?></div>
        </div>
<?php
            $row_class = 'rowEven';
            foreach ($news as $news_id => $news_item) {
                $news_content = '';
                if (isset($news_item['news_content'])) {
                    $news_content = ' <div class="news-content">' . $news_item['news_content'] . '</div>';
                }
?>
        <div class="news-row <?= $row_class ?>">
            <div class="news-cell"><?= $news_item['start_date'] . ((isset ($news_item['end_date'])) ? ( NEWS_DATE_SEPARATOR . $news_item['end_date']) : '') ?></div>
            <div class="news-cell"><a href="<?= zen_href_link(FILENAME_ARTICLE, 'p=' . $news_id) ?>"><?= $news_item['title'] ?></a><?= $news_content ?></div>
        </div>
<?php
                $row_class = ($row_class == 'rowEven') ? 'rowOdd' : 'rowEven';
            }
?>
    </div>
    <div class="clearBoth"></div>
</div>
<?php
        // -----
        // Rendering for a by-categories display.
        //
        } else {
            $row = 0;
            $col = 0;
            $col_width = floor(100 / count($news));
            $list_box_contents = array();
            foreach ($news as $news_id => $news_item) {
                $nb_content = '';
                if (isset($news_item['news_content'])) {
                    $nb_content = '<div class="nb-content">' . $news_item['news_content'] . '</div>';
                }
                
                $list_box_contents[$row][$col] = array(
                    'params' => 'class="centerBoxContentsNews centeredContent back" style="width:' . $col_width . '%;"',
                    'text' => 
                        '<div class="nb-inner">' . PHP_EOL .
                        '    <div>' . PHP_EOL .
                        '        <div class="nb-cat nb-back">' . constant('BOX_NEWS_NAME_TYPE' . $news_item['type']) . '</div>' . PHP_EOL .
                        '        <div class="nb-more nb-forward"><a href="' . zen_href_link(FILENAME_ALL_ARTICLES, 't=' . $news_item['type']) . '">See All</a></div>' . PHP_EOL .
                        '    </div>' . PHP_EOL .
                        '    <div class="nb-clear"></div>' . PHP_EOL .
                        '    <a class="nb-title" href="' . zen_href_link(FILENAME_ARTICLE, 'p=' . $news_id) . '">' . $news_item['title'] . '</a>' . PHP_EOL .
                        '    <span class="nb-start">' . $news_item['start_date'] . '</span>' . PHP_EOL . $nb_content . PHP_EOL .
                        '</div>' . PHP_EOL
                );
                $col++;
            }
?>
<div class="centerBoxWrapper" id="nb-cats">
<?php
            $title = '';
            require $template->get_template_dir('tpl_columnar_display.php', DIR_WS_TEMPLATE, $current_page_base, 'common') . '/tpl_columnar_display.php';
?>
</div>
<?php
        }
    }
}
