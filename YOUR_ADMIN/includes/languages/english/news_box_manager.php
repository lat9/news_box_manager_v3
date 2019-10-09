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
define('NEWS_BOX_HEADING_TITLE', 'News Box Manager');
    define('NEWS_BOX_SUBHEADING_LISTING', '(Viewing &quot;%s&quot; News)');
    define('NEWS_BOX_SUBHEADING_PREVIEW', '(Previewing)');
    define('NEWS_BOX_SUBHEADING_EDIT', '(Editing)');
    define('NEWS_BOX_SUBHEADING_NEW', '(Creating a new article)');

define('TABLE_HEADING_NEWS', 'News');
define('TABLE_HEADING_NEWS_TYPE', 'News Type');
define('TABLE_HEADING_NEWS_START', 'Start Date');
define('TABLE_HEADING_NEWS_END', 'End Date');
define('TABLE_HEADING_MODIFIED', 'Last Modified');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_NEWS_TITLE', 'News Title:');
define('TEXT_NEWS_CONTENT', 'News Content:');
define('TEXT_NEWS_METATAGS_TITLE', 'Metatags Title:');
define('TEXT_NEWS_METATAGS_DESCRIPTION', 'Metatags Description:');
define('TEXT_NEWS_METATAGS_KEYWORDS', 'Metatags Keywords:');
define('NEWS_BOX_NAME_ALL', 'All');

define('TEXT_NEWS_CHOOSE_TYPE', 'Choose the article\'s &quot;Type&quot;:');
define('TEXT_NEWS_TYPE', 'News Type:');
define('TEXT_NEWS_STATUS', 'Status:');
    define('TEXT_ENABLED', 'Enabled');
    define('TEXT_DISABLED', 'Disabled');
define('TEXT_NEWS_DATE_ADDED', 'Date Added:');
define('TEXT_NEWS_DATE_MODIFIED', 'Date Modified:');
define('TEXT_NEWS_START_DATE', 'News Starts:');
define('TEXT_NEWS_END_DATE', 'News Ends:');
    define('TEXT_NEVER', 'Never');

define('TEXT_NEWS_TYPE_NAME_UNKNOWN', ' (Unknown)');

define('TEXT_INFO_HEADING_DELETE_NEWS', 'Delete a News Article');
define('TEXT_NEWS_DELETE_INFO', 'Are you sure you want to delete this news article?');

define('ERROR_NEWS_TITLE_CONTENT', 'The <em>News Title</em> and <em>News Content</em> must both be non-blank for at least one language');
define('ERROR_NEWS_DATE_ISSUES', 'The <em>Start Date</em> must be on or before the <em>End Date</em>.');
define('SUCCESS_NEWS_ARTICLE_CHANGED', 'The news article has been %s.');
    define('NEWS_ARTICLE_UPDATED', 'updated');
    define('NEWS_ARTICLE_CREATED', 'created');

define('TEXT_DISPLAY_NUMBER_OF_NEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> news)');
define('TEXT_NEWS_BOX_MANAGER_INFO', 'Use this tool to create news articles that are displayed in your store.  Refer to the settings in <em>Configuration-&gt;News Box Manager</em> for the various settings.<br /><br />A valid news article must have a non-blank &quot;News Title&quot; and &quot;News Content&quot; in at least one of your store\'s languages.');
define('TEXT_EDIT_INSERT_INFO', 'If you leave the <em>Start Date</em> blank, its value will default to today.  Leave the <em>End Date</em> blank for a news article that never expires.');
