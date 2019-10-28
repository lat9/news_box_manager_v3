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
require 'includes/application_top.php';

require DIR_WS_FUNCTIONS . 'news_box_manager_functions.php';

// -----
// Starting with v3.0.0, multiple (1-4) news "types" can be defined, with access to the
// individual types' controlled via admin profiles.
//
// Determine which of the types is being processed on this access, so that all redirects
// are kept to the (possibly) sub-version of the tool.
//
$news_box_script_name = basename($PHP_SELF);

// -----
// Pull in the "base" (i.e. all-types) language file, if this access is for a specific
// type.
//
if ($news_box_script_name == FILENAME_NEWS_BOX_MANAGER . '.php') {
    $news_box_type = 0;
    $news_type_name = NEWS_BOX_NAME_ALL;
    $all_news_types = true;
} else {
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
    $all_news_types = false;
    require DIR_WS_LANGUAGES . $_SESSION['language'] . '/news_box_manager.php';
    $news_box_name_type = 'BOX_NEWS_NAME_TYPE' . $news_box_type;
    $news_type_name = (defined($news_box_name_type)) ? constant($news_box_name_type) : ($news_box_name_type . TEXT_NEWS_TYPE_NAME_UNKNOWN);
}

// -----
// Determine which languages are currently in use on the site.
//
$languages = zen_get_languages();

// -----
// Handle (and sanitize) the common $_GET variables.
//
$action = (isset($_GET['action']) ? $_GET['action'] : '');
$nbm_page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
if ($nbm_page < 1) {
    $nbm_page = 1;
    unset($_GET['page']);
}
$current_sort = (isset($_GET['s'])) ? (int)$_GET['s'] : false;
if ($current_sort !== false && ($current_sort < 0 || $current_sort > 3)) {
    $current_sort = false;
    unset($_GET['s']);
}
$keywords = (isset($_GET['search'])) ? (string)$_GET['search'] : false;
$page_get_params = zen_get_all_get_params(array('action', 'nID'));

// -----
// If the admin clicked the "Back" button from the news insert/update preview, 'reset'
// the action to allow modification of the news article's information.
//
if (isset($_POST['edit'])) {
    $action = ($_POST['edit'] == 'newedit') ? 'newedit' : 'updateedit';
}

