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
$article_class = "nbt-$news_type";
if (is_array($start_date)) {
    $article_class .= ' news-mdy';
}
?>
<div class="centerColumn <?= $article_class ?>" id="articleDefault">
    <h1 id="articleHeading"><?= $news_title ?></h1>

<?php
if (is_array($start_date)) {
    $news_date = '<span>' . $start_date[0] . '</span><span class="nb-day">' . $start_date[1] . '</span><span>' . $start_date[2] . '</span>';
} else {
    $news_date = '<div class="news-header"><strong>' . TEXT_NEWS_PUBLISHED_DATE . '</strong>&nbsp;<span>' . $start_date . ((!empty($end_date)) ? (NEWS_DATE_SEPARATOR . $end_date) : '') . '</span></div>';
}
?>
    <div class="news-dates"><?= $news_date ?></div>
    <div class="news-content"><?= $news_content ?></div>

    <div class="buttonRow back"><?= zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>' ?></div>
</div>
