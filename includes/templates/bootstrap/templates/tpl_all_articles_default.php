<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.8a and later by lat9.
// Copyright (C) 2015-2026, Vinos de Frutas Tropicales
//
// +----------------------------------------------------------------------+
// | Do Not Remove: Coded for Zen-Cart by geeks4u.com                     |
// | Dedicated to Memory of Amelita "Emmy" Abordo Gelarderes              |
// +----------------------------------------------------------------------+
//
?>
<div class="centerColumn" id="allArticlesDefault">
    <h1><?= sprintf(HEADING_TITLE, $news_type_name) ?></h1>
<?php
if (count($news) === 0) {
?>
    <div id="no-news" class="text-center"><p><?= TEXT_NO_NEWS_CURRENTLY ?></p></div>
<?php
} else {
?>
    <div id="filter-wrapper" class="group row d-flex justify-content-center">
        <div id="all-articles-sort" class="my-1">
<?php
    echo
        zen_draw_form('sorter_form', zen_href_link(FILENAME_ALL_ARTICLES), 'get', 'class="form-inline"') .
        zen_draw_hidden_field('main_page', FILENAME_ALL_ARTICLES) .
        zen_hide_session_id();

    $sort_options = [
        ['id' => '1', 'text' => TEXT_INFO_NB_SORT_BY_DATE],
        ['id' => '2', 'text' => TEXT_INFO_NB_SORT_BY_DATE_DESC],
        ['id' => '3', 'text' => TEXT_INFO_NB_SORT_BY_NAME_AZ],
        ['id' => '4', 'text' => TEXT_INFO_NB_SORT_BY_NAME_ZA],
    ];

    echo zen_draw_label(TEXT_INFO_SORT_BY, 'disp-sort-order');
    echo zen_draw_pull_down_menu('sort', $sort_options, $nb_sort_order, 'class="mx-2" id="disp-sort-order" onchange="this.form.submit();"');

    echo '</form>';
?>
        </div>
        <div id="all-articles-search" class="my-1">
<?php
    echo
        zen_draw_form('nb-search', zen_href_link(FILENAME_ALL_ARTICLES), 'get', 'class="form-inline"') .
        zen_draw_hidden_field('main_page', FILENAME_ALL_ARTICLES) .
        zen_hide_session_id();

    $nb_keyword = zen_output_string_protected($_GET['nb_keyword'] ?? '');

    echo zen_draw_label(TEXT_NEWS_BOX_SEARCH_LABEL, 'nb-keyword', 'class="sr-only"');
    echo zen_draw_input_field('nb_keyword', $nb_keyword, 'class="mx-2" size="21" maxlength="100" placeholder="' . TEXT_NEWS_BOX_SEARCH_LABEL . '"  aria-label="' . SEARCH_DEFAULT_TEXT . '" id="nb-keyword"');

    if (strtolower(IMAGE_USE_CSS_BUTTONS) === 'yes') {
        echo zen_image_submit(BUTTON_IMAGE_SEARCH, HEADER_SEARCH_BUTTON);
    } else {
?>
            <input type="submit" value="<?= HEADER_SEARCH_BUTTON ?>">
<?php
    }
    echo '</form>';
?>
        </div>
    </div>
    <br>
    <p id="news-info" class="text-center clearBoth"><?= TEXT_NEWS_BOX_INSTRUCTIONS ?></p>
<?php
    if (NEWS_BOX_ALL_ARTICLES_DISPLAY === 'Table') {
?>
    <div id="news-table">
        <div class="news-row news-heading">
            <div class="news-cell"><?= NEWS_BOX_HEADING_DATES ?></div>
            <div class="news-cell"><?= NEWS_BOX_HEADING_TITLE ?></div>
        </div>
<?php
        foreach ($news as $news_id => $news_item) {
            $news_content = '';
            if (isset($news_item['news_content'])) {
                $news_content = ' <div class="news-content">' . $news_item['news_content'] . '</div>';
            }
            $row_class = 'nbt-' . $news_item['type'];
?>
        <div class="news-row <?= $row_class ?>">
            <div class="news-cell news-dates"><?= $news_item['start_date'] . ((isset($news_item['end_date'])) ? (NEWS_DATE_SEPARATOR . $news_item['end_date']) : '') ?></div>
            <div class="news-cell"><a href="<?= zen_href_link(FILENAME_ARTICLE, 'p=' . $news_id) ?>"><?= $news_item['title'] ?></a><?= $news_content ?></div>
        </div>
<?php
        }
?>
    </div>
    <div class="clearBoth"></div>
<?php
    // -----
    // Start 'Listing' display ...
    //
    } else {
?>
    <div class="card">
        <div class="card-body">
            <div class="row">
<?php
        foreach ($news as $news_id => $news_item) {
            $article_class = "nbt-" . $news_item['type'];
            if (!empty($news_item['end_date'])) {
                $news_dates = $news_item['start_date'] . NEWS_DATE_SEPARATOR . $news_item['end_date'];
            } else {
                if (NEWS_BOX_DATE_FORMAT === 'MdY') {
                    $article_class .= ' news-mdy';
                    $start_date = explode(' ', date('M d Y', strtotime($news_item['start_date_raw'])));
                    $news_dates = '<span>' . $start_date[0] . '</span><span class="nb-day">' . $start_date[1] . '</span><span>' . $start_date[2] . '</span>';
                } else {
                    $news_dates = $news_item['start_date'];
                }
            }
?>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="card-title h4">
                                <a href="<?= zen_href_link(FILENAME_ARTICLE, "p=$news_id") ?> "class="stretched-link"><?= $news_item['title'] ?></a>
                            </p>
                            <p class="news-content card-text"><?= $news_item['news_content'] ?? '' ?></p>
                            <small class="news-dates"><?= $news_dates ?></small>
                        </div>
                    </div>
                </div>
<?php
        }
?>
            </div>
        </div>
    </div>
<?php
    }
?>
    <div class="row py-2">
        <div class="navSplitPagesResult col-md-6"><?= $news_split->display_count(TEXT_DISPLAY_NUMBER_OF_NEWS_ARTICLES) ?></div>
        <div class="navSplitPagesLinks col-md-6"><?= TEXT_RESULT_PAGE . ' ' . $news_split->display_links(MAX_DISPLAY_PAGE_LINKS, zen_get_all_get_params(['page', 'info', 'x', 'y', 'main_page'])) ?></div>
    </div>

    <div class="buttonRow text-center"><?= zca_back_link() ?></div>
<?php
}
?>
</div>
