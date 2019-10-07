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

// -----
// Pull in the storefront's extra_definitions, contains the BOX_NEWS_NAME_TYPEx definitions.
//
$nbm_language_file = '/extra_definitions/news_box_manager_defines.php';
if (file_exists(DIR_FS_CATALOG . DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . $template_dir . $nbm_language_file)) {
    $nbm_language_file = DIR_FS_CATALOG . DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . $template_dir . $nbm_language_file;
} else {
    $nbm_language_file = DIR_FS_CATALOG . DIR_WS_LANGUAGES . $_SESSION['language'] . $nbm_language_file;
}
require $nbm_language_file; 

// -----
// Set up the "box" definitions that identify the names shown on the admin menu.  Note that
// the type-specific values need special care, since they're defined in the database (and won't
// be on an initial install or upgrade).
//
define('BOX_NEWS_BOX_MANAGER', 'News Box Manager [All Types]');
define('BOX_NEWS_BOX_MANAGER1', 'News Box Manager [' . (defined('BOX_NEWS_NAME_TYPE1') ? BOX_NEWS_NAME_TYPE1 : 'Type1') . ']');
define('BOX_NEWS_BOX_MANAGER2', 'News Box Manager [' . (defined('BOX_NEWS_NAME_TYPE2') ? BOX_NEWS_NAME_TYPE2 : 'Type2') . ']');
define('BOX_NEWS_BOX_MANAGER3', 'News Box Manager [' . (defined('BOX_NEWS_NAME_TYPE3') ? BOX_NEWS_NAME_TYPE3 : 'Type3') . ']');
define('BOX_NEWS_BOX_MANAGER4', 'News Box Manager [' . (defined('BOX_NEWS_NAME_TYPE4') ? BOX_NEWS_NAME_TYPE4 : 'Type4') . ']');

// -----
// Used by the plugin's initialization script to identify that the installation or update
// was successful.
//
define('NEWS_BOX_INSTALLED', '<em>News Box Manager</em> (%s) was successfully installed.');
define('NEWS_BOX_UPDATED', '<em>News Box Manager</em> was successfully updated from v%1$s to v%2$s.');