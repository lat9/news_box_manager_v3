<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.8a and later by lat9.
// Copyright (C) 2015-2024, Vinos de Frutas Tropicales
//
if (!defined('IS_ADMIN_FLAG') || IS_ADMIN_FLAG !== true) {
    die('Illegal Access');
}

// -----
// This module is required by the plugin's main installation handler when it detects
// a change in the current vs. installed version of the plugin.  Note that if the
// version is currently '0.0.0', it's an initial installation.
//
if (NEWS_BOX_MODULE_VERSION === '0.0.0') {
    $nb_message = sprintf(NEWS_BOX_INSTALLED, NEWS_BOX_CURRENT_VERSION_DATE);
    $nb_current_version = '0.0.0';
} else {
    $nb_message = sprintf(NEWS_BOX_UPDATED, NEWS_BOX_MODULE_VERSION, NEWS_BOX_CURRENT_VERSION_DATE);
    $version_info = explode('(', NEWS_BOX_MODULE_VERSION);
    $nb_current_version = trim($version_info[0]);
}

if (version_compare($nb_current_version, '2.2.0', '<')) {
    $db->Execute(
        "ALTER TABLE " . TABLE_BOX_NEWS . "
            MODIFY `news_added_date` datetime DEFAULT '0001-01-01 00:00:00'"
    );
    $db->Execute(
        "UPDATE " . TABLE_BOX_NEWS . "
            SET `news_added_date` = '0001-01-01 00:00:00'
          WHERE CAST(`news_added_date` AS CHAR(20)) = '0000-00-00 00:00:00'"
    );
    $db->Execute(
        "UPDATE " . TABLE_BOX_NEWS . "
            SET `news_end_date` = NULL
          WHERE CAST(`news_end_date` AS CHAR(20)) = '0000-00-00 00:00:00'"
    );
    if (!$sniffer->field_exists(TABLE_BOX_NEWS, 'news_content_type')) {
        $db->Execute(
            "ALTER TABLE " . TABLE_BOX_NEWS . "
               ADD COLUMN `news_content_type` tinyint(1) NOT NULL default 0"
        );
    }
    if (!$sniffer->field_exists(TABLE_BOX_NEWS_CONTENT, 'news_metatags_title')) {
        $db->Execute(
            "ALTER TABLE " . TABLE_BOX_NEWS_CONTENT . "
               ADD COLUMN `news_metatags_title` varchar(255) NOT NULL default '',
               ADD COLUMN `news_metatags_keywords` text,
               ADD COLUMN `news_metatags_description` text"
        );
    }
    // -----
    // v2.1.1 moves the tool from the 'Localization' menu to the 'Tools' ...
    //
    zen_deregister_admin_pages('localizationNewsBox');
    if (!zen_page_key_exists('toolsNewsBox')) {
        zen_register_admin_page('toolsNewsBox', 'BOX_NEWS_BOX_MANAGER', 'FILENAME_NEWS_BOX_MANAGER', '', 'tools', 'Y');
    }
}