// -----
// Determine what to do next, based on the current 'action' being performed.
//
switch ($action) {
    case 'insert':
    case 'update':
        if ($all_news_types) {
            $news_content_type = (int)$_POST['news_content_type'];
        } else {
            $news_content_type = $news_box_type;
        }
        $error = false;

        $nID = (!empty($_POST['nID'])) ? (int)$_POST['nID'] : 0;
        
        // -----
        // Add the starting/ending times to the news article's starting/ending dates.
        //
        $news_start_date = ((empty($_POST['news_start_date'])) ? date('Y-m-d') : zen_db_prepare_input($_POST['news_start_date'])) . ' 00:00:00';
        $news_end_date = (empty($_POST['news_end_date'])) ? 'null' : (zen_db_prepare_input($_POST['news_end_date']) . ' 23:59:59');
        
        $sql_data_array = array (
            'news_start_date' => $news_start_date,
            'news_end_date' => $news_end_date,
            'news_content_type' => $news_content_type,
            'news_status' => (int)$_POST['news_status'],
        );

        if ($action == 'insert') {
            $sql_data_array['news_added_date'] = 'now()';
            zen_db_perform(TABLE_BOX_NEWS, $sql_data_array);
            $nID = zen_db_insert_id();
        } else {
            $sql_data_array['news_modified_date'] = 'now()';
            zen_db_perform(TABLE_BOX_NEWS, $sql_data_array, 'update', "box_news_id = $nID");
        }

        $news_metatags_title = $_POST['news_metatags_title'];
        $news_metatags_keywords = $_POST['news_metatags_keywords'];
        $news_metatags_description = $_POST['news_metatags_description'];
        foreach ($languages as $current_language) {
            $lang_id = $current_language['id'];
            $news_title = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $_POST['news_title'][$lang_id]);
            $news_content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $_POST['news_content'][$lang_id]);
            $sql_data_array = array (
                'news_title' => trim($news_title),
                'news_content' => trim($news_content),
                'news_metatags_title' => trim(zen_clean_html($news_metatags_title[$lang_id])),
                'news_metatags_keywords' => (empty(trim(zen_clean_html($news_metatags_keywords[$lang_id])))) ? 'null' : trim(zen_clean_html($news_metatags_keywords[$lang_id])),
                'news_metatags_description' => (empty(trim(zen_clean_html($news_metatags_description[$lang_id])))) ? 'null' : trim(zen_clean_html($news_metatags_description[$lang_id])),
            );

            if ($action == 'insert') {
                $sql_data_array['box_news_id'] = $nID;
                $sql_data_array['languages_id'] = $lang_id;
                zen_db_perform(TABLE_BOX_NEWS_CONTENT, $sql_data_array);
                $change_type = NEWS_ARTICLE_CREATED;
            } else {
                zen_db_perform(TABLE_BOX_NEWS_CONTENT, $sql_data_array, 'update', "box_news_id = $nID AND languages_id = $lang_id");
                $change_type = NEWS_ARTICLE_UPDATED; 
            }
        }
        $messageStack->add_session(sprintf(SUCCESS_NEWS_ARTICLE_CHANGED, $change_type), 'success');
        zen_redirect(zen_href_link($news_box_script_name, $page_get_params . "nID=$nID"));
        break;

    // -----
    // Delete Confirmation: Issued from the 'Delete' sidebox.  Removes the specified news article.
    //
    case 'deleteconfirm':
        if (isset($_POST['nID'])) {
            $nID = (int)$_POST['nID'];
            $db->Execute("DELETE FROM " . TABLE_BOX_NEWS . " WHERE box_news_id = $nID");
            $db->Execute("DELETE FROM " . TABLE_BOX_NEWS_CONTENT . " WHERE box_news_id = $nID");
            $messageStack->add_session(SUCCESS_NEWS_ARTICLE_DELETED, 'success');
        }
        zen_redirect(zen_href_link($news_box_script_name, $page_get_params));
        break;
        
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
        zen_redirect(zen_href_link($news_box_script_name, $link_parms));
        break;
        
    // -----
    // Copy Confirmation: Issued from the 'Copy' sidebox.  Makes a copy (currently disabled) of the
    // article in the article's current 'News Type'.
    //
    case 'copyconfirm':
        $link_parms = $page_get_params;
        if (isset($_POST['nID'])) {
            $nID = (int)$_POST['nID'];
            $article = $db->Execute(
                "SELECT *
                   FROM " . TABLE_BOX_NEWS . "
                  WHERE box_news_id = $nID
                  LIMIT 1"
            );
            $article_content = $db->Execute(
                "SELECT *
                   FROM " . TABLE_BOX_NEWS_CONTENT . "
                  WHERE box_news_id = $nID"
            );
            if (!$article->EOF && !$article_content->EOF) {
                $article_base = $article->fields;
                
                unset($article_base['box_news_id'], $article_base['news_modified_date']);
                
                // -----
                // If the news-end-date is empty, i.e. NULL, remove it from the to-be-recorded
                // record to prevent unwanted '0000-00-00 00:00:00' dates from being stored.
                //
                if (empty($article_base['news_end_date'])) {
                    unset($article_base['news_end_date']);
                }
                
                $article_base['news_added_date'] = 'now()';
                $article_base['news_status'] = 0;
                
                zen_db_perform(TABLE_BOX_NEWS, $article_base);
                $new_nID = zen_db_insert_id();
                
                foreach ($article_content as $content) {
                    $content['box_news_id'] = $new_nID;
                    zen_db_perform(TABLE_BOX_NEWS_CONTENT, $content);
                }
                $messageStack->add_session(SUCCESS_NEWS_ARTICLE_COPIED, 'success');
                $link_parms .= "nID=$nID";
            }
        }
        zen_redirect(zen_href_link($news_box_script_name, $link_parms));
        break;
        
    // -----
    // "Quick" change of status, via click on the red/green status icons from an article's listing display.
    //
    // Note: A change from disabled to enabled is **disallowed** if the news article's title or content is
    // empty in **any** of the store's defined languages!
    //
    case 'status':
        $nID = (!empty($_GET['nID'])) ? (int)$_GET['nID'] : 0;
        $news = $db->Execute(
            "SELECT news_status 
               FROM " . TABLE_BOX_NEWS . " 
              WHERE box_news_id = $nID 
              LIMIT 1"
        );
        $redirect_parms = $page_get_params;
        if (!$news->EOF) {
            $redirect_parms .= "nID=$nID";
            $news_content = $db->Execute(
                "SELECT news_title, news_content
                   FROM " . TABLE_BOX_NEWS_CONTENT . "
                  WHERE box_news_id = $nID"
            );
            $content_ok = true;
            foreach ($news_content as $current_news) {
                if (empty(trim($current_news['news_title'])) || empty(trim($current_news['news_content']))) {
                    $content_ok = false;
                    break;
                }
            }
            $news_status = ($news->fields['news_status'] == 0) ? 1 : 0;
            if ($news_status == 1 && !$content_ok) {
                $messageStack->add_session(ERROR_NEWS_TITLE_CONTENT . ERROR_NEWS_NOT_ENABLED, 'error');
            } else {
                $db->Execute(
                    "UPDATE " . TABLE_BOX_NEWS . " 
                        SET news_status = $news_status, 
                            news_modified_date = now() 
                      WHERE box_news_id = $nID 
                      LIMIT 1"
                );
            }
        }
        zen_redirect(zen_href_link($news_box_script_name, $redirect_parms));
        break;

    // -----
    // Change the editor used for the article's descriptions.  The change will be done by init_html_editor.php;
    // we just need to redirect to get the page to refresh properly.
    //
    case 'set_editor':
        $params = $page_get_params;
        if (isset($_GET['nID']) && ((int)$_GET['nID']) > 0) {
            $params .= 'nID=' . (int)$_GET['nID'];
        }
        zen_redirect(zen_href_link($news_box_script_name, $params));
        break;
        
    // -----
    // 1) Preview Only: Issued via click on the 'preview' icon on a news article's listing entry.
    // 2) Modify Article: Issued via click on the 'Edit' icon from an existing article's listing entry.
    // 3) Copy an Article: Issued via click on the 'Copy' icon from an existing article's listing entry.
    // 4) Move an Article: Issued via click on the 'Move' icon from an existing article's listing entry.
    //    - Note that 'Move' is a little different; it's allowed only from the 'all articles' display tool.
    //
    // In any case, we'll pull the requested article's information from the database for the follow-on display.
    //
    case 'move':
        if (!$all_news_types) {
            zen_redirect(zen_href_link($news_box_script_name, $page_get_params));
        }
    case 'copy':                    //-Fall-through from processing above ...
    case 'previewonly':
    case 'modify':
        if (empty($_GET['nID']) || ((int)$_GET['nID'] < 1)) {
            zen_redirect(zen_href_link($news_box_script_name, $page_get_params));
        }
        
        $nID = (int)$_GET['nID'];
        $news_info = $db->Execute(
            "SELECT *
               FROM " . TABLE_BOX_NEWS . "
             WHERE box_news_id = $nID
             LIMIT 1"
        );
        if ($news_info->EOF) {
            zen_redirect(zen_href_link($news_box_script_name, $page_get_params));
        }
        $nInfo = new objectInfo($news_info->fields);
        
        // -----
        // The news_start_date (required) and news_end_date (optional) are stored as 'datetime'
        // fields, but entered as solely the date.  Make sure that the time-related portion of
        // each date is stripped for the display.
        //
        $nInfo->news_start_date = substr($nInfo->news_start_date, 0, strpos($nInfo->news_start_date, ' '));
        if (!empty($nInfo->news_end_date)) {
            $nInfo->news_end_date = substr($nInfo->news_end_date, 0, strpos($nInfo->news_end_date, ' '));
        }
        
        $news_content_info = $db->Execute(
            "SELECT *
               FROM " . TABLE_BOX_NEWS_CONTENT . "
              WHERE box_news_id = $nID"
        );
        if ($news_content_info->EOF) {
            zen_redirect(zen_href_link($news_box_script_name, $page_get_params));
        }
        $nInfo->news_title = array();
        $nInfo->news_content = array();
        $nInfo->news_metatags_title = array();
        $nInfo->news_metatags_keywords = array();
        $nInfo->news_metatags_description = array();
        foreach ($news_content_info as $news_content) {
            $lang_id = $news_content['languages_id'];
            $nInfo->news_title[$lang_id] = $news_content['news_title'];
            $nInfo->news_content[$lang_id] = $news_content['news_content'];
            $nInfo->news_metatags_title[$lang_id] = $news_content['news_metatags_title'];
            $nInfo->news_metatags_keywords[$lang_id] = $news_content['news_metatags_keywords'];
            $nInfo->news_metatags_description[$lang_id] = $news_content['news_metatags_description'];
        }
        break;

    // -----
    // 1) New/Update Preview: Issued via click on the 'Preview' button from an article's data-entry page.
    // 2) New/Update Edit: Issued via click of the 'Back' button from an article's data-entry page.
    //
    // In all cases, the updated article information "should" be present as posted values.  We'll
    // create an object, if there **are** posted values, for the follow-on display/edit operation.
    //
    case 'newpreview':
    case 'updatepreview':
    case 'newedit':
    case 'updateedit':
        if (empty($_POST) || (($action == 'updatepreview' || $action == 'updateedit') && empty($_POST['nID']))) {
            zen_redirect(zen_href_link($news_box_script_name, $page_get_params));
        }
        
        // -----
        // On the transition from the new/edit action to its 'preview', make sure that
        // the required fields are set and valid.  If not, kick the admin back to the
        // editing page.
        //
        if ($action == 'newpreview' || $action == 'updatepreview') { 
            // -----
            // Validate the article's starting and ending dates.
            //
            $error = false;
            if (!empty($_POST['news_start_date'])) {
                $date_elements = explode('-', $_POST['news_start_date']);
                if (count($date_elements) != 3 || !checkdate((int)$date_elements[1], (int)$date_elements[2], (int)$date_elements[0])) {
                    $error = true;
                    $messageStack->add(ERROR_NEWS_START_DATE, 'error');
                }
            }
            if (!empty($_POST['news_end_date'])) {
                $date_elements = explode('-', $_POST['news_end_date']);
                if (count($date_elements) != 3 || !checkdate((int)$date_elements[1], (int)$date_elements[2], (int)$date_elements[0])) {
                    $error = true;
                    $messageStack->add(ERROR_NEWS_END_DATE, 'error');
                }
            }
            $news_start = (empty($_POST['news_start_date'])) ? date('Y-m-d') : $_POST['news_start_date'];
            $news_end = (empty($_POST['news_end_date'])) ? 'null' : $_POST['news_end_date'];
            if (!$error && $news_end != 'null' && $news_start > $news_end) {
                $error = true;
                $messageStack->add(ERROR_NEWS_DATE_ISSUES, 'error');
            }
            
            // -----
            // For the news article to be saved, it must have both a title and content in **all** languages defined for the store
            // and neither the title nor the content can contain any <script></script> tags.
            //
            $content_error = false;
            foreach ($languages as $current_language) {
                $lang_id = $current_language['id'];
                if (preg_match('#<script.*?>.*?</script>#is', $_POST['news_title'][$lang_id])) {
                    $content_error = true;
                    break;
                }
                if (preg_match('#<script.*?>.*?</script>#is', $_POST['news_content'][$lang_id])) {
                    $content_error = true;
                    break;
                }
                if (empty(trim($_POST['news_title'][$lang_id])) || empty(trim($_POST['news_content'][$lang_id]))) {
                    $content_error = true;
                    break;
                }
            }
            if ($content_error) {
                $error = true;
                $messageStack->add(ERROR_NEWS_TITLE_CONTENT, 'error');
            }
            
            if ($error) {
                $action = ($action == 'newpreview') ? 'newedit' : 'updateedit';
            }
        }
        $nID = (int)$_POST['nID'];
        $nInfo = new objectInfo($_POST);
        break;
        
    // -----
    // New Article: Issued via click on the 'Insert' button on the articles' listing page.
    //
    case 'new':
        $parameters = array( 
            'news_added_date' => '',
            'news_content_type' => ($all_news_types) ? 1 : $news_box_type,
            'news_modified_date' => '',
            'news_start_date' => '',
            'news_end_date' => '',
            'news_status' => 0,
            'news_title' => array(),
            'news_content' => array(),
            'news_metatags_title' => array(),
            'news_metatags_keywords' => array(),
            'news_metatags_description' => array(),
        );
        foreach ($languages as $current_language) {
            $lang_id = $current_language['id'];
            $parameters['news_title'][$lang_id] = '';
            $parameters['news_content'][$lang_id] = '';
            $parameters['news_metatags_title'][$lang_id] = '';
            $parameters['news_metatags_keywords'][$lang_id] = '';
            $parameters['news_metatags_description'][$lang_id] = '';
        }
        $nInfo = new objectInfo($parameters);
        unset($parameters);
        break;

    default:
        break;
}
?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta charset="<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" href="includes/stylesheet.css">
<link rel="stylesheet" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<style>
<!--
.green { color: green; }
.red { color: red; }
.meta-tags { text-align: center; padding: 0.5em 0; }
.large { font-size: large; }
.larger { font-size: larger; }
.smaller { font-size: smaller; }
.nb-padding { padding: 0.5em; }
-->
</style>
<script src="includes/menu.js"></script>
<script src="includes/general.js"></script>
<script>
<!--
function init()
{
    cssjsmenu('navbar');
    if (document.getElementById) {
        var kill = document.getElementById('hoverJS');
        kill.disabled = true;
    }
    if (typeof _editor_url == "string") {
        HTMLArea.replaceAll();
    }
}
// -->
</script>
<?php
if ($editor_handler != '') {
    include $editor_handler;
}
?>
</head>
<body onload="init();">
<!-- header //-->
<?php require DIR_WS_INCLUDES . 'header.php'; ?>
<!-- header_eof //-->
<!-- body //-->
<div class="container-fluid">
<?php
switch ($action) {
    case 'new':
    case 'newedit':
        $subheading = NEWS_BOX_SUBHEADING_NEW;
        break;

    case 'modify':
    case 'updateedit':
        $subheading = NEWS_BOX_SUBHEADING_EDIT;
        break;

    case 'newpreview':
    case 'updatepreview':
    case 'previewonly':
        $subheading = NEWS_BOX_SUBHEADING_PREVIEW;
        break;

    default:
        $subheading = sprintf(NEWS_BOX_SUBHEADING_LISTING, $news_type_name);
        break;
}
?>
    <h1><?php echo NEWS_BOX_HEADING_TITLE; ?> <span class="smaller"><?php echo $subheading; ?></span></h1>
