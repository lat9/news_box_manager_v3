<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.1 and later by lat9.
// Copyright (C) 2015-2019, Vinos de Frutas Tropicales
//
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

// -----
// Wait for an admin to login before processing ...
//
if (empty($_SESSION['admin_id'])) {
    return;
}

define('NEWS_BOX_CURRENT_VERSION', '2.2.1');
define('NEWS_BOX_CURRENT_UPDATE_DATE', '2019-04-22');
define('NEWS_BOX_CURRENT_VERSION_DATE', NEWS_BOX_CURRENT_VERSION . ' (' . NEWS_BOX_CURRENT_UPDATE_DATE . ')');

function init_nbm_next_sort ($menu_key) 
{
    global $db;
    $next_sort = $db->Execute('SELECT MAX(sort_order) as max_sort FROM ' . TABLE_ADMIN_PAGES . " WHERE menu_key='$menu_key'", false, false, 0, true);
    return $next_sort->fields['max_sort'] + 1;
}

// -----
// Determine the configuration group associated with the News Box Manager, creating one if not present.
//
$configurationGroupTitle = 'News Box Manager';
$configuration = $db->Execute("SELECT configuration_group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = '$configurationGroupTitle' LIMIT 1");
if ($configuration->EOF) {
    $db->Execute("INSERT INTO " . TABLE_CONFIGURATION_GROUP . " 
                    (configuration_group_title, configuration_group_description, sort_order, visible) 
                  VALUES ('$configurationGroupTitle', '$configurationGroupTitle Settings', '1', '1');");
    $cgi = $db->Insert_ID(); 
    $db->Execute("UPDATE " . TABLE_CONFIGURATION_GROUP . " SET sort_order = $cgi WHERE configuration_group_id = $cgi;");
} else {
    $cgi = $configuration->fields['configuration_group_id'];
}

// ----
// Record the configuration's current version in the database.
//
if (!defined('NEWS_BOX_MODULE_VERSION')) {
    $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) VALUES ('News Box Manager Version', 'NEWS_BOX_MODULE_VERSION', '" . NEWS_BOX_CURRENT_VERSION_DATE . "', 'The News Box Manager version number and release date.', $cgi, 10, now(), 'trim(')");
   
    $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function ) VALUES ('Items to Show in Sidebox', 'NEWS_BOX_SHOW_NEWS', '5', 'Set the maximum number of the latest-news titles to show in the &quot;Latest News&quot; sidebox.', $cgi, 40, now(), NULL, NULL)");
   
    $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function ) VALUES ('Items to Show in Home Page', 'NEWS_BOX_SHOW_CENTERBOX', '0', 'Set the maximum number of the latest-news titles to show in the &quot;Latest News&quot; section at the bottom of your home page.  Set the value to 0 to disable the news display.', $cgi, 45, now(), NULL, NULL)");
   
    $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function ) VALUES ('News Archive: Items to Display', 'NEWS_BOX_SHOW_ARCHIVE', '10', 'Set the maximum number of the latest-news titles to show on the split-page view of the &quot;News Archive&quot; page.', $cgi, 47, now(), NULL, NULL)");
  
    $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function ) VALUES ('News Archive: Date Format', 'NEWS_BOX_DATE_FORMAT', 'short', 'Choose the style of dates to be displayed for an article\'s start/end dates on the &quot;News Archive&quot; page.  Choose <em>short</em> to have dates displayed similar to <b>03/02/2015</b> or <em>long</em> to display the date like <b>Monday 02 March, 2015</b>.<br /><br />The date-related settings you have made in your primary language files are honoured using the built-in functions <code>zen_date_short</code> and <code>zen_date_long</code>, respectively.', $cgi, 50, now(), NULL, 'zen_cfg_select_option(array(\'short\', \'long\'),')");
    
    $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function ) VALUES ('Home Page News Content Length', 'NEWS_BOX_CONTENT_LENGTH_CENTERBOX', '0', 'Set the maximum number of characters (an integer value) of each article\'s content to display within the home-page center-box.  Set the value to <em>0</em> to disable the content display or to <em>-1</em> to display each article\'s entire content (no HTML will be stripped).', $cgi, 46, now(), NULL, NULL)");
  
    $db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function ) VALUES ('News Archive: News Content Length', 'NEWS_BOX_CONTENT_LENGTH_ARCHIVE', '0', 'Set the maximum number of characters (an integer value) of each article\'s content to display within the &quot;News Archive&quot; page.  Set the value to <em>0</em> to disable the content display or to <em>-1</em> to display each article\'s entire content (no HTML will be stripped).', $cgi, 48, now(), NULL, NULL)");

    // ----
    // Create each of the database tables for the news-box records.
    //
    $sql = "CREATE TABLE IF NOT EXISTS " . TABLE_BOX_NEWS . " (
        `box_news_id` int(11) NOT NULL auto_increment,
        `news_added_date` datetime NOT NULL default '0001-01-01 00:00:00',
        `news_modified_date` datetime default NULL,
        `news_start_date` datetime default NULL,
        `news_end_date` datetime default NULL,
        `news_status` tinyint(1) default '0',
        `news_content_type` tinyint(1) NOT NULL default 0,
        PRIMARY KEY  (`box_news_id`)
    ) ENGINE=MyISAM";
    $db->Execute($sql);

    $sql = "CREATE TABLE IF NOT EXISTS " . TABLE_BOX_NEWS_CONTENT . " (
        `box_news_id` int(11) NOT NULL default '0',
        `languages_id` int(11) NOT NULL default '1',
        `news_title` varchar(255) NOT NULL default '',
        `news_content` text NOT NULL,
        `news_metatags_title` varchar(255) NOT NULL default '',
        `news_metatags_keywords` text,
        `news_metatags_description` text,
        PRIMARY KEY  (`languages_id`,`box_news_id`)
    ) ENGINE=MyISAM DEFAULT CHARACTER SET " . DB_CHARSET;
    $db->Execute($sql);

    // -----
    // Register the admin-level pages for use.
    //
    if (!zen_page_key_exists('toolsNewsBox')) {
        zen_register_admin_page('toolsNewsBox', 'BOX_NEWS_BOX_MANAGER', 'FILENAME_NEWS_BOX_MANAGER', '', 'tools', 'Y', init_nbm_next_sort('tools'));
    }
    if (!zen_page_key_exists('configNewsBox')) {
        zen_register_admin_page('configNewsBox', 'BOX_NEWS_BOX_MANAGER', 'FILENAME_CONFIGURATION', "gID=$cgi", 'configuration', 'Y', init_nbm_next_sort('configuration'));
    }

    define ('NEWS_BOX_MODULE_VERSION', '0.0.0');
}

