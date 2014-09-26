<?php

function view_user_main_form() {
// START OF PAGE PAGE DIV main-form-outline
    $user_id=check_user_id();
    $out .= "<html><body>" . "<div id='main-form-outline'>";

// GET OR SET THE ACTIVE GROUP
    $group = check_user_last_access();

// DISPLAY ANY MESSAGE
    $out .= create_message_area();

// MAIN NAVIGATION
    $out .= "<form name='navigation' method='post'>";
    $out .= "<div id='TOT_NAV'>";
    $out .= js_navigation($group);
    $out .= "</div>";
    $out .= "</form>";

//  MAIN AREA 
    $out .= "<div id='TOT_MAIN_USER_FORM'>";

//  MAIN DATA TABLE
    $out .= "<form name='main_form_data' id='main_form_data' method='post'>";
    $out .= "<div id='main_form_input_fields'>";
    $out .= create_record_data_fields($group);
    $out .= "</div><hr>";
    $out .= "</form>";

// CREATE ICON AREA
    $out .= "<div id='main-icon-area-style'>";
    $out .= create_icon_area($which_icons);
    $out .= "</div>";

// USER FOLDER LISTING 
    $out .= "<form name='USER_UPLOAD_FORM' id='main_form_data' method='post'>";
    $out .= "<div id='USER_UPLOAD_AREA_DIV'>";
    $out .=get_user_folder_listing();
    $out .= "</div>";
    $out .= "</form>";

// UPLOAD INPUT AND BUTTON 
    $out .= "<form id='upload' name='upload'  method='post' enctype='multipart/form-data'>";
    $out .= "<div id='upload-form' name='upload-form'>";
    $out .= create_upload_area();
    $out .= "</div>";
    $out .= "</form>";

// END THE FORM DIV
    $out .= "</div>";

// CREATE FOOTER AREA
    $out .= create_main_footer();

// FINISH THE HTML
    $out .= "</div> ";
    $out .= "</body></html> ";

    return $out;
}

function create_message_area() {
// USER MESSAGE AREA
    global $myMsg;
    $out .= "<div id='message'>";
    $out .= "|AD: GOES HERE|";
    if (isset($myMsg)) {
        $out .= "" . $myMsg . "";
    }
    $out .= "<span id='result'></span>";
    $out .= "</div>";
    return $out;
}

// CREATE THE NAVIGATION AREA
//     js-navigation($group)

function create_upload_area() {
// ======================================
// code for the upload 
// ======================================

    $out .= "<input type='file' id='async-upload' name='tot_attachments[]' placeholder='select a file' multiple='multiple' />";
    $out .= "<input type='hidden' name='postID' value='0' />";
    $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field'); // SECURITY
    $out .= "<input type='submit' id='submit' name='UPLOAD_BUTTON' value='Upload' />";
    return $out;
}

function create_icon_area($which_icons) {
    // USER ICONS TO 
    $out .= "<input type='image' id='update' SRC='http://www.toolsontools.com/wp-content/uploads/2014/09/wp_save_icons.png' "
            . "name='update' class='INPUT_MAIN_ICON' ALT='update record'>";
    $out .= "<input type='image' id='add' SRC='http://www.toolsontools.com/wp-content/uploads/2014/09/wp_add_icon.png' "
            . "name='add' class='INPUT_MAIN_ICON ALT='add record'>";
    $out .= "<input type='image' id='delete' SRC='http://www.toolsontools.com/wp-content/uploads/2014/09/wp_delete_icon.png' "
            . "name='delete' class='INPUT_MAIN_ICON' ALT='delete record'>";
    $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field');
    return $out;
}

function get_user_folder_listing() {
// ======================================
//  RETURNS NAMES OF FILES IN USERS FOLDER
// ======================================
    $id = get_current_user_id();
    // SETS THE PATH TO WHERE FILES ARE SAVED
    define('UPLOADS', 'members/' . $id);
    // TURNS OFF THE DATE IN FOLDER FEATURE USED BY MEDIA LIBRARY
    add_filter('option_uploads_use_yearmonth_folders', '__return_false', 100);
    // gets the uploads folder
    $uploads = wp_upload_dir();

    if ($dir = opendir($uploads['basedir'])) {
        while (false !== ($file = readdir($dir))) {
            if ($file != "." && $file != "..") {
                // OUTPUT THE HTML FOR THE IMAGE AND TEXT;
                $dir_list .=
                        "<img src='" . $uploads['url'] . "/"
                        . $file
                        . "'  ALT='" . $file
                        . "' WIDTH=32 HEIGHT=32 >";
                // CREATE THE TEXT
                // $dir_list .= $file;
            }
        }
        closedir($dir);
    }
    $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field'); // SECURITY
    // SEND OUT THE DIRECOTRY LIST
    $out .= $dir_list;
    return $out;
}

function create_main_footer() {
// =======================================================================
// FOOTER AREA
// =======================================================================    	
    $viewer = getenv("HTTP_USER_AGENT");
    $browser = "An unidentified browser";
    if (preg_match("/MSIE/i", "$viewer")) {
        $browser = "Internet Explorer";
    } else if (preg_match("/Netscape/i", "$viewer")) {
        $browser = "Netscape";
    } else if (preg_match("/Mozilla/i", "$viewer")) {
        $browser = "Mozilla";
    }
    $platform = "An unidentified OS!";
    if (preg_match("/Windows/i", "$viewer")) {
        $platform = "Windows!";
    } else if (preg_match("/Linux/i", "$viewer")) {
        $platform = "Linux or Android or Apple";
    }
    $out .= "<div id='TOT_FOOTER'>";
    $out .= ("Your browser: $browser OS: $platform");
    $out .= "<div id = 'copyright'><footer area> ToolOnTools Copyright &#169; 2014</div>";
    $out .= "</div>";

    return $out;
}