<?php
// -----
// Editing an existing article or inserting a new one ... noting that we can also come back here
// from an article's insert/update "Preview" to make changes prior to saving in the database.
//
if ($action == 'modify' || $action == 'updateedit' || $action == 'new' || $action == 'newedit') {
    if ($action == 'modify' || $action == 'updateedit') {
        $form_action = 'updatepreview' . "&amp;nID=$nID";
        $hidden_field = zen_draw_hidden_field('nID', $nID);
        $cancel_link = zen_href_link($news_box_script_name, $page_get_params . "nID=$nID");
    } else {
        $form_action = 'newpreview';
        $hidden_field = ($all_news_types) ? '' : zen_draw_hidden_field('news_content_type', $news_box_type);
        $cancel_link = zen_href_link($news_box_script_name, $page_get_params);
    }
    if (!empty($page_get_params)) {
        $page_get_params = '&amp;' . $page_get_params;
    }
?>
    <?php echo zen_draw_form('news', $news_box_script_name, "action=$form_action" . $page_get_params, 'post', 'enctype="multipart/form-data" class="form-horizontal"') . $hidden_field; ?>
        <div>
            <span class="floatButton text-right">
                <button type="submit" class="btn btn-primary"><?php echo IMAGE_PREVIEW; ?></button>&nbsp;&nbsp;<a href="<?php echo $cancel_link; ?>" class="btn btn-default" role="button"><?php echo IMAGE_CANCEL; ?></a>
            </span>
        </div>
        
        <div class="form-group">
            <?php echo zen_draw_label(TEXT_NEWS_STATUS, 'news_status', 'class="col-sm-3 control-label"'); ?>
            <div class="col-sm-9 col-md-6">
                <label class="radio-inline"><?php echo zen_draw_radio_field('news_status', '1', ($nInfo->news_status == 1)) . TEXT_ENABLED; ?></label>
                <label class="radio-inline"><?php echo zen_draw_radio_field('news_status', '0', ($nInfo->news_status == 0)) . TEXT_DISABLED; ?></label>
            </div>
        </div>
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
?>
        <div class="form-group">
            <?php echo zen_draw_label(TEXT_NEWS_CHOOSE_TYPE, 'news_content_type', 'class="col-sm-3 control-label"'); ?>
            <div class="col-sm-9 col-md-6">
                <?php echo zen_draw_pull_down_menu('news_content_type', $type_selections, $nInfo->news_content_type, 'class="form-control"'); ?>
            </div>
        </div>
<?php
    }
