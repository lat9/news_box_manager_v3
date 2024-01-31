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
$define = [
    'NEWS_BOX_MANAGER_HEADING_TITLE' => 'News Box Manager',
        'NEWS_BOX_SUBHEADING_LISTING' => '(Viewing &quot;%s&quot; News)',
        'NEWS_BOX_SUBHEADING_PREVIEW' => '(Previewing)',
        'NEWS_BOX_SUBHEADING_EDIT' => '(Editing)',
        'NEWS_BOX_SUBHEADING_NEW' => '(Creating a new article)',

    'TABLE_HEADING_NEWS_ID' => 'ID',
    'TABLE_HEADING_NEWS_TITLE' => 'News Title',
    'TABLE_HEADING_NEWS_TYPE' => 'News Type',
    'TABLE_HEADING_NEWS_START' => 'Start Date',
    'TABLE_HEADING_NEWS_END' => 'End Date',
    'TABLE_HEADING_MODIFIED' => 'Last Modified',
    'TABLE_HEADING_STATUS' => 'Status',
    'TABLE_HEADING_ACTION' => 'Action',

    'TEXT_NEWS_TITLE' => 'News Title:',
    'TEXT_NEWS_CONTENT' => 'News Content:',
    'TEXT_NEWS_METATAGS_TITLE' => 'Metatags Title:',
    'TEXT_NEWS_METATAGS_DESCRIPTION' => 'Metatags Description:',
    'TEXT_NEWS_METATAGS_KEYWORDS' => 'Metatags Keywords:',
        'TEXT_NEWS_CONTENT_HELP' => 'The <b>News Title</b> and <b>News Content</b> must be non-blank in all languages and cannot contain <code>&lt;script&gt;&lt;/script&gt;</code> tags.  HTML tags are not supported in any of the <b>Metatags</b> fields.',

    'TEXT_NEWS_SORT_ORDER' => 'News Display Order:',
        'NEWS_SORT_START_DESC' => 'Start Date (desc)',    //-Default
        'NEWS_SORT_START_ASC' => 'Start Date (asc)',
        'NEWS_SORT_ENABLED' => 'Enabled Articles First',
        'NEWS_SORT_DISABLED' => 'Disabled Articles First',

    'NEWS_BOX_NAME_ALL' => 'All',

    'TEXT_NEWS_CHOOSE_TYPE' => 'Choose the article\'s &quot;Type&quot;:',
    'TEXT_NEWS_TYPE' => 'News Type:',
    'TEXT_NEWS_STATUS' => 'Status:',
        'TEXT_ENABLED' => 'Enabled',
        'TEXT_DISABLED' => 'Disabled',
    'TEXT_NEWS_DATE_ADDED' => 'Date Added:',
    'TEXT_NEWS_DATE_MODIFIED' => 'Date Modified:',
    'TEXT_NEWS_START_DATE' => 'News Starts:',
        'TEXT_NEWS_START_DATE_HELP' => 'Enter a date in the format <span class="errorText">YYYY-MM-DD</span> or leave the date blank to default to today.  The news starts at <code>00:00:00</code> on the date you specify.',
    'TEXT_NEWS_END_DATE' => 'News Ends:',
        'TEXT_NEWS_END_DATE_HELP' => 'Enter a date in the format <span class="errorText">YYYY-MM-DD</span> or leave the date blank to indicate that the article never expires.  If a non-blank ending date is entered, the news ends at <code>23:59:59</code> on that date.',
        'TEXT_NEVER' => 'Never',

    'TEXT_NEWS_FOR_LANGUAGE' => 'News Information for %s',    //- %s is filled in with the 'name' of the associated language, e.g. 'English'.
        
    'TEXT_METATAGS_SHOW_HIDE' => 'Show/Hide Metatags',
    'TEXT_METATAGS_TITLE' => 'Metatags Title:',
    'TEXT_METATAGS_KEYWORDS' => 'Metatags Keywords:',
    'TEXT_METATAGS_DESCRIPTION' => 'Metatags Description:',
        'TEXT_NO_METATAGS' => 'Not Entered',

    'TEXT_NEWS_TYPE_NAME_UNKNOWN' => ' (Unknown)',

    'TEXT_INFO_HEADING_DELETE_NEWS' => 'Delete a News Article',
    'TEXT_NEWS_DELETE_INFO' => 'Are you sure you want to delete this news article?',
    'SUCCESS_NEWS_ARTICLE_DELETED' => 'The requested news article has been successfully deleted.',

    'TEXT_INFO_HEADING_COPY_NEWS' => 'Copy a News Article',
    'TEXT_INFO_COPY_ARTICLE' => 'Create a copy of this news article?',
    'SUCCESS_NEWS_ARTICLE_COPIED' => 'The requested news article has been successfully copied.',

    'TEXT_INFO_HEADING_MOVE_NEWS' => 'Move a News Article',
    'TEXT_INFO_MOVE_CHOOSE_TYPE' => 'Choose the <em>News Type</em> to which the article will be moved',
    'SUCCESS_NEWS_ARTICLE_MOVED' => 'The requested news article has been successfully moved.',

    'ERROR_NEWS_TITLE_CONTENT' => 'The <em>News Title</em> and <em>News Content</em> must both be non-blank for <b>all</b> languages and &lt;script&gt; tags are not allowed.',
        'ERROR_NEWS_NOT_ENABLED' => ' The news article cannot be enabled until that condition is corrected.',
    'ERROR_NEWS_START_DATE' => 'The <em>News Starts</em> date must be in the format <b>YYYY-MM-DD</b> and must be a valid date.',
    'ERROR_NEWS_END_DATE' => 'The <em>News Ends</em> date must be in the format <b>YYYY-MM-DD</b> and must be a valid date.',
    'ERROR_NEWS_DATE_ISSUES' => 'The <em>News Starts</em> date must be on or before the <em>News Ends</em> date.',
    'SUCCESS_NEWS_ARTICLE_CHANGED' => 'The news article has been successfully %s.',
        'NEWS_ARTICLE_UPDATED' => 'updated',
        'NEWS_ARTICLE_CREATED' => 'created',

    'TEXT_DISPLAY_NUMBER_OF_NEWS' => 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> news articles)',
    'TEXT_NEWS_BOX_MANAGER_INFO' => 'Use this tool to create news articles that are displayed in your store.  Refer to <em>Configuration-&gt;News Box Manager</em> for the various settings.<br /><br />A valid news article must have a non-blank &quot;News Title&quot; and &quot;News Content&quot; in <b>all</b> of your store\'s languages.',
];
return $define;
