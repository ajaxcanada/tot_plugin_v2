<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function admin_action_callback() {
// HANDLES AJAX REQUESTS ON THE SERVER SIDE CALLED FROM JQ. 
// THE CALL IS THE SAME NAME + _callback
// SO JQ "action: 'admin_action'" IS SENT OUT BY AJAX
// THIS FUNCTION admin_action_callback HANDLES THE REQUEST ON SERVER

    check_ajax_referer('my-special-string', 'security');
    $table_name = filter_input(INPUT_POST, 'nav_button_selected', FILTER_SANITIZE_SPECIAL_CHARS);
    $main_command = filter_input(INPUT_POST, 'main_command', FILTER_SANITIZE_SPECIAL_CHARS);
    switch ($main_command) {
// MAIN | UPDATE INPUT
        case 'update_main_data':
            $js_return_value = update_main_data();
            break;
// MAIN | NAVIGATION STEP 1 - ECHO THE NEW GROUP DATA
        case 'get_main_navigation':
            $js_return_value = js_navigation($table_name);
            break;
// ADMIN | NAVIGATION STEP 2. ECHO THE GROUP RECORD DATA
        case 'get_record_data':
            $js_return_value .= view_db_table($table_name);
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
    }
    echo $js_return_value;
    die(); // this is required to return a proper result
}

// admin_action is called from jq
add_action('wp_ajax_admin_action', 'admin_action_callback');

function view_admin_form() {
    global $myMsg;
    global $wpdb;

    $out .= hide_header_on_this_page();
    $out .= "<html> <body>   <div id='admin-body'>";
    $out .= "<form name='admin' method='POST'>  <div id='admin-nav'>";
    $out .= create_admin_nav();
    $out .= "</div> </form>";

    $out .= "<form name='records' method='POST'>";
    $out .= "<span id='result'></span>";
    $out .= "<div id='admin-form-input-fields'>";
    //$out .= create_admin_nav();
    $out .= "</div> </form>";
    $out .= "</div> </body> </html>";


    return $out;
}

function view_db_table($table_name) {
    global $wpdb;

// NOW LOOK UP THE RECORDS
    $db_name = $wpdb->prefix . 'tot_' . $table_name; //$wpdb->prefix . record_DB; // load db records
    $query = "SELECT * FROM {$db_name} limit 1"; // records string to pass to mysql query
    $records = mysql_query($query) or die(mysql_error()); // get records from database
// check for no data record

    $num_rows = mysql_num_rows($records);
    if ($num_rows < 1) {
        //$out .= " number of rows is less than 1";
        $table_name = 'tot_' . $table_name;
        //$out .= $table_name;
        load_table_w_array($table_name, array('date_modified' => '', 'user_id' => $user_id));
        $records = mysql_query($query) or die(mysql_error()); // get records from database
    }

    while ($row = mysql_fetch_assoc($records)) {  // load the group_rows of fields data 
        foreach ($row as $fieldname => $fieldvalue) {
          //  $out .= "<span class='main-input-label'>$fieldname</span>";
            $out .= "<input class='admin' type='text' name='$fieldname' id='$fieldname' value='$fieldname'>";
//            $out .= "<input type='text' name='field_name' placeholder='field name' >";
//            $out .= "<input type='text' name='field_title' placeholder='field title' value=''>";
//            $out .= "<input type='text' name='field_type' placeholder='field type' value='text'>";
        }
         $out .= "<br>";
    }

    $out .= "<hr>";
    $x = "ADMIN>> <button class='add_field' value='add_field'>+</button>";
    $out .= "<span id='main-table-data-edit'>$x</span><br>";

    return $out;
}

function create_admin_nav($table_name) {
// GET THE NAVIGATON GROUPS (TABLES)
    global $wpdb;
    $db_table_name = $wpdb->prefix . "tot_db_groups"; // load fields records
    $query = "SELECT group_title FROM " . $db_table_name . " ORDER BY group_title";
    $results = mysql_query($query) or die(mysql_error()); // get fields from database
// LOAD THE NAV GROUPS;
    while ($db_row = mysql_fetch_assoc($results)) {
        foreach ($db_row as $col_name => $col_value) {
            if ($col_value == $table_name) {
                $out .= "<button class='group-button'>" . \strtoupper($table_name) . "</button>";
            } else {
                $out .= "<button class='js-navigator' id='$col_name' value='$col_value' >" . \strtoupper($col_value) . "</button>";
            }
        }
    }

    $out .= " ADMIN>> <button class='add-record' value='add-record'>+</button> <<"
            . "<button class='delete-record' value='delete-record'>x</button>"
            . "<span id='table-title'></span>";

    return $out;
}