?>
        <div class="form-group">
            <?php echo zen_draw_label(TEXT_NEWS_START_DATE, 'news_start_date', 'class="col-sm-3 control-label"'); ?>
            <div class="col-sm-9 col-md-6">
                <div class="date input-group" id="datepicker-start">
                    <span class="input-group-addon datepicker_icon">
                        <i class="fa fa-calendar fa-lg"></i>
                    </span>
                    <?php echo zen_draw_input_field('news_start_date', $nInfo->news_start_date, 'class="form-control"'); ?>
                </div>
                <span class="help-block"><?php echo TEXT_NEWS_START_DATE_HELP; ?></span>
            </div>
        </div>
        
        <div class="form-group">
            <?php echo zen_draw_label(TEXT_NEWS_END_DATE, 'news_end_date', 'class="col-sm-3 control-label"'); ?>
            <div class="col-sm-9 col-md-6">
                <div class="date input-group" id="datepicker-end">
                    <span class="input-group-addon datepicker_icon">
                        <i class="fa fa-calendar fa-lg"></i>
                    </span>
                    <?php echo zen_draw_input_field('news_end_date', $nInfo->news_end_date, 'class="form-control"'); ?>
                </div>
                <span class="help-block"><?php echo TEXT_NEWS_END_DATE_HELP; ?></span>
            </div>
        </div>
