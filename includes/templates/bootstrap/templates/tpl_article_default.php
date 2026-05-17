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
<div class="centerColumn container" id="articleDefault">
    <div class="card mb-2">
        <div class="card-header">
            <h1 id="articleHeading" class="text-center"><?= $news_title ?></h1>
        </div>
        <div class="card-body">
            <p class="news-content"><?= $news_content ?></p>
        </div>
        <div class="card-footer text-center">
<?php
if (is_array($start_date)) {
    $news_date = '<span>' . $start_date[0] . '</span><span class="nb-day">' . $start_date[1] . '</span><span>' . $start_date[2] . '</span>';
} else {
    $news_date =
        '<div class="news-header"><strong>' .
            TEXT_NEWS_PUBLISHED_DATE .
            '</strong>&nbsp;<span>' . $start_date . ((!empty($end_date)) ? (NEWS_DATE_SEPARATOR . $end_date) : '') . '</span></div>';
}
?>
            <small class="news-dates"><?= $news_date ?></small>
        </div>
    </div>

    <div class="buttonRow text-center">
        <?= zca_back_link() ?>
    </div>
</div>
