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

define('TABLE_HEADING_NEWS_ID', 'ID');
define('TABLE_HEADING_NEWS_TITLE', 'News Title');
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
    define('TEXT_NEWS_CONTENT_HELP', 'The <b>News Title</b> and <b>News Content</b> must be non-blank in all languages and cannot contain <code>&lt;script&gt;&lt;/script&gt;</code> tags.  HTML tags are not supported in any of the <b>Metatags</b> fields.');
    
define('NEWS_BOX_NAME_ALL', 'All');

define('TEXT_NEWS_CHOOSE_TYPE', 'Choose the article\'s &quot;Type&quot;:');
define('TEXT_NEWS_TYPE', 'News Type:');
define('TEXT_NEWS_STATUS', 'Status:');
    define('TEXT_ENABLED', 'Enabled');
    define('TEXT_DISABLED', 'Disabled');
define('TEXT_NEWS_DATE_ADDED', 'Date Added:');
define('TEXT_NEWS_DATE_MODIFIED', 'Date Modified:');
define('TEXT_NEWS_START_DATE', 'News Starts:');
    define('TEXT_NEWS_START_DATE_HELP', 'Enter a date in the format <span class="errorText">YYYY-MM-DD</span> or leave the date blank to default to today.  The news starts at <code>00:00:00</code> on the date you specify.');
define('TEXT_NEWS_END_DATE', 'News Ends:');
    define('TEXT_NEWS_END_DATE_HELP', 'Enter a date in the format <span class="errorText">YYYY-MM-DD</span> or leave the date blank to indicate that the article never expires.  If a non-blank ending date is entered, the news ends at <code>23:59:59</code> on that date.');
    define('TEXT_NEVER', 'Never');
    
define('TEXT_NEWS_FOR_LANGUAGE', 'News Information for %s');    //- %s is filled in with the 'name' of the associated language, e.g. 'English'.
    
define('TEXT_METATAGS_SHOW_HIDE', 'Show/Hide Metatags');
define('TEXT_METATAGS_TITLE', 'Metatags Title:');
define('TEXT_METATAGS_KEYWORDS', 'Metatags Keywords:');
define('TEXT_METATAGS_DESCRIPTION', 'Metatags Description:');
    define('TEXT_NO_METATAGS', 'Not Entered');

define('TEXT_NEWS_TYPE_NAME_UNKNOWN', ' (Unknown)');

define('TEXT_INFO_HEADING_DELETE_NEWS', 'Delete a News Article');
define('TEXT_NEWS_DELETE_INFO', 'Are you sure you want to delete this news article?');
define('SUCCESS_NEWS_ARTICLE_DELETED', 'The requested news article has been successfully deleted.');

define('TEXT_INFO_HEADING_COPY_NEWS', 'Copy a News Article');
define('TEXT_INFO_COPY_ARTICLE', 'Create a copy of this news article?');
define('SUCCESS_NEWS_ARTICLE_COPIED', 'The requested news article has been successfully copied.');

define('TEXT_INFO_HEADING_MOVE_NEWS', 'Move a News Article');
define('TEXT_INFO_MOVE_CHOOSE_TYPE', 'Choose the <em>News Type</em> to which the article will be moved');
define('SUCCESS_NEWS_ARTICLE_MOVED', 'The requested news article has been successfully moved.');

define('ERROR_NEWS_TITLE_CONTENT', 'The <em>News Title</em> and <em>News Content</em> must both be non-blank for <b>all</b> languages and &lt;script&gt; tags are not allowed.');
    define('ERROR_NEWS_NOT_ENABLED', ' The news article cannot be enabled until that condition is corrected.');
define('ERROR_NEWS_START_DATE', 'The <em>News Starts</em> date must be in the format <b>YYYY-MM-DD</b> and must be a valid date.');
define('ERROR_NEWS_END_DATE', 'The <em>News Ends</em> date must be in the format <b>YYYY-MM-DD</b> and must be a valid date.');
define('ERROR_NEWS_DATE_ISSUES', 'The <em>News Starts</em> date must be on or before the <em>News Ends</em> date.');
define('SUCCESS_NEWS_ARTICLE_CHANGED', 'The news article has been successfully %s.');
    define('NEWS_ARTICLE_UPDATED', 'updated');
    define('NEWS_ARTICLE_CREATED', 'created');

define('TEXT_DISPLAY_NUMBER_OF_NEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> news)');
define('TEXT_NEWS_BOX_MANAGER_INFO', 'Use this tool to create news articles that are displayed in your store.  Refer to <em>Configuration-&gt;News Box Manager</em> for the various settings.<br /><br />A valid news article must have a non-blank &quot;News Title&quot; and &quot;News Content&quot; in <b>all</b> of your store\'s languages.');