<?php 
    $title_max_length = zen_set_field_length(TABLE_BOX_NEWS_CONTENT, 'news_title');
    $metatags_title_max_length = zen_set_field_length(TABLE_BOX_NEWS_CONTENT, 'news_metatags_title');
    foreach ($languages as $current_language){
        $lang_dir = $current_language['directory'];
        $lang_image_file = $current_language['image'];
        $lang_name = $current_language['name'];
        $lang_image = zen_image(DIR_WS_CATALOG_LANGUAGES . "$lang_dir/images/$lang_image_file", $lang_name);
        
        $lang_id = $current_language['id'];
?>
        <div class="form-group">
            <div class="col-sm-3 control-label">&nbsp;</div>
            <div class="col-sm-9 col-md-6">
                <div class="input-group">
                    <span class="input-group-addon"><?php echo $lang_image; ?></span>
                    <div class="form-control"><strong><?php echo sprintf(TEXT_NEWS_FOR_LANGUAGE, $current_language['name']); ?></strong></div>
                </div>
                <span class="help-block"><?php echo TEXT_NEWS_CONTENT_HELP; ?></span>
            </div>
        </div>

        <div class="form-group">
            <?php echo zen_draw_label(TEXT_NEWS_TITLE, 'news_title', 'class="col-sm-3 control-label"'); ?>
            <div class="col-sm-9 col-md-6">
                <div class="input-group">
                    <?php echo zen_draw_input_field("news_title[$lang_id]", $nInfo->news_title[$lang_id], $title_max_length . ' class="form-control"'); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?php echo zen_draw_label(TEXT_NEWS_CONTENT, 'news_content', 'class="col-sm-3 control-label"'); ?>
            <div class="col-sm-9 col-md-6">
                <div class="input-group">
                    <?php echo zen_draw_textarea_field("news_content[$lang_id]", 'soft', '100%', '20', $nInfo->news_content[$lang_id], 'class="editorHook form-control" id="ta' . $lang_id . '"'); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?php echo zen_draw_label(TEXT_NEWS_METATAGS_TITLE, 'news_metatags_title', 'class="col-sm-3 control-label"'); ?>
            <div class="col-sm-9 col-md-6">
                <div class="input-group">
                    <?php echo zen_draw_input_field("news_metatags_title[$lang_id]", $nInfo->news_metatags_title[$lang_id], $metatags_title_max_length . ' class="form-control"'); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?php echo zen_draw_label(TEXT_NEWS_METATAGS_KEYWORDS, 'news_metatags_keywords', 'class="col-sm-3 control-label"'); ?>
            <div class="col-sm-9 col-md-6">
                <div class="input-group">
                    <?php echo zen_draw_textarea_field("news_metatags_keywords[$lang_id]", 'soft', '100%', '1', $nInfo->news_metatags_keywords[$lang_id], 'class="noEditor form-control"'); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?php echo zen_draw_label(TEXT_NEWS_METATAGS_DESCRIPTION, 'news_metatags_description', 'class="col-sm-3 control-label"'); ?>
            <div class="col-sm-9 col-md-6">
                <div class="input-group">
                    <?php echo zen_draw_textarea_field("news_metatags_description[$lang_id]", 'soft', '100%', '2', $nInfo->news_metatags_description[$lang_id], 'class="noEditor form-control"'); ?>
                </div>
            </div>
        </div>
                            
        <hr />
<?php
    }
    echo '</form>';