// -----
// Version 3.0.0 introduces the concept of multiple "types" of news.  Admins' authorization
// can be limited to specific types of news categories.
//
// This version also re-aligns the various settings' sort-orders for a semblance of
// future-proofing:
//
// 0-99 ........ Basic, overall settings
// 100-199 ..... Settings applicable to Sidebox #1 (news_box_sidebox)
// 200-299 ..... Settings applicable to Sidebox #2 (news_box_sidebox2)
// 300-399 ..... Home-page Centerbox settings
// 400-499 ..... Settings for the "all_articles" pages
// 1000-1099 ... Settings applicable to news_content_type 1
// 1100-1199 ... Settings applicable to news_content_type 2
// 1200-1299 ... Settings applicable to news_content_type 3
// 1300-1399 ... Settings applicable to news_content_type 4
//
if (version_compare($nb_current_version, '3.0.0', '<')) {
    // -----
    // Register additional, category-specific pages to access.
    //
    if (!zen_page_key_exists('toolsNewsBox1')) {
        zen_register_admin_page('toolsNewsBox1', 'BOX_NEWS_BOX_MANAGER1', 'FILENAME_NEWS_BOX_MANAGER1', '', 'tools', 'Y');
    }
    if (!zen_page_key_exists('toolsNewsBox2')) {
        zen_register_admin_page('toolsNewsBox2', 'BOX_NEWS_BOX_MANAGER2', 'FILENAME_NEWS_BOX_MANAGER2', '', 'tools', 'Y');
    }
    if (!zen_page_key_exists('toolsNewsBox3')) {
        zen_register_admin_page('toolsNewsBox3', 'BOX_NEWS_BOX_MANAGER3', 'FILENAME_NEWS_BOX_MANAGER3', '', 'tools', 'Y');
    }
    if (!zen_page_key_exists('toolsNewsBox4')) {
        zen_register_admin_page('toolsNewsBox4', 'BOX_NEWS_BOX_MANAGER4', 'FILENAME_NEWS_BOX_MANAGER4', '', 'tools', 'Y');
    }

    // -----
    // The 'names' of each of the news' categories were (for v3.0.0-beta1) recorded in the database; they're now in the
    // storefront 'extra_definitions' file, enabling language customization.  Remove them from the database.
    //
    $db->Execute(
        "DELETE FROM " . TABLE_CONFIGURATION . "
          WHERE configuration_key LIKE 'BOX_NEWS_NAME_TYPE%'"
    );

    // -----
    // Sidebox configuration changes, as there are now two separately-placed sideboxes.
    //
    $db->Execute(
        "INSERT IGNORE INTO " . TABLE_CONFIGURATION . "
            (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function )
         VALUES
            ('Sideboxes: Date Format', 'NEWS_BOX_SIDEBOX_DATE_FORMAT', 'short', 'Choose the style of dates to be displayed for an article\'s start/end dates when displayed in one of the plugin\'s sideboxes.  Choose <em>short</em> to have dates displayed similar to <b>03/02/2015</b> or <em>long</em> to display the date like <b>Monday 02 March, 2015</b>.<br><br>The date-related settings you have made in your primary language files are honoured using the built-in functions <code>zen_date_short</code> and <code>zen_date_long</code>, respectively.', $cgi, 50, now(), NULL, 'zen_cfg_select_option([\'short\', \'long\'],')"
    );
    $db->Execute(
        "UPDATE " . TABLE_CONFIGURATION . "
            SET configuration_title = 'Sidebox #1: Items to Show',
                configuration_description = 'Set the maximum number of the latest-news titles to show in sidebox #1. If the value is set to <b>0</b>, the sidebox display is disabled.',
                configuration_key = 'NEWS_BOX_SHOW_NEWS_SB1'
          WHERE configuration_key = 'NEWS_BOX_SHOW_NEWS'
          LIMIT 1"
    );
    $db->Execute(
        "INSERT IGNORE INTO " . TABLE_CONFIGURATION . "
            (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function)
         VALUES
            ('Sidebox #1: Category to Show', 'NEWS_BOX_SHOW_NEWS_CAT_SB1', 'All', 'Choose the single category (or all news categories) to display for sidebox #1.', $cgi, 41, now(), NULL, 'zen_cfg_select_option([\'All\', \'1\', \'2\', \'3\', \'4\'],')"
    );
    $db->Execute(
        "INSERT IGNORE INTO " . TABLE_CONFIGURATION . "
            (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function)
         VALUES
            ('Sidebox #1: Layout', 'NEWS_BOX_LAYOUT_SB1', 'List', 'Choose the formatting layout for <code>news_box_sidebox1</code>, one of:<ol><li><b>List</b>: The titles of the selected articles are displayed in a list-type format.</li><li><b>Grid, Title/Date</b>: The title and date(s) for the selected articles are displayed in a grid format.</li><li><b>Grid, Title/Date/Content</b>: The title, date(s) and a portion of the content are displayed in a grid format.</li></ol>', $cgi, 42, now(), NULL, 'zen_cfg_select_option([\'List\', \'GridTitleDate\', \'GridTitleDateDesc\'],')"
    );
    $db->Execute(
        "INSERT IGNORE INTO " . TABLE_CONFIGURATION . "
            (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function)
         VALUES
            ('Sidebox #2: Items to Show', 'NEWS_BOX_SHOW_NEWS_SB2', '0', 'Set the maximum number of the latest-news titles to show in sidebox #2.  If the value is set to <b>0</b>, the sidebox display is disabled.', $cgi, 43, now(), NULL, NULL)"
    );
    $db->Execute(
        "INSERT IGNORE INTO " . TABLE_CONFIGURATION . "
            (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function)
         VALUES
            ('Sidebox #2: Category to Show', 'NEWS_BOX_SHOW_NEWS_CAT_SB2', 'All', 'Choose the single category (or all news categories) to display for sidebox #2.', $cgi, 44, now(), NULL, 'zen_cfg_select_option([\'All\', \'1\', \'2\', \'3\', \'4\'],')"
    );
    $db->Execute(
        "INSERT IGNORE INTO " . TABLE_CONFIGURATION . "
            (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function)
         VALUES
            ('Sidebox #2: Layout', 'NEWS_BOX_LAYOUT_SB2', 'List', 'Choose the formatting layout for <code>news_box_sidebox2</code>, one of:<ol><li><b>List</b>: The titles of the selected articles are displayed in a list-type format.</li><li><b>Grid, Title/Date</b>: The title and date(s) for the selected articles are displayed in a grid format.</li><li><b>Grid, Title/Date/Content</b>: The title, date(s) and a portion of the content are displayed in a grid format.</li></ol>', $cgi, 45, now(), NULL, 'zen_cfg_select_option([\'List\', \'GridTitleDate\', \'GridTitleDateDesc\'],')"
    );
    
    // -----
    // Renaming of and additions to the Home Page and "News Archive" settings, now the "All Articles" page.
    //
    $db->Execute(
        "UPDATE " . TABLE_CONFIGURATION . "
            SET configuration_title = 'Home Page: Number of Items',
                configuration_description = 'Set the maximum number of the latest-news titles to show in the news center-box at the bottom of your home page. Set the value to 0 to disable the home-page news display.'
          WHERE configuration_key = 'NEWS_BOX_SHOW_CENTERBOX'
          LIMIT 1"
    );
    $db->Execute(
        "UPDATE " . TABLE_CONFIGURATION . "
            SET configuration_title = 'Home Page: Content Length'
          WHERE configuration_key = 'NEWS_BOX_CONTENT_LENGTH_CENTERBOX'
          LIMIT 1"
    );

    $db->Execute(
        "INSERT IGNORE INTO " . TABLE_CONFIGURATION . "
            (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function)
         VALUES
            ('Home Page: Display Mode', 'NEWS_BOX_HOMEPAGE_DISPLAY', 'Individual', 'Choose the format used to display the home-page news.  The <em>Individual</em> format displays individual articles in a table-type format.  The <em>Categories</em> format displays the most recent article from each news category, with a link to view all articles in that category.', $cgi, 52, now(), NULL, 'zen_cfg_select_option([\'Individual\', \'Categories\'],')"
    );
    $db->Execute(
        "UPDATE " . TABLE_CONFIGURATION . "
            SET configuration_title = 'All Articles: Items per Page',
                configuration_description = 'Set the maximum number of the latest-news titles to show on the split-page view of the &quot;All Articles&quot; pages.'
          WHERE configuration_key = 'NEWS_BOX_SHOW_ARCHIVE'
          LIMIT 1"
    );
    $db->Execute(
        "UPDATE " . TABLE_CONFIGURATION . "
            SET configuration_title = 'All Articles: Content Length',
                configuration_description = 'Set the maximum number of characters (an integer value) of each article\'s content to display within the &quot;All Articles&quot; pages.  Set the value to <em>0</em> to disable the content display or to <em>-1</em> to display each article\'s entire content (no HTML will be stripped).'
          WHERE configuration_key = 'NEWS_BOX_CONTENT_LENGTH_ARCHIVE'
          LIMIT 1"
    );
    $db->Execute(
        "UPDATE " . TABLE_CONFIGURATION . "
            SET configuration_title = 'Article Display: Date Format',
                configuration_description = 'Choose the style of dates to be displayed for an article\'s start/end dates on the <em>All Articles</em> and <em>Article</em> pages:<ul><li><em>short</em>: Display dates similar to <b>03/02/2015</b>, using the built-in <code>zen_date_short</code>function.</li><li><em>long</em>: Display dates similar to <b>Monday 02 March, 2015</b>, using the built-in <code>zen_date_long</code> function.</li><li><em>MdY</em>: Applies to single-date articles, displays the date similar to <b>Mar 02 2015</b>.  Defaults to the <em>short</em> format if an article\'s ending-date is specified.</li></ul>',
                set_function = 'zen_cfg_select_option([\'MdY\', \'short\', \'long\'],'
          WHERE configuration_key = 'NEWS_BOX_DATE_FORMAT'
          LIMIT 1"
    );
    $db->Execute(
        "INSERT IGNORE INTO " . TABLE_CONFIGURATION . "
            (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function)
         VALUES
            ('All Articles: Display Mode', 'NEWS_BOX_ALL_ARTICLES_DISPLAY', 'Table', 'Choose the format used to display the articles on the <code>all_articles</code> page.  The <em>Table</em> format displays the most recent articles in a table-type format.  The <em>Listing</em> format displays the most recent articles in a listing format.', $cgi, 405, now(), NULL, 'zen_cfg_select_option([\'Table\', \'Listing\'],')"
    );
    $db->Execute(
        "DELETE FROM " . TABLE_CONFIGURATION . "
          WHERE configuration_key = 'NEWS_BOX_CONTENT_LENGTH'
          LIMIT 1"
    );

    // -----
    // Rearranging all configuration settings' sort-orders.
    //
    $keys_sort_orders = [
        'NEWS_BOX_SHOW_NEWS_SB1' => 100,
        'NEWS_BOX_SHOW_NEWS_CAT_SB1' => 110,
        'NEWS_BOX_LAYOUT_SB1' => 120,
        'NEWS_BOX_SHOW_NEWS_SB2' => 200,
        'NEWS_BOX_SHOW_NEWS_CAT_SB2' => 210,
        'NEWS_BOX_LAYOUT_SB2' => 220,
        'NEWS_BOX_SHOW_CENTERBOX' => 300,
        'NEWS_BOX_CONTENT_LENGTH_CENTERBOX' => 310,
        'NEWS_BOX_HOMEPAGE_DISPLAY' => 320,
        'NEWS_BOX_SHOW_ARCHIVE' => 400,
        'NEWS_BOX_CONTENT_LENGTH_ARCHIVE' => 410,
        'NEWS_BOX_DATE_FORMAT' => 420,
    ];
    foreach ($keys_sort_orders as $key => $sort_order) {
        $db->Execute(
            "UPDATE " . TABLE_CONFIGURATION . "
                SET sort_order = $sort_order
              WHERE configuration_key = '$key'
              LIMIT 1"
        );
    }

    // -----
    // Prior versions of the News Box Manager set numeric-character defaults for its added
    // database tables; clean those up.
    //
    $db->Execute(
        "ALTER TABLE " . TABLE_BOX_NEWS . "
            MODIFY `news_status` tinyint(1) default 0"
    );
    $db->Execute(
        "ALTER TABLE " . TABLE_BOX_NEWS_CONTENT . "
            MODIFY `box_news_id` int(11) NOT NULL default 0,
            MODIFY `languages_id` int(11) NOT NULL default 1"
    );

    // -----
    // "Reset" any previously-recorded content-type '0' values to be news-content type 1.
    //
    $db->Execute(
        "UPDATE " . TABLE_BOX_NEWS . "
            SET news_content_type = 1
          WHERE news_content_type = 0"
    );

    // -----
    // Now, the BIG change.  Previous versions of the 'News Box Manager' allowed a multi-language
    // store to record news articles with empty titles and/or content so long as there was one language
    // that *had* content.  v3.0.0 now requires that the titles and content be recorded for **all**
    // languages.
    //
    // If a news article is found to have a blank title or content in *any* of the store's defined
    // languages, that article is disabled.  A log file is generated to identify which articles have
    // been disabled.
    //
    $nbm_fixups = $db->Execute(
        "SELECT nbc.*
           FROM " . TABLE_BOX_NEWS . " nb
                INNER JOIN " . TABLE_BOX_NEWS_CONTENT . " nbc
                    ON nbc.box_news_id = nb.box_news_id
          WHERE nb.news_status = 1
          ORDER BY nbc.box_news_id ASC"
    );
    $news_disabled = [];
    $nbm_logname = DIR_FS_LOGS . '/news_box_manager_articles_disabled.log';
    foreach ($nbm_fixups as $next_check) {
        if (empty(trim($next_check['news_title'])) || empty(trim($next_check['news_content']))) {
            if (!in_array($next_check['box_news_id'], $news_disabled)) {
                $news_disabled[] = $next_check['box_news_id'];
                $db->Execute(
                    "UPDATE " . TABLE_BOX_NEWS . "
                        SET news_status = 0
                      WHERE box_news_id = {$next_check['box_news_id']}
                      LIMIT 1"
                );
                error_log(date('Y-m-d H:i:s: ') . 'News article #' . $next_check['box_news_id'] . ' has been disabled, due to missing content.' . PHP_EOL, 3, $nbm_logname);
            }
        }
    }
    if (count($news_disabled) !== 0) {
        $messageStack->add_session(sprintf(NEWS_BOX_ARTICLES_DISABLED, $nbm_logname), 'warning');
    }
    unset($nbm_fixups, $next_check);
}

// -----
// v3.2.0 changes the 'set_function' for the plugin's saved version to
// use zen_cfg_read_only (so it doesn't get inadvertantly wiped).
//
if (version_compare($nb_current_version, '3.2.0', '<')) {
    $db->Execute(
        "UPDATE " . TABLE_CONFIGURATION . "
            SET set_function = 'zen_cfg_read_only('
          WHERE configuration_key = 'NEWS_BOX_MODULE_VERSION'
          LIMIT 1"
    );
}

$messageStack->add_session($nb_message, 'success');
zen_record_admin_activity($nb_message, 'warning');
$db->Execute(
    "UPDATE " . TABLE_CONFIGURATION . "
        SET configuration_value = '" . NEWS_BOX_CURRENT_VERSION_DATE . "',
            last_modified = now()
      WHERE configuration_key = 'NEWS_BOX_MODULE_VERSION'
      LIMIT 1"
);