//function view_db_table($table_name) {
//    $out .= "<form name='$db_records' method='POST'>$db_records";
//    $out .= "<div id='user_form'>";
//    $out .= "<div id='record-body'>";
//
//    $out .= "<div id='db_row'>";
//    $out .= "<input type='submit' name='UPDATE_RECORD' value='Save' disabled>";
//    $out .= "<input type='submit' name='DELETE_RECORD' value='Delete' disabled>";
//
//    $db_records = $wpdb->prefix . "tot_user"; // load db records
//    $query = "SELECT * FROM {$db_records}"; // records string to pass to mysql query
//    $records_results = mysql_query($query) or die(mysql_error()); // get records from database
//    while ($row = \mysql_fetch_assoc($records_results)) {
//        foreach ($row as $cname => $cvalue) {
//            switch ($cname) {
//                case "id": // looking for field id so we can output a hidden field
//                    break;
//                default: // looking for fields table headers
//                    $out_headers .= "<input type='text' value='" . $cname . "'>";
//                    break;
//            }
//        }
//        break;
//    }
//    $out .= $out_headers;
//    $out .= "</div>";
//
//    $row_number = 0;
//    while ($row = \mysql_fetch_assoc($records_results)) {
//        $out .= "<div id='db_row'>";
//        $out .= "<form name='form " . $row_number++ . "' method='POST'>";
//
//        $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field');
//        $out .= "<input type='hidden' name='records_form'>";  // so we know the name of the form
//        $out .= "<input type='submit' name='UPDATE_RECORD' value='Save'>";
//        $out .= "<input type='submit' name='DELETE_RECORD' value='Delete'>";
//        foreach ($row as $cname => $cvalue) {
//            // $out .= "$cname: $cvalue\t";
//            switch ($cname) {
//                case "id": // looking for field id so we can output a hidden field
//                    $out .= "<input type='hidden' name='" . $cname . "' value='" . $cvalue . "'>";
//                    break;
//                case "data_name": // check for a matching name						
//
//                    $out .= "<input type='text' name='" . $cname . "' value='" . $cvalue . "'>";
//                    break;
//                default: // looking for fields table headers
//                    $out .= "<input type='text' name='" . $cname . "' value='" . $cvalue . "'>";
//                    break;
//            }
//        }
//        $out .= "</form>";
//        $out .= "</div>"; // END OF db_row DIV
//    }
//    $out .= "<div id='db_row'>";
//    $out .= "<form name='new_record' method='POST'>";
//    $out .= "<input type='hidden' name='records_form'>"; // unique identifier for this form
//    $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field');
//    $out .= "<input type='submit' name='ADD_RECORD' value='new'>";
//    $out .= "</form>"; // END OF new_record FORM
//    $out .= "</div>";
//    $out .= "<br></div>"; // END OF record-body DIV
//
//
//    $out .= "</div></body></html> "; // </ end of our html
//    return $out;
//}
//
//// *************************************************************
//// create the groups table form
//if (!function_exists('view_db_groups_form_MERGE')) {
//
//    function view_db_groups_form() {
//        $out .= hide_header_on_this_page();
//
//        global $wpdb;
//        global $myMsg;
//        $db_groups = $wpdb->prefix . "tot_db_groups";
//        $query = "SELECT * FROM {$db_groups}";
//        $results = mysql_query($query) or die(mysql_error());
//
//        $out .= "<html>";
//        $out .= "<body>";
//        $out .= "<div id='data_set'>";
//
//        $out .= "<div id='db_message'>Modify the users records here. $myMsg</div>";
//
//        $out .= "<div id='record-body'>";
//
//        while ($row = mysql_fetch_assoc($results)) {
//            $out .= "<div id='db_row'><form name='form " . $row_number++ . "' method='POST'>";
//            $out .= "<input type='hidden' name='groups_form'>";  // so we know the name of the form
//            foreach ($row as $cname => $cvalue) {
//                //$out .= "$cname: $cvalue\t";
//                switch ($cname) {
//                    case "id": // looking for field id so we can output a hidden field
//                        $out .= "<input type='hidden' name='" . $cname . "' value='" . $cvalue . "'>";
//                        break;
//                    case "group_name": // looking for fields table headers
//                        $out .= $cname . "<input type='text' name='" . $cname . "' value='" . $cvalue . "'>";
//                        break;
//                }
//            }
//            $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field');
//            $out .= "<input type='submit' name='UPDATE_RECORD' value='Save'>";
//            $out .= "<input type='submit' name='DELETE_RECORD' value='Delete'>";
//            $out .= "</form></div>";
//        }
//        $out .= "<div id='db_row'><form name='new_record' method='POST'>";
//        $out .= "<input type='hidden' name='groups_form'>"; // unique identifier for this form
//        $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field');
//        $out .= "<input type='submit' name='ADD_RECORD' value='new'></form></div>";
//        $out .= "<br></div>";
//        $out .= "</div>"; // </ end of our div
//        $out .= "</body>"; // </ end of our body
//        $out .= "</html> "; // </ end of our html
//        return $out;
//    }
//
//}
//
//// *************************************************************
//// create the fields table form
//function view_db_fields_form_MERGE($db_select_items = "a") {
//
//    global $wpdb;
//    global $myMsg;
//    $db_records_table = $wpdb->prefix . "tot_db_records"; // records table name
//    $db_fields = $wpdb->prefix . "tot_db_fields"; // fields table name
//    $db_groups = $wpdb->prefix . "tot_db_groups"; // groups table name
//    $field_names_array = $wpdb->get_results("SELECT * FROM {$db_fields}"); // get the data out of the db
//    $num_rows = $wpdb->num_rows;  // get the number of rows in the data selected
//    $db_field_items = return_db_fields("fields"); // get names for the data in the table
//    $field_names = explode(",", $db_field_items); // breaks down names
//    $field_groups_array = $wpdb->get_results("SELECT * FROM {$db_groups}"); // load the field groups to poulate the options
//
//    $out .= hide_header_on_this_page();
//    $out .= "<html><body><div id='not_used'>";   // < Start of CREATION of the html web page
//    $out .= "<div id='record-body'>";
//
//    //CREATE THE HEADER ROW
//    $out_header .= "<div id='db-header'>"; // create the div so it styles nicely
//
//    for ($start = 0; $start < count($field_names); $start++) { // based on the number of field names cycle through data
//        $out_header .= "<input type='text' name='" . $field_names[$start];
//        $out_header .= " ' value='" . $field_names[$start] . "' readonly>";
//    }
//    $out_header .= "</div>"; // end of header div
//
//    $out .= $out_header;
//
//    for ($row_line = 0; $row_line < $num_rows; $row_line++) { // cycle through each row of data
//        $out .= "<div id='db_row'>"; // create the div so it styles nicely
//        $out .= "<form name='form" . $row_line . "' method='POST'>"; // create the form
//        $out .= "<input type='hidden' name='id' value='" . $field_names_array[$row_line]->{id} . "'>"; // hide the id
//        $out .= "<input type='hidden' name='fields_form'>"; // unique identifier for this form
//        $out .= "<input type='submit' name='UPDATE_RECORD' value='Save'>";
//        $out .= "<input type='submit' name='DELETE_RECORD' value='Delete'>";
//
//        for ($start = 0; $start < count($field_names); $start++) { // based on the number of field names cycle through data
//            $out .= "<input type='text' name='" . $field_names[$start];
//            $out .= " ' value='" . $field_names_array[$row_line]->$field_names[$start] . "'>";
//        }
//        $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field'); // wp nonce field for security
//        
//        $out .= "</form></div>";
//    } // end of rows
//    $out .= "<br></div>"; // END OF record-body DIV
//
//    $out .= "<div id='db_row'><form name='new_record' method='post'>"; // create a new form for the new record button
//    $out .= "<input type='hidden' name='fields_form'>"; // unique identifier for this form
//    $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field'); // wp nonce field for security
//
//    $out .= "<input type='submit' name='ADD_RECORD' value='NEW' ></form></div>"; // add new record button
//
//    $out .= "</div></body></html> "; // </ end of our html
//    return $out; // return
//}