// -----
// Previewing an article (from the listing) or prior to saving changes via edit/insert ...
//
} elseif ($action == 'previewonly' || $action == 'newpreview' || $action == 'updatepreview') {
    $cancel_parms = $page_get_params;
    if ($nID > 0) {
        $cancel_parms .= "nID=$nID";
    }
    $cancel_link = zen_href_link($news_box_script_name, $cancel_parms);
    if ($action == 'previewonly') {
        $submit_buttons = '';
        $cancel_name = IMAGE_BACK;
    } else {
        $form_action = ($action == 'updatepreview') ? 'update' : 'insert';
        $submit_buttons = zen_draw_form('news', $news_box_script_name, "action=$form_action" . ((empty($page_get_params)) ? '' : "&amp;$page_get_params"));
        if ($action == 'updatepreview') {
            $edit_button_value = 'updateedit';
            $submit_buttons .= zen_draw_hidden_field('nID', $nID);
        } else {
            $edit_button_value = 'newedit';
        }
        $submit_buttons .= zen_draw_hidden_field('news_content_type', $nInfo->news_content_type);
        $submit_buttons .= zen_draw_hidden_field('news_status', $nInfo->news_status);
        $submit_buttons .= zen_draw_hidden_field('news_start_date', $nInfo->news_start_date);
        $submit_buttons .= zen_draw_hidden_field('news_end_date', $nInfo->news_end_date);
        
        $cancel_name = IMAGE_CANCEL;
    }
    $news_content_type = $nInfo->news_content_type;
?>
    <hr />
    <div class="row large">
        <div class="col-sm-1"><strong><?php echo TEXT_NEWS_TYPE; ?></strong></div>
        <div class="col-sm-2"><?php echo constant("BOX_NEWS_NAME_TYPE$news_content_type"); ?></div>

        <div class="col-sm-1"><strong><?php echo TEXT_NEWS_STATUS; ?></strong></div>
        <div class="col-sm-2"><?php echo ($nInfo->news_status == 0) ? TEXT_DISABLED : TEXT_ENABLED; ?></div>

        <div class="col-sm-1"><strong><?php echo TEXT_NEWS_START_DATE; ?></strong></div>
        <div class="col-sm-2"><?php echo (empty($nInfo->news_start_date)) ? date('Y-m-d') : $nInfo->news_start_date; ?></div>

        <div class="col-sm-1"><strong><?php echo TEXT_NEWS_END_DATE; ?></strong></div>
        <div class="col-sm-2"><?php echo (empty($nInfo->news_end_date)) ? TEXT_NEVER : $nInfo->news_end_date; ?></div>
    </div>
    <hr />
<?php
    foreach ($languages as $current_language) {
        $lang_id = $current_language['id'];
        if (!empty($submit_buttons)) {
            $submit_buttons .= zen_draw_hidden_field("news_title[$lang_id]", $nInfo->news_title[$lang_id]);
            $submit_buttons .= zen_draw_hidden_field("news_content[$lang_id]", $nInfo->news_content[$lang_id]);
            $submit_buttons .= zen_draw_hidden_field("news_metatags_title[$lang_id]", $nInfo->news_metatags_title[$lang_id]);
            $submit_buttons .= zen_draw_hidden_field("news_metatags_keywords[$lang_id]", $nInfo->news_metatags_keywords[$lang_id]);
            $submit_buttons .= zen_draw_hidden_field("news_metatags_description[$lang_id]", $nInfo->news_metatags_description[$lang_id]);
        }
        
        // -----
        // If the news-content doesn't contain any formatting HTML, convert any new-lines to <br>'s so that the on-screen formatting looks "nicer".
        //
        $news_content = (!preg_match('/(<br|<p|<div|<dd|<li|<span)/i', $nInfo->news_content[$lang_id]) ? nl2br($nInfo->news_content[$lang_id]) : $nInfo->news_content[$lang_id]);
        
        // -----
        // Enable the preview display of any images where the admin has specified an image name in either an href="" or src="" attribute
        // using a relocatable form of that name, e.g. src="images/somefile.jpg".
        //
        $news_content = preg_replace(
            array(
                '#href="' . DIR_WS_IMAGES . '#',
                '#src="' . DIR_WS_IMAGES . '#'
            ),
            array(
                'href="../' . DIR_WS_IMAGES,
                'src="../' . DIR_WS_IMAGES
            ),
            $news_content
        );
?>
    <div class="row">
        <div class="col-sm-6 pageHeading">
          <?php echo zen_image(DIR_WS_CATALOG_LANGUAGES . $current_language['directory'] . '/images/' . $current_language['image'], $current_language['name']) . '&nbsp;' . $nInfo->news_title[$lang_id]; ?>
        </div>
    </div>
    <div class="row"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></div>
    <div class="row"><?php echo $news_content; ?></div>
    <hr />

    <a href="#metatags-<?php echo $lang_id; ?>" class="btn btn-info" data-toggle="collapse"><?php echo TEXT_METATAGS_SHOW_HIDE; ?></a>
    <div id="metatags-<?php echo $lang_id; ?>" class="collapse">
        <div class="row nb-padding">
            <div class="col-sm-2"><strong><?php echo TEXT_METATAGS_TITLE; ?></strong></div>
            <div class="col-sm-10"><?php echo empty($nInfo->news_metatags_title[$lang_id]) ? TEXT_NO_METATAGS : $nInfo->news_metatags_title[$lang_id]; ?></div>
        </div>
        <div class="row nb-padding">
            <div class="col-sm-2"><strong><?php echo TEXT_METATAGS_KEYWORDS; ?></strong></div>
            <div class="col-sm-10"><?php echo empty($nInfo->news_metatags_keywords[$lang_id]) ? TEXT_NO_METATAGS : $nInfo->news_metatags_keywords[$lang_id]; ?></div>
        </div>
        <div class="row nb-padding">
            <div class="col-sm-2"><strong><?php echo TEXT_METATAGS_DESCRIPTION; ?></strong></div>
            <div class="col-sm-10"><?php echo empty($nInfo->news_metatags_description[$lang_id]) ? TEXT_NO_METATAGS : $nInfo->news_metatags_description[$lang_id]; ?></div>
        </div>
    </div>
    <hr />
<?php
    }
    
    if (!empty($submit_buttons)) {
        $submit_buttons .= '<button type="submit" name="edit" value="' . $edit_button_value . '" class="btn btn-default">' . IMAGE_BACK . '</button>&nbsp;&nbsp;';
        $submit_buttons .= '<button type="submit" class="btn btn-primary">' . (($form_action == 'update') ? IMAGE_UPDATE : IMAGE_INSERT) . '</button>&nbsp;&nbsp;';
        $submit_buttons .= '</form>';
    }
?>
   <div class="row text-center"><?php echo $submit_buttons; ?>&nbsp;<a href="<?php echo $cancel_link; ?>" class="btn btn-default" role="button"><?php echo $cancel_name; ?></a></div>
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
?>
    <p><?php echo TEXT_NEWS_BOX_MANAGER_INFO; ?></p>
<?php
// -----
// Don't render sort/search/editor forms when a sidebox action is active.
//
    if ($action == '') {
        // -----
        // Choose sort order form.
        //
?>
    <div class="col-md-4">
         <div>
<?php
        $sort_choices = array(
            array('id' => '0', 'text' => NEWS_SORT_START_DESC),
            array('id' => '1', 'text' => NEWS_SORT_START_ASC),
            array('id' => '2', 'text' => NEWS_SORT_ENABLED),
            array('id' => '3', 'text' => NEWS_SORT_DISABLED),
        );
        // toggle switch for editor
        echo zen_draw_form('sort_news_form', $news_box_script_name, '', 'get', 'class="form-horizontal"');
        echo zen_draw_label(TEXT_NEWS_SORT_ORDER, 's', 'class="col-sm-6 col-md-4 control-label"');
        echo '<div class="col-sm-6 col-md-8">' . zen_draw_pull_down_menu('s', $sort_choices, (int)$current_sort, 'onchange="this.form.submit();" class="form-control"') . '</div>';
        echo zen_hide_session_id();
        if ($nID > 0) {
            echo zen_draw_hidden_field('nID', $nID);
        }
        if ($keywords !== false) {
            echo zen_draw_hidden_field('search', $keywords);
        }
        echo zen_draw_hidden_field('page', $nbm_page);
        echo '</form>';
?>
          </div>
    </div>
<?php
        // -----
        // Search news form.
        //
?>
    <div class="col-md-4">
        <div>
<?php
        echo zen_draw_form('search_news_form', $news_box_script_name, '', 'get', 'class="form-horizontal"');
?>
            <div class="col-sm-6 col-md-4 control-label"><?php echo zen_draw_label(HEADING_TITLE_SEARCH_DETAIL, 'search'); ?></div>
            <div class="col-sm-6 col-md-8"><?php echo zen_draw_input_field('search', '', ($action == '' ? 'autofocus="autofocus"' : '') . ' class="form-control"'); ?></div>
            <div class="col"><?php echo zen_draw_separator('pixel_trans.gif', '100%', '1'); ?></div>
<?php
            echo zen_hide_session_id();
            if (!empty($keywords)) {
?>
            <div class="col-sm-6 col-md-4 control-label"><?php echo TEXT_INFO_SEARCH_DETAIL_FILTER; ?></div>
            <div class="col-sm-6 col-md-8">
<?php 
                echo zen_output_string_protected($keywords);
?>
                <a href="<?php echo zen_href_link($news_box_script_name, zen_get_all_get_params(array('action', 'search'))); ?>" class="btn btn-default" role="button"><?php echo IMAGE_RESET; ?></a>
            </div>
<?php
            }
            if ($current_sort !== false) {
                echo zen_draw_hidden_field('s', $current_sort);
            }
            echo '</form>';
?>
        </div>
    </div>
<?php
        // -----
        // Choose editor form.
        //
?>
    <div class="col-md-4">
         <div>
<?php
        // toggle switch for editor
        echo zen_draw_form('set_editor_form', $news_box_script_name, '', 'get', 'class="form-horizontal"');
        echo zen_draw_label(TEXT_EDITOR_INFO, 'reset_editor', 'class="col-sm-6 col-md-4 control-label"');
        echo '<div class="col-sm-6 col-md-8">' . zen_draw_pull_down_menu('reset_editor', $editors_pulldown, $current_editor_key, 'onchange="this.form.submit();" class="form-control"') . '</div>';
        echo zen_hide_session_id();
        if ($nID > 0) {
            echo zen_draw_hidden_field('nID', $nID);
        }
        echo zen_draw_hidden_field('page', $nbm_page);
        if ($current_sort !== false) {
            echo zen_draw_hidden_field('s', $current_sort);
        }
        if ($keywords !== false) {
            echo zen_draw_hidden_field('search', $keywords);
        }
        echo zen_draw_hidden_field('action', 'set_editor');
        echo '</form>';
?>
          </div>
    </div>
<?php
    }

// -----
// Start table output ...
?>
    <div <?php echo (empty($action)) ? '' : 'class="col-xs-12 col-sm-12 col-md-9 col-lg-9 configurationColumnLeft"'; ?>>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center"><?php echo TABLE_HEADING_NEWS_ID; ?></th>
                    <th><?php echo TABLE_HEADING_NEWS_TITLE; ?></th>
<?php
    if ($all_news_types) {
?>
                    <th class="text-center"><?php echo TABLE_HEADING_NEWS_TYPE; ?></th>
<?php
    }
?>
                    <th class="text-right"><?php echo TABLE_HEADING_NEWS_START; ?></th>
                    <th class="text-right"><?php echo TABLE_HEADING_NEWS_END; ?></th>
                    <th class="text-right"><?php echo TABLE_HEADING_MODIFIED; ?></th>
                    <th class="text-center"><?php echo TABLE_HEADING_STATUS; ?></th>
                    <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?></th>
                </tr>
            </thead>
<?php
    switch ((int)$current_sort) {
        // -----
        // Order by article start-date, oldest first.
        //
        case 1:
            $order_by = 'n.news_start_date ASC, n.box_news_id ASC';
            break;
        // ------
        // Enabled articles first, then by article ID.
        //
        case 2:
            $order_by = 'n.news_status DESC, n.box_news_id ASC';
            break;
        // -----
        // Disabled articles first, then by article ID.
        //
        case 3:
            $order_by = 'n.news_status ASC, n.box_news_id ASC';
            break;
        // -----
        // Order by article start-date, newest first.
        //
        default:
            $order_by = 'n.news_start_date DESC, n.box_news_id ASC';
            break;
    }
    $where_clause = ($all_news_types) ? '' : " WHERE news_content_type = $news_box_type";
    if (!empty($keywords)) {
        if (empty($where_clause)) {
            $where_clause = ' WHERE ';
        } else {
            $where_clause .= ' AND ';
        }
        $search_clause = '%' . zen_db_input($keywords) . '%';
        $where_clause .= "(nc.news_title LIKE '$search_clause' OR nc.news_content LIKE '$search_clause')";
    }
    $news_query_raw = 
        "SELECT n.box_news_id, nc.news_title, nc.news_content, n.news_added_date, n.news_modified_date, n.news_start_date, n.news_end_date, n.news_status, n.news_content_type 
           FROM " . TABLE_BOX_NEWS . " n
                INNER JOIN " . TABLE_BOX_NEWS_CONTENT . " nc 
                    ON nc.box_news_id = n.box_news_id
                   AND nc.languages_id = " . (int)$_SESSION['languages_id'] . "
           $where_clause
          ORDER BY $order_by";
    $news_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $news_query_raw, $news_query_numrows);
    $news = $db->Execute($news_query_raw);
    while (!$news->EOF) {
        $start_date = date('Y-m-d', strtotime($news->fields['news_start_date']));
        $start_date_class = ($start_date <= date('Y-m-d')) ? 'green' : 'red';

        $end_date_class = ($news->fields['news_end_date'] == null || $news->fields['news_end_date'] >= date('Y-m-d')) ? 'green' : 'red';
        $news_end_date = ($news->fields['news_end_date'] == null) ? TEXT_NONE : zen_date_short($news->fields['news_end_date']);
        
        $link_parms = $page_get_params . 'nID=' . $news->fields['box_news_id'];
        
        $row_class = '';
        if ($nID == $news->fields['box_news_id']) {
            $row_class = ' class="info"';
            $nInfo = new objectInfo($news->fields);
        }
?>
            <tr onclick="document.location.href='<?php echo zen_href_link($news_box_script_name, $link_parms . '&amp;action=modify');?>'" role="button"<?php echo $row_class; ?>>
                <td class="text-center"><?php echo $news->fields['box_news_id']; ?></td>
                <td>
                    <a href="<?php echo zen_href_link($news_box_script_name, $link_parms . '&amp;action=previewonly');?>"><?php echo zen_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW); ?></a>&nbsp;<?php echo $news->fields['news_title']; ?>
                </td>
