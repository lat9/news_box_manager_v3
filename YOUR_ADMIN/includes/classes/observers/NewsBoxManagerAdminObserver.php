<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.6 and later by lat9.
// Copyright (C) 2019-2024, Vinos de Frutas Tropicales
//  
if (!defined('IS_ADMIN_FLAG') || IS_ADMIN_FLAG !== true) {
    die('Illegal Access');
}

class NewsBoxManagerAdminObserver extends base
{
    public function __construct() {
        if (!defined('NEWS_BOX_MODULE_VERSION')) {
            return;
        }
        $this->attach (
            $this,
            [
                /* Issued by /admin/languages.php */
                'NOTIFY_ADMIN_LANGUAGE_INSERT', 
                'NOTIFY_ADMIN_LANGUAGE_DELETE',
            ]
        );
    }

    public function update(&$class, $eventID, $p1, &$p2, &$p3) {
        global $db;

        switch ($eventID) {
            // -----
            // If a language is added, copy any news title/content/metatags to the new language from the
            // active session's language entries.
            //
            // On entry:
            //
            // $p1 ... (r/o) The newly-created language_id.
            //
            case 'NOTIFY_ADMIN_LANGUAGE_INSERT':
                $language_to_add = (int)$p1;
                if ($language_to_add < 1) {
                    return;
                }
                $news = $db->Execute(
                    "SELECT *
                       FROM " . TABLE_BOX_NEWS_CONTENT . "
                      WHERE languages_id = " . (int)$_SESSION['languages_id']
                );
                foreach ($news as $item) {
                    $news_title = zen_db_input($item['news_title']);
                    $news_content = zen_db_input($item['news_content']);
                    $news_metatags_title = zen_db_input($item['news_metatags_title']);
                    $news_metatags_keywords = zen_db_input($item['news_metatags_keywords']);
                    $news_metatags_description = zen_db_input($item['news_metatags_description']);
                    $db->Execute(
                        "INSERT INTO " . TABLE_BOX_NEWS_CONTENT . "
                            (box_news_id, languages_id, news_title, news_content, news_metatags_title, news_metatags_keywords, news_metatags_description)
                         VALUES
                            ({$item['box_news_id']}, $language_to_add, '$news_title', '$news_content', '$news_metatags_title', '$news_metatags_keywords', '$news_metatags_description')"
                    );
                }
                break;

            // -----
            // If a language is removed, remove any news title/content/metatags for that language from the database.
            //
            // On entry:
            //
            // $p1 ... (r/o) The to-be-removed language_id.
            //
            case 'NOTIFY_ADMIN_LANGUAGE_DELETE':
                $language_to_remove = (int)$p1;
                $db->Execute(
                    "DELETE FROM " . TABLE_BOX_NEWS_CONTENT . "
                      WHERE languages_id = $language_to_remove"
                );
                break;

            // -----
            // Anything else ...
            //
            default:
                break;
        }
    }
}
