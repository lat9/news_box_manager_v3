<?php
// -----
// Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.6 and later by lat9.
// Copyright (C) 2015-2019, Vinos de Frutas Tropicales
//
if (!defined('IS_ADMIN_FLAG') || IS_ADMIN_FLAG !== true) {
    die('Illegal Access');
}

// -----
// This module, required by the plugin's overall initialization on an initial install only,
// sets the "legacy" configuration into the database.  Note that the news_box_manager_update.php
// module might make follow-on modifications.
//
// Basic sort-order organization:
//
// 0-29 .... Basic, overall settings
// 30-49 ... Maximum values and lengths for the storefront displays
// 50-69 ... Common configuration settings
// 70-109 .. Category-specific news settings
//
$db->Execute(
    "INSERT INTO " . TABLE_CONFIGURATION . " 
        (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, set_function) 
     VALUES 
        ('News Box Manager Version', 'NEWS_BOX_MODULE_VERSION', '" . NEWS_BOX_CURRENT_VERSION_DATE . "', 'The News Box Manager version number and release date.', $cgi, 10, now(), 'trim('),
        
        ('Items to Show in Sidebox', 'NEWS_BOX_SHOW_NEWS', '5', 'Set the maximum number of the latest-news titles to show in the &quot;Latest News&quot; sidebox.', $cgi, 40, now(), NULL, NULL),

        ('Items to Show in Home Page', 'NEWS_BOX_SHOW_CENTERBOX', '0', 'Set the maximum number of the latest-news titles to show in the &quot;Latest News&quot; section at the bottom of your home page.  Set the value to 0 to disable the news display.', $cgi, 45, now(), NULL, NULL),

        ('News Archive: Items to Display', 'NEWS_BOX_SHOW_ARCHIVE', '10', 'Set the maximum number of the latest-news titles to show on the split-page view of the &quot;News Archive&quot; page.', $cgi, 47, now(), NULL, NULL),

        ('News Archive: Date Format', 'NEWS_BOX_DATE_FORMAT', 'short', 'Choose the style of dates to be displayed for an article\'s start/end dates on the &quot;News Archive&quot; page.  Choose <em>short</em> to have dates displayed similar to <b>03/02/2015</b> or <em>long</em> to display the date like <b>Monday 02 March, 2015</b>.<br /><br />The date-related settings you have made in your primary language files are honoured using the built-in functions <code>zen_date_short</code> and <code>zen_date_long</code>, respectively.', $cgi, 50, now(), NULL, 'zen_cfg_select_option(array(\'short\', \'long\'),'),

        ('Home Page News Content Length', 'NEWS_BOX_CONTENT_LENGTH_CENTERBOX', '0', 'Set the maximum number of characters (an integer value) of each article\'s content to display within the home-page center-box.  Set the value to <em>0</em> to disable the content display or to <em>-1</em> to display each article\'s entire content (no HTML will be stripped).', $cgi, 46, now(), NULL, NULL),

        ('News Archive: News Content Length', 'NEWS_BOX_CONTENT_LENGTH_ARCHIVE', '0', 'Set the maximum number of characters (an integer value) of each article\'s content to display within the &quot;News Archive&quot; page.  Set the value to <em>0</em> to disable the content display or to <em>-1</em> to display each article\'s entire content (no HTML will be stripped).', $cgi, 48, now(), NULL, NULL)"
);

// ----
// Create each of the database tables for the news-box records.
//
$sql = "CREATE TABLE IF NOT EXISTS " . TABLE_BOX_NEWS . " (
    `box_news_id` int(11) NOT NULL auto_increment,
    `news_added_date` datetime NOT NULL default '0001-01-01 00:00:00',
    `news_modified_date` datetime default NULL,
    `news_start_date` datetime default NULL,
    `news_end_date` datetime default NULL,
    `news_status` tinyint(1) default 0,
    `news_content_type` tinyint(1) NOT NULL default 1,
    PRIMARY KEY  (`box_news_id`)
)";
$db->Execute($sql);

$sql = "CREATE TABLE IF NOT EXISTS " . TABLE_BOX_NEWS_CONTENT . " (
    `box_news_id` int(11) NOT NULL default 0,
    `languages_id` int(11) NOT NULL default 1,
    `news_title` varchar(255) NOT NULL default '',
    `news_content` text NOT NULL,
    `news_metatags_title` varchar(255) NOT NULL default '',
    `news_metatags_keywords` text,
    `news_metatags_description` text,
    PRIMARY KEY (`languages_id`,`box_news_id`)
)";
$db->Execute($sql);

// -----
// Register the admin-level pages for use.
//
if (!zen_page_key_exists('toolsNewsBox')) {
    zen_register_admin_page('toolsNewsBox', 'BOX_NEWS_BOX_MANAGER', 'FILENAME_NEWS_BOX_MANAGER', 'nType=0', 'tools', 'Y');
}
if (!zen_page_key_exists('configNewsBox')) {
    zen_register_admin_page('configNewsBox', 'BOX_NEWS_BOX_MANAGER', 'FILENAME_CONFIGURATION', "gID=$cgi", 'configuration', 'Y');
}

define('NEWS_BOX_MODULE_VERSION', '0.0.0');