<?php
        if ($all_news_types) {
?>
                <td class="text-center"><?php echo $news_types[$news->fields['news_content_type']]; ?></td>
<?php
        }
?>
                <td class="text-right"><span class="<?php echo $start_date_class; ?>"><?php echo zen_date_short($news->fields['news_start_date']); ?></span></td>
                <td class="text-right"><span class="<?php echo $end_date_class; ?>"><?php echo $news_end_date; ?></span></td>
                <td class="text-right"><?php echo (($news->fields['news_modified_date'] == NULL) ? $news->fields['news_added_date'] : $news->fields['news_modified_date']); ?></td>
                <td class="text-center">
<?php
        echo zen_draw_form('setstatus', $news_box_script_name, $link_parms . '&amp;action=status');
        if ($news->fields['news_status'] == 0) {
            $icon_image = 'icon_red_on.gif';
            $icon_title = IMAGE_ICON_STATUS_OFF;
        } else {
            $icon_image = 'icon_green_on.gif';
            $icon_title = IMAGE_ICON_STATUS_ON;
        }
?>
                    <input type="image" src="<?php echo DIR_WS_IMAGES . $icon_image; ?>" alt="<?php echo $icon_title; ?>" />
                </form></td>
                <td class="text-right">
                    <a href="<?php echo zen_href_link($news_box_script_name, $link_parms . '&amp;action=modify'); ?>" style="text-decoration: none">
                        <div class="fa-stack fa-lg edit"><i class="fa fa-circle fa-stack-2x base"></i><i class="fa fa-pencil fa-stack-1x overlay" aria-hidden="true"></i></div>
                    </a>
                    <a href="<?php echo zen_href_link($news_box_script_name, $link_parms . '&amp;action=delete'); ?>" style="text-decoration: none">
                        <div class="fa-stack fa-lg delete"><i class="fa fa-circle fa-stack-2x base"></i><i class="fa fa-trash-o fa-stack-1x overlay" aria-hidden="true"></i></div>
                    </a>
