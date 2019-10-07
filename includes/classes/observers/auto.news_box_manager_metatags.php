<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.5 and later by lat9.
// Copyright (C) 2018, Vinos de Frutas Tropicales
//
class zcObserverNewsBoxManagerMetatags extends base 
{
    public function __construct() 
    {
        if (!empty($_GET['main_page']) && $_GET['main_page'] == FILENAME_MORE_NEWS) {
            $this->attach($this, array('NOTIFY_MODULE_META_TAGS_UNSPECIFIEDPAGE'));
        }
    }

    public function update(&$class, $eventID, $p1, &$p2, &$meta_tags_over_ride, &$metatags_title, &$metatags_description, &$metatags_keywords) 
    {
        if (isset($GLOBALS['news_box_query']) && is_object($GLOBALS['news_box_query'])) {
            $news_box_fields = $GLOBALS['news_box_query']->fields;
            if ($news_box_fields['news_metatags_title'] != '') {
                $metatags_title = zen_clean_html($news_box_fields['news_metatags_title']);
                $meta_tags_over_ride = true;
            }
            if (!empty($news_box_fields['news_metatags_keywords'])) {
                $metatags_keywords = zen_clean_html($news_box_fields['news_metatags_keywords']);
                $meta_tags_over_ride = true;
            }
            if (!empty($news_box_fields['news_metatags_description'])) {
                $news_metatags_description = $news_box_fields['news_metatags_description'];
                $news_metatags_description = zen_truncate_paragraph(strip_tags(stripslashes($news_metatags_description)), MAX_META_TAG_DESCRIPTION_LENGTH);
                $metatags_description = zen_clean_html($news_metatags_description);
                $meta_tags_over_ride = true;
            }
        }
    }
}