// -----
// Update the configuration table to reflect the current version, if it's not already set.
//
if (NEWS_BOX_MODULE_VERSION != NEWS_BOX_CURRENT_VERSION_DATE) {
    if (NEWS_BOX_MODULE_VERSION == '0.0.0') {
        $nb_message = sprintf(NEWS_BOX_INSTALLED, NEWS_BOX_CURRENT_VERSION_DATE);
    } else {
        $version_info = trim(explode('(', NEWS_BOX_MODULE_VERSION));
        $nb_current_version = $version_info[0];
        if (version_compare($nb_current_version, '2.2.1', '<')) {
            $db->Execute(
                "ALTER TABLE " . TABLE_BOX_NEWS . "
                MODIFY `news_added_date` datetime DEFAULT '0001-01-01 00:00:00'"
            );
            $db->Execute(
                "UPDATE " . TABLE_BOX_NEWS . "
                    SET `news_added_date` = '0001-01-01 00:00:00'
                  WHERE `news_added_date` = '0000-00-00 00:00:00'"
            );
            $db->Execute(
                "UPDATE " . TABLE_BOX_NEWS . "
                    SET `news_end_date` = NULL
                  WHERE `news_end_date` = '0000-00-00 00:00:00'"
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
            // v2.2.1 moves the tool from the 'Localization' menu to the 'Tools' ...
            //
            zen_deregister_admin_pages('localizationNewsBox');
            if (!zen_page_key_exists('toolsNewsBox')) {
                zen_register_admin_page('toolsNewsBox', 'BOX_NEWS_BOX_MANAGER', 'FILENAME_NEWS_BOX_MANAGER', '', 'tools', 'Y', init_nbm_next_sort('tools'));
            }
            $nb_message = sprintf(NEWS_BOX_UPDATED, NEWS_BOX_MODULE_VERSION, NEWS_BOX_CURRENT_VERSION_DATE);
        }
    }
    $messageStack->add($nb_message, 'success');
    zen_record_admin_activity($nb_message, 'warning');
    $db->Execute("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '" . NEWS_BOX_CURRENT_VERSION_DATE . "' WHERE configuration_key = 'NEWS_BOX_MODULE_VERSION'");
}
