<?php

function my_action_callback() {
// HANDLES AJAX REQUESTS ON THE SERVER SIDE CALLED FROM JQ. 
// THE CALL IS THE SAME NAME + _callback
// SO JQ "action: 'my_action'" IS SENT OUT BY AJAX
// THIS FUNCTION my_action_callback HANDLES THE REQUEST ON SERVER

    check_ajax_referer('my-special-string', 'security');
    $group = filter_input(INPUT_POST, 'nav_button_selected', FILTER_SANITIZE_SPECIAL_CHARS);
    $main_command = filter_input(INPUT_POST, 'main_command', FILTER_SANITIZE_SPECIAL_CHARS);
    switch ($main_command) {
// MAIN | UPDATE INPUT
        case 'update_main_data':
            $js_return_value = update_main_data();
            break;
// MAIN | NAVIGATION STEP 1 - ECHO THE NEW GROUP DATA
        case 'get_main_navigation':
            $js_return_value = js_navigation($group);
            break;
// MAIN | NAVIGATION STEP 2. ECHO THE GROUP RECORD DATA
        case 'get_record_data':
            $js_return_value .= create_record_data_fields($group);
            break;
// MAIN | delete file
        case 'delete_file':
            $js_return_value = ajax_delete_file($USER_FILE_NAME);
            break;
// MAIN | add file
        case 'add_new_table':
            $new_group_name = filter_input(INPUT_POST, 'new_name', FILTER_SANITIZE_SPECIAL_CHARS);
            $js_return_value = create_table($new_group_name);
            break;
// MAIN | add field tot table
        case 'add_new_field':
            $field_value = filter_input(INPUT_POST, 'field_value', FILTER_SANITIZE_SPECIAL_CHARS);
            $js_return_value = add_column_to_table($field_value);
            break;



// MANAGER | IF THIS IS AN INPUT UPDATE OFF MANAGER
        case 'update_field_data':
            $js_return_value = update_field_data();
            break;
// MANAGER | STEP 1 - ECHO THE NEW FIELD DATA
        case 'get_field_navigation':
            $js_return_value = create_field_naviation();
            break;
// MANAGER | STEP 2. ECHO THE GROUP FIELD DATA
        case 'get_field_data':
            $field_selection = filter_input(INPUT_POST, 'field_nav_button_selected', FILTER_SANITIZE_SPECIAL_CHARS);
            $out_to_find = "where field_group = '" . $field_selection . "' ";
            $js_return_value .= create_field_data_html($out_to_find);
            break;
    }
    echo $js_return_value;
    die(); // this is required to return a proper result
}

// my_action is called from jq
add_action('wp_ajax_my_action', 'my_action_callback');

// CALLED FROM JS TO DELETE AN FILE
function ajax_delete_file() {
    $file_name = filter_input(INPUT_POST, 'file_name', FILTER_SANITIZE_SPECIAL_CHARS);
    // THIS HAS A DEBUG COMMENT AT THE BOTTOM TO STOP IT FROM OUTPUTTING
    $id = get_current_user_id();
    // SETS THE PATH TO WHERE FILES ARE SAVED
    define('UPLOADS', 'members/' . $id);
    // TURNS OFF THE YEAR MONTH FOLDER 
    add_filter('option_uploads_use_yearmonth_folders', '__return_false', 100);

    // GETS THE CURRENT FOLDER AND OTHER STUFF NEEDED 
    $uploads = wp_upload_dir();
    $baseurl = $uploads['baseurl'];
    $basedir = $uploads['basedir'];
    $clean_file_name = str_replace($baseurl, "", $file_name);
    $new_file_name = $basedir . $clean_file_name;

    unlink($new_file_name);
    // GET A FRESH COPY OF THE DATA
    $out = get_user_folder_listing();
    return $out;
}

