# Additional News Box Categories

***News Box Manager*** provides up to four (4) different categories (or types) of news.  This article identifies how you can increase that number.

First, determine the 'count' of different types of news that you want; I'll use an example of 8.

1. Edit `/includes/languages/english/extra_definitions[/YOUR_TEMPLATE]/news_box_manager_defines.php`.

   1. Find the following section:

      - ```
         define('BOX_NEWS_NAME_TYPE1', 'Type1');
         define('BOX_NEWS_NAME_TYPE2', 'Type2');
         define('BOX_NEWS_NAME_TYPE3', 'Type3');
         define('BOX_NEWS_NAME_TYPE4', 'Type4');
         ```
      
   2. Add the names of the additional types (5 through 8):
   
      - ```
        define('BOX_NEWS_NAME_TYPE1', 'Type1');
        define('BOX_NEWS_NAME_TYPE2', 'Type2');
        define('BOX_NEWS_NAME_TYPE3', 'Type3');
        define('BOX_NEWS_NAME_TYPE4', 'Type4');
        define('BOX_NEWS_NAME_TYPE1', 'Type5');
        define('BOX_NEWS_NAME_TYPE2', 'Type6');
        define('BOX_NEWS_NAME_TYPE3', 'Type7');
        define('BOX_NEWS_NAME_TYPE4', 'Type8');
        ```
   
2. "Clone" additional admin tools.  Copy `/YOUR_ADMIN/news_box_manager4.php` to `/YOUR_ADMIN/news_box_manager5.php`, `/YOUR_ADMIN/news_box_manager6.php`, `/YOUR_ADMIN/news_box_manager7.php` and `/YOUR_ADMIN/news_box_manager8.php`. and edit each of those files to contain its unique article type.  Here's the code for `news_box_manager4.php` for reference.

   - ```<?php
     // -----
     // Part of the News Box Manager plugin, re-structured for Zen Cart v1.5.6 and later by lat9.
     // Copyright (C) 2018-2019, Vinos de Frutas Tropicales
     //
     // -----
     // Starting with v3.0.0 of the plugin, a store-owner can define individual news "types" and
     // restrict their use to specific admin-profiles.  This "clone" handles news-box Type4, as
     // defined by the store itself.
     //
     $_GET['nType'] = 4;  //<--- Change this
     require 'news_box_manager.php';
   
3. Edit `/YOUR_ADMIN/news_box_manager.php` to recognize the additional news-box types.

   1. Find the following code snippet (towards the top of the file) and change the **4** to an **8**:

      ```
          $news_box_type = (int)str_replace(
              array(
                  FILENAME_NEWS_BOX_MANAGER,
                  '.php'
              ),
              '',
              $news_box_script_name
          );
          if ($news_box_type < 1 || $news_box_type > 4) {
              exit('Invalid Access.');
          }
      ```

   2. Find the following code snippet and change the **4** to an **8**:

      ```
          // -----
          // Move Confirmation: Issued from the 'Move' sidebox.  Moves the specified news article to a
          // different 'Type'.
          //
          case 'moveconfirm':
              $link_parms = $page_get_params;
              if (isset($_POST['nID']) && isset($_POST['news_content_type'])) {
                  $nID = (int)$_POST['nID'];
                  $news_content_type = (int)$_POST['news_content_type'];
                  if ($news_content_type >= 1 && $news_content_type <= 4) {
                      $db->Execute(
                          "UPDATE " . TABLE_BOX_NEWS . "
                              SET news_content_type = $news_content_type
                            WHERE box_news_id = $nID
                            LIMIT 1"
                      );
                      $messageStack->add_session(SUCCESS_NEWS_ARTICLE_MOVED, 'success');
                      $link_parms .= "nID=$nID";
                  }
              }
      ```

   3. Find the following code snippet and add the definitions for types 5 through 8:

      ```
      <?php
          if ($all_news_types) {
              $type_selections = array(
                  array('id' => 1, 'text' => BOX_NEWS_NAME_TYPE1),
                  array('id' => 2, 'text' => BOX_NEWS_NAME_TYPE2),
                  array('id' => 3, 'text' => BOX_NEWS_NAME_TYPE3),
                  array('id' => 4, 'text' => BOX_NEWS_NAME_TYPE4)
              );
              if (!isset($nInfo->news_content_type)) {
                  $news_content_type = 1;
              }
      ```

   4. Find the following code snippet and add the definitions for types 5 through 8

      ```
      <?php
      // -----
      // No special action _OR_ 'delete', 'copy' or 'move' (which display the right-sidebox for confirmation)?  Display the
      // current listing of news articles.
      //
      } else {
          $nID = (isset($_GET['nID'])) ? (int)$_GET['nID'] : 0;
          $news_types = array(
              0 => 'Undefined',
              1 => BOX_NEWS_NAME_TYPE1,
              2 => BOX_NEWS_NAME_TYPE2,
              3 => BOX_NEWS_NAME_TYPE3,
              4 => BOX_NEWS_NAME_TYPE4
          );
      ```

   5. Find the following code snippet and add the definitions for types 5 through 8.

      ```
          if (isset($nInfo)) {
              $heading = array();
              $contents = array();
              $cancel_link = '<a href="' . zen_href_link($news_box_script_name, $page_get_params . 'nID=' . $nInfo->box_news_id) . '" class="btn btn-default" role="button">' . IMAGE_CANCEL . '</a>';
      
              $type_selections = array(
                  array('id' => 1, 'text' => BOX_NEWS_NAME_TYPE1),
                  array('id' => 2, 'text' => BOX_NEWS_NAME_TYPE2),
                  array('id' => 3, 'text' => BOX_NEWS_NAME_TYPE3),
                  array('id' => 4, 'text' => BOX_NEWS_NAME_TYPE4)
              );
      ```

4. Edit `/YOUR_ADMIN/includes/languages/english/extra_definitions/news_box_manager_menu_name.php`, finding the following code snippet and adding the definitions for types 5 through 8:

   ```
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
   ```

5. Edit `/YOUR_ADMIN/includes/extra_datafiles/news_box_manager_file_database_names.php`, finding the following code snippet and adding the definitions for types 5 through 8:

   ```
   define('FILENAME_NEWS_BOX_MANAGER1', 'news_box_manager1');
   define('FILENAME_NEWS_BOX_MANAGER2', 'news_box_manager2');
   define('FILENAME_NEWS_BOX_MANAGER3', 'news_box_manager3');
   define('FILENAME_NEWS_BOX_MANAGER4', 'news_box_manager4');
   ```

6. Run the following SQL queries using your admin's `Tools :: Install SQL Patches` (be sure to make a database backup, first!) to enable the additional types to be used in sideboxes:

   ```
   UPDATE configuration SET set_function = 'zen_cfg_select_option(array(\'All\', \'1\', \'2\', \'3\', \'4\', \'5\', \'6\', \'7\', \'8\'),' WHERE configuration_key IN ('NEWS_BOX_SHOW_NEWS_CAT_SB1', 'NEWS_BOX_SHOW_NEWS_CAT_SB2');
   ```

   

Note that the type-specific tools for news types 5 through 8 won't be displayed on the admin menus, but superusers can access a type-specific tool by directly entering the tool's name on the browser's address bar, e.g.: `https://example.com/zcadmin/index.php?cmd=news_box_manager5`
