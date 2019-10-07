<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.6 and later by lat9.
// Copyright (C) 2015-2019, Vinos de Frutas Tropicales
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
<div class="centerColumn <?php echo $article_class; ?>" id="articleDefault">
    <h1 id="articleHeading"><?php echo $news_title; ?></h1>

<?php
if ($start_date === false) {
?>
    <div id="no-news"><?php echo TEXT_NEWS_ARTICLE_NOT_FOUND; ?></div>
<?php
} else {
    if (is_array($start_date)) {
        $news_date = '<span>' . $start_date[0] . '</span><span class="nb-day">' . $start_date[1] . '</span><span>' . $start_date[2] . '</span>';
    } else {
        $news_date = '<div class="news-header"><span>' . TEXT_NEWS_PUBLISHED_DATE . '</span><span>' . $start_date . ((!empty($end_date)) ? ( NEWS_DATE_SEPARATOR . $end_date) : '') . '</span></div>';
    }
?>
    <div class="news-dates"><?php echo $news_date; ?></div>
    <div class="news-content"><?php echo $news_content; ?></div>

    <div class="buttonRow back"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>
</div>
<?php
}