// CALLED FROM JS TO UPDATE THE MAIN PAGE DATA
function update_main_data() {
    global $wpdb;
    $user_id = get_current_user_id();

    $table_name = check_user_last_access();
    $field_name = filter_input(INPUT_POST, 'field_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $field_value = filter_input(INPUT_POST, 'field_value', FILTER_SANITIZE_SPECIAL_CHARS);
//    $table_name = filter_input(INPUT_POST, 'table_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $db_table = $wpdb->prefix . "tot_" . $table_name; // load db records

    $wpdb->query("UPDATE " . $db_table . " SET " . $field_name . " = '" . $field_value . "' WHERE user_id = '" . $user_id . "'");
    // SEND AND UPDATE MESSAGE. LANGUAGE...
    $out .= "update " . $field_value . " - > " . $field_name . " in table=" . $table_name . ":) ";
    return $out;
}

// CALLED FROM JS TO UPDATE THE FIELDS TABLE ON THE MANAGER PAGE
function update_field_data() {
    global $wpdb;
    $field_name = filter_input(INPUT_POST, 'field_name', FILTER_SANITIZE_SPECIAL_CHARS);
    $field_value = filter_input(INPUT_POST, 'field_value', FILTER_SANITIZE_SPECIAL_CHARS);
    $field_id = filter_input(INPUT_POST, 'field_id', FILTER_SANITIZE_SPECIAL_CHARS);
    $db_table = $wpdb->prefix . "tot_db_fields"; // load db records

    $wpdb->query("UPDATE " . $db_table . " SET " . $field_name . " = '" . $field_value . "' WHERE id = '" . $field_id . "'");
    // SEND AND UPDATE MESSAGE. LANGUAGE...
    $out .=$db_table . " " . $field_value . " - - > " . $field_name . " " . $field_id . ":) ";
    return $out;
}

function create_record_data_fields($group) {
// CREATE THE MAIN FORM DATA FOR THE GROUP SELECTED HERE
// also used by jquery to populate navigation div
    global $wpdb;
    global $myMsg;
// CREATE THE LIST OF FIELD NAMES FROM THE GROUP THAT WILL BE USED TO LOOK UP THE RECORD NAMES.
    //$db_fields_names = load_the_group_data($group);
    $user_id = get_current_user_id();

// NOW LOOK UP THE RECORDS
    $db_name = $wpdb->prefix . 'tot_' . $group; //$wpdb->prefix . record_DB; // load db records
    $query = "SELECT * FROM {$db_name} WHERE user_id={$user_id}"; // limit 1"; // records string to pass to mysql query
    $records = mysql_query($query) or die(mysql_error()); // get records from database
    //if $row>
    // check for no data record
    if ($user_id == 2) {
        $x = "ADMIN>> <input type='checkbox' name='edit_mode' value='allow_admin' />";
        $out .= $x . "<br>";
    };


    $num_rows = mysql_num_rows($records);
    if ($num_rows < 1) {
        //$out .= " number of rows is less than 1";
        $table_name = 'tot_' . $group;
        //$out .= $table_name;
        load_table_w_array($table_name, array('date_modified' => '', 'user_id' => $user_id));
        $records = mysql_query($query) or die(mysql_error()); // get records from database
    }

    while ($row = mysql_fetch_assoc($records)) {  // load the group_rows of fields data 
        foreach ($row as $fieldname => $fieldvalue) {
            switch ($fieldname) {
                case 'id':
                    $out .= "<input type='hidden' id='$fieldname' name='$fieldname' value='$fieldvalue'>";
                    break;
                case 'user_id':
                    break;
                case 'date_created':
                case 'date_modified':
                case 'group_selected':
                    break;
                case 'date_recorded':
                    break;
                default:
                    $out .= "<span class='main-input-label'>$fieldname</span>";
                    $out .= "<input type='text' name='$fieldname' id='$fieldname' value='$fieldvalue'>";
                    //$out .= "<br>";
                    if ($user_id == 2) {
                        $allow_admin = filter_input(INPUT_POST, 'admin', FILTER_SANITIZE_SPECIAL_CHARS);
                        if ($allow_admin == 'allow_admin'){
                        $out .= add_admin_commands();}
                    }
                    $out .= "<br>";
            }
        }

        $out .= "<hr>";
    }


    if ($user_id == 2) {
        $x = "ADMIN>> <button class='add_field' value='add_field'>+</button>";
        $out .= "<span id='main-table-data-edit'>$x</span><br>";
    };

    return $out;
}

function add_admin_commands() {
    $out .= "<input type='text' name='field_name' placeholder='field name' >";
    $out .= "<input type='text' name='field_title' placeholder='field title' value=''>";
    $out .= "<input type='text' name='field_type' placeholder='field type' value='text'>";


    return $out;
}

function js_navigation($group) {
// 1 - pull the groups for navigation and 
// 2 - set the new navigation group
    global $wpdb;  // wordpress database connection
    $user_id = get_current_user_id();
    $db_table_name = $wpdb->prefix . "tot_db_groups"; // load fields records
    $query = "SELECT group_title FROM " . $db_table_name . " ORDER BY group_title";

    //if ($wpdb->get_var("show tables like '$db_table'") != $db_table) {}
    //if ($wpdb->get_var("show tables like '$db_table'") != $db_table) {}

    $results = mysql_query($query) or die(mysql_error()); // get fields from database
// LOAD THE NAV GROUPS;
    while ($db_row = mysql_fetch_assoc($results)) {
        foreach ($db_row as $col_name => $col_value) {
            if ($col_value == $group) {
                $out .= "<button class='group-button'>" . \strtoupper($group) . "</button>";
            } else {
                $out .= "<button class='js-navigator' id='$col_name' value='$col_value' >" . \strtoupper($col_value) . "</button>";
            }
        }
    }



// DISPLAY THE EDIT BUTTONS    
    if ($user_id == 2) {
        $out .= "ADMIN>> <button class='add-record' value='add-record'>+</button>"
                . "<button class='delete-record' value='delete-record'>x</button>"
                . "<span id='table-title'></span>";
    }
// SET THE NEW GROUP FOR THE USER IN USER_INFORMATION
    $db_table = $wpdb->prefix . "tot_user";
    $wpdb->query("UPDATE $db_table SET group_selected = '$group' WHERE user_id = '$user_id'");
    return $out;
}