<?php
        // -----
        // Don't offer to 'Move' an article if we're not displaying **all** news articles.  The admin might not
        // have permission to view/modify other article types.
        //
        if ($all_news_types) {
?>
                    <a href="<?php echo zen_href_link($news_box_script_name, $link_parms . '&amp;action=move'); ?>" style="text-decoration: none">
                        <div class="fa-stack fa-lg move"><i class="fa fa-circle fa-stack-2x base"></i><i class="fa fa-stack-1x overlay" aria-hidden="true"><strong>M</strong></i></div>
                    </a>
<?php
        }
?>
                    <a href="<?php echo zen_href_link($news_box_script_name, $link_parms . '&amp;action=copy'); ?>" style="text-decoration: none">
                        <div class="fa-stack fa-lg copy"><i class="fa fa-circle fa-stack-2x base"></i><i class="fa fa-stack-1x overlay" aria-hidden="true"><strong>C</strong></i></div>
                    </a>
                </td>
            </tr>
<?php
        $news->MoveNext();
    }
?>
        </table>
    </div>
<?php
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

        switch ($action) {
            case 'delete':
                // -----
                // No delete-confirm displayed if $_GET['nID'] is not valid.
                //
                if ($nID < 1) {
                    break;
                }
                $heading[] = array('text' => '<h4>' . TEXT_INFO_HEADING_DELETE_NEWS . '</h4>');
                
                $contents = array('form' => zen_draw_form('news', $news_box_script_name, $page_get_params . 'action=deleteconfirm') . zen_draw_hidden_field('nID', $nInfo->box_news_id));
                $contents[] = array('text' => TEXT_NEWS_DELETE_INFO);
                $contents[] = array('text' => '<strong>' . $nInfo->news_title . '</strong>');
                $contents[] = array('align' => 'center', 'text' => '<button type="submit" class="btn btn-danger">' . IMAGE_DELETE . '</button> ' . $cancel_link);
                break;
                
            case 'copy':
                $heading[] = array('text' => '<h4>' . TEXT_INFO_HEADING_COPY_NEWS . '</h4>');
                
                $contents = array('form' => zen_draw_form('news', $news_box_script_name, $page_get_params . 'action=copyconfirm') . zen_draw_hidden_field('nID', $nInfo->box_news_id));
 
                $contents[] = array('text' => '<strong>' . $nInfo->news_title . '</strong>');
                $contents[] = array('text' => TEXT_INFO_COPY_ARTICLE);
                $contents[] = array('align' => 'center', 'text' => '<button type="submit" class="btn btn-danger">' . IMAGE_COPY . '</button> ' . $cancel_link);
                break;
                
            case 'move':
                $heading[] = array('text' => '<h4>' . TEXT_INFO_HEADING_MOVE_NEWS . '</h4>');
                
                $contents = array('form' => zen_draw_form('news', $news_box_script_name, $page_get_params . 'action=moveconfirm') . zen_draw_hidden_field('nID', $nInfo->box_news_id));
 
                $contents[] = array('text' => '<strong>' . $nInfo->news_title . '</strong>');
                $contents[] = array('text' => TEXT_INFO_MOVE_CHOOSE_TYPE);
                $contents[] = array('text' => zen_draw_pull_down_menu('news_content_type', $type_selections, $nInfo->news_content_type, 'class="form-control"'));
                $contents[] = array('align' => 'center', 'text' => '<button type="submit" class="btn btn-danger">' . IMAGE_MOVE . '</button> ' . $cancel_link);
                break;

            default:
                break;
        }
        
        if (!empty($heading) && !empty($contents)) {
            $box = new box;
?>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 configurationColumnRight"><?php echo $box->infoBox($heading, $contents); ?></div>
<?php
        }
    }
    
    if ($news_query_numrows == 0) {
        $news_display_count = '';
        $news_display_links = '';
    } else {
        $news_display_count = $news_split->display_count($news_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nbm_page, TEXT_DISPLAY_NUMBER_OF_NEWS);
        $news_display_links = $news_split->display_links($news_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nbm_page, zen_get_all_get_params(array('action', 'page', 'nID')));
    }
?>
    <div class="row text-center">
        <div class="col-sm-4"><?php echo $news_display_count; ?></div>
        <div class="col-sm-4"><?php echo $news_display_links; ?></div>
        <div class="col-sm-4">
            <a href="<?php echo zen_href_link($news_box_script_name, $page_get_params . 'action=new'); ?>" class="btn btn-primary" role="button"><?php echo IMAGE_INSERT; ?></a>
        </div>
    </div>
<?php
}
?>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require DIR_WS_INCLUDES . 'footer.php'; ?>
<!-- footer_eof //-->
</body>
</html>
<?php require DIR_WS_INCLUDES . 'application_bottom.php'; ?>