// SUBROUTINES OF OTHER FUNCTIONS
function load_the_group_names($group) {
// LOAD THE FIELDS TITLES INFO FOR THE GROUP REQUESTED BY THE USER
    global $wpdb;  // wordpress database connection
    // FIRST LOAD THE FIELD TABLE BASED ON THE USER SELECTION OR DEFAULT
    $db_fields = $wpdb->prefix . "tot_db_fields";
    $query_field_titles = "SELECT field_title FROM {$db_fields} where field_group = '" . $group . "'";
    $field_titles = mysql_query($query_field_titles) or die(mysql_error()); // get fields from database
    //$db_field_name .= "`id`, `user id`, ";
    while ($fieldrow = mysql_fetch_assoc($field_titles)) {  // load the group_rows of fields data 
        foreach ($fieldrow as $field_name => $field_value) {
            $db_field_name .= "<div class='header_row'>$field_value</div>";
            //$db_field_name .= "<input type='text' class='header_row' readonly value='$field_value'>";
        }
    }

    //$db_field_name .= "`date recorded`";
    return $db_field_name;
}

function load_the_group_data($group) {
// LOAD THE FIELDS TABLE INFO FOR THE GROUP REQUESTED BY THE USER
// THIS IS A BIT COMPLICATED BECASE I 
// 1-PUT THE DATA INTO AN ARRAY SO I COULD 
// 2-DISPLAY IT VERTICALLY OR HORIZONTALLY.    
// FIRST LOAD THE FIELD TABLE BASED ON THE USER SELECTION OR DEFAULT
    global $myMsg;
    $myMsg .= 'load_the_group_data';
    global $wpdb;
    $db_fields = $wpdb->prefix . "tot_db_fields";
    $query_fields = "SELECT field_name FROM {$db_fields} where field_group = '" . $group . "'";
    $fields_results = mysql_query($query_fields) or die(mysql_error()); // get fields from database

    $num_cols = mysql_num_fields($fields_results);
    $num_rows = mysql_num_rows($fields_results);
    // SETUP AND DIMENSION THE ARRAY
    $values = array();
    for ($c = 1; $c <= $num_cols; $c++) {
        for ($r = 1; $r <= $num_rows; $r++) {
            $values['col_' . $c][$r] = array();
            $headers['col_' . $c][1] = array();
        }
    }
    // INIT VARIABLES
    $c = 1;
    $r = 1;
    // LOAD THE FIELDNAMES INTO THE ARRAY
    while ($fieldrow = mysql_fetch_assoc($fields_results)) {
        $c = 1; // reset back to column 1
        foreach ($fieldrow as $field_name => $field_value) {
            $values['col' . $c][$r] = $field_value;
            $c++;
        }
        $r++;
    }
    //ADD ID AND USER_ID FIELDS TO THE SEARCH STRING
    $db_fields_names .= "`id`, `user_id`, ";
    // USE THE ARRAY DATA TO CREATE A SEARCH STRING
    for ($r = 1; $r <= $num_rows; $r++) {
        for ($c = 1; $c <= $num_cols; $c++) {
            $db_fields_names .= "`" . $values['col' . $c][$r] . "`, ";
        }
    }
    // ADD date_created AND date_modified ONTO THE END OF THE STRING
    $db_fields_names .= "`date_recorded`";
    return $db_fields_names;
}

function check_user_id() {
// =======================================================================
// MY CHECK USER ROUTINE - WP FUNCTIONS ARE AVAILABLE FOR THIS
// CAN BE USED TWO WAYS. CHECK VALID USER WHEN SHORTCODES ARE REQUESTED
// OR IT CAN RETURN THE USER ID

    hide_header_on_this_page();

    if (!is_user_logged_in()) {
        echo unregistered_user_welcome_message;
        exit;
        //no user logged in
    } else {
        $cuid = get_current_user_id();
    }
    return $cuid;
}

function check_user_last_access() {
// =============================================================================
// VALIDATE THE WP USER_ID. 
// FOR PRODUCTION WE NEED TO 
// ADD AN CUSTOM ENCRYPTED USER ID IN SHARED DATA MODEL
// =============================================================================
    global $wpdb;  // wordpress database connection
    $user_id = get_current_user_id();
    $table_name = $wpdb->prefix . "tot_user"; // load records
    $query = "SELECT `group_selected` FROM {$table_name} where user_id = '$user_id'"; // add where id = userid 
    $record_results = mysql_query($query) or die(mysql_error()); // get group selected from database

    while ($db_row = mysql_fetch_assoc($record_results)) {
        foreach ($db_row as $col_name => $col_value) {
            $ret = $col_value;
        }
    }

    if ($col_value == "") {
        // nothing setup for this user, adding a new entry now
        load_table_w_array('tot_user', array(
            'group_selected' => 'user',
            'user_id' => $user_id));
        $ret .= "user";
    }
    return $ret;
}

?>