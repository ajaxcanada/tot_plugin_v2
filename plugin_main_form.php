<?php

// *************************************************************
// CREATE THE MAIN FORM HERE
function view_user_main_form() 
{
    // NEED THIS FOR JQUERY TO WORK
    echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>';
    $start = microtime(TRUE);  // starts a microtimer called start
    // OUTPUT PAGE HTML     
    $out .= "<html><body>"; // start of the html
    // MAIN_PAGE ==================================
    $out .= "<div id='div_outline_on_all_forms'>";
    // GET USER ID OR EXIT TO WELCOME
    $cuid = check_user_id();
    // GET THE ACTIVE GROUP
    $group = check_user_last_access($cuid);
    // DISPLAY ANY MESSAGES
    $out .= create_message_area(); // MESSAGE CODE
    // SET THE DEFAULT NAVIGATION
    $out .= create_navigation($group); // NAVIGATION CODE
    // CREATE THE MAIN AREA OF THE FORM
    $out .= create_main_area($cuid, $group); // MAIN CODE
    // CREATE FOOTER AREA
    $out .= create_main_footer($start); // FOOTER CODE
    // END THE PAGE DIV        
    $out .= "</div> "; // </ END OF MAIN FORM 
    // FINISH THE HTML
    $out .= "</body></html> "; // </ end of our html
    // SEND EVERYTHING BACK TO MAIN
    return $out;
}


// *************************************************************
// CREATE THE MAIN FORM HERE
function create_main_area($user_id, $user_group_selected) {
    console.log( 'creating main form');
    global $wpdb;  // wordpress database connection
    // GET THE FIELD TITLES FOR THE GROUPS
    $titles = load_the_group_names($user_group_selected);
    $db_fields_names = load_the_group_data($user_group_selected);

    $db_records = $wpdb->prefix . "tot_db_records"; // load db records
    $query_records = "SELECT " . $db_fields_names . " FROM {$db_records} WHERE user_id={$user_id}"; // records string to pass to mysql query
    $records_results = mysql_query($query_records) or die(mysql_error()); // get records from database
    // DIV_MAIN_FORM ============================
    $out .= "<div id='TOT_MAIN_USER_FORM'>";
    $out .= "<form name='main_form_data' id='main_form_data' method='' action=''>";
    $out .= "<hr>";

    $out .= "<input type='hidden' name='main_form'>"; // unique identifier for this form
    $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field'); // SECURITY
    //$out .= $user_group_selected.'<br>';
    // OUTPUT THE DATA ==========================

    //$r_count = 0;
    $out .= $titles;
    while ($row = mysql_fetch_assoc($records_results)) {  // load the group_rows of fields data 
        $out .= "<div id='main_form_input_fields'>";

        foreach ($row as $fieldname => $fieldvalue) {
            switch ($fieldname) {
                case 'id':
                case 'user_id':
                case 'date_recorded':
                    $out .= "<input type='hidden' name='$fieldname' value='$fieldvalue'>";
                    break;
                default:
                    //$out .= "<label for='$fieldname'>$fieldname</label>";
                    $out .= "<input type='text' name='$fieldname' id='$fieldname' value='$fieldvalue'>"; // capture the new record name
            }
            // $out .=  $fieldvalue ; // output column to screen
            // if($count++ == 8){break;}
        }
        $out .= "</div><hr>";
    }
    //$out .= "First name: <input type='text' id='txt1' onkeyup='showHint(this.value)' />";
    $out .= "<div id='js_enabled_hide_buttons'>";
    //$out .= "<input type='submit' name='TEST_JQ' value='update record'>";
    $out .= "<input type='submit' name='UPDATE_MAIN_RECORD' value='update record'>";
    $out .= "<input type='submit' name='DELETE_MAIN_RECORD' value='delete record'>";
    $out .= "</div>";
    $out .= "<input type='submit' name='record' id='sub' value='update_main_record' />";
    $out .= "<input type='submit' name='fields' id='sub' value='update_main_fields' />";
    //$out .= "<input type='submit' name='sub_1' id='sub_1' value='jq test 1' />";
    //$out .= "<button name='sub' id='sub'>save in jq</button>";
    //$out .= "<div id='results'></span>";
    $out .= "<br><span id='result'></span>";
    
    $out .= "</form>"; // End the form 
    $out .= "</div>";  // end DIV_MAIN_FORM  ======================================
    return $out;
}

// LOAD THE FIELDS TITLES INFO FOR THE GROUP REQUESTED BY THE USER
function load_the_group_names($user_group_selected) {
    global $wpdb;  // wordpress database connection
    // FIRST LOAD THE FIELD TABLE BASED ON THE USER SELECTION OR DEFAULT
    $db_fields = $wpdb->prefix . "tot_db_fields";
    $query_field_titles = "SELECT field_title FROM {$db_fields} where field_group = '" . $user_group_selected . "'";
    $field_titles = mysql_query($query_field_titles) or die(mysql_error()); // get fields from database
    //$db_field_name .= "`id`, `user id`, ";
    while ($fieldrow = mysql_fetch_assoc($field_titles)) {  // load the group_rows of fields data 
        foreach ($fieldrow as $field_name => $field_value) {
            $db_field_name .= "<input type='text' class='header_row' readonly value='$field_value'>";
        }
    }
    //$db_field_name .= "`date recorded`";
    return $db_field_name;
}

// LOAD THE FIELDS TABLE INFO FOR THE GROUP REQUESTED BY THE USER
function load_the_group_data($user_group_selected) {
    global $wpdb;  // wordpress database connection
    // FIRST LOAD THE FIELD TABLE BASED ON THE USER SELECTION OR DEFAULT
    $db_fields = $wpdb->prefix . "tot_db_fields";

    $query_fields = "SELECT field_name FROM {$db_fields} where field_group = '" . $user_group_selected . "'";
    $fields_results = mysql_query($query_fields) or die(mysql_error()); // get fields from database

    $num_cols = mysql_num_fields($fields_results);
    $num_rows = mysql_num_rows($fields_results);
    $values = array();

    // INIT ARRAY
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
    while ($fieldrow = mysql_fetch_assoc($fields_results)) {  // load the group_rows of fields data 
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

// =======================================================================
// END OF MAIN AREA
// =======================================================================
// =======================================================================
// USER MESSAGE AREA
// =======================================================================
function create_message_area() {
    global $myMsg;
    $out .= "<div id='message'>";
    if (isset($myMsg)) {
        $out .= "Debug message=" . $myMsg . "<br>";
    }
    $out .= "</div>";
    return $out;
}

// =======================================================================
// FOOTER AREA
// =======================================================================
function create_main_footer($start) {
    // DIV_FOOTER	
    $out .= "<div id='TOT_FOOTER'>";
    $out .= "<div id='my_timer'>&nbsp;Elapsed time=";
    $out .= page_timer($start); //    
    $out .= "</div>";
    $out .= "</div>";

    return $out;
}

// =======================================================================
// NAVIGATION AREA
// =======================================================================
function create_navigation($group) {
    global $wpdb;  // wordpress database connection
    // LOAD GROUPS DATA;
    $db_groups = $wpdb->prefix . "tot_db_groups"; // load fields records
    $query_groups = "SELECT * FROM {$db_groups}"; //fields string to pass to mysql query
    $groups_results = mysql_query($query_groups) or die(mysql_error()); // get groups from database

    $out .= "<div id='TOT_NAV'>";
    $out .= "<form name='navigation' method='POST'>"; // Form - new_record
    $out .= "<input type='hidden' name='navigation'>"; // unique identifier for this form
    $out .= "<input type='hidden' name='navigation'>"; // unique identifier for this form
    $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field'); // SECURITY
    $out .= "<button class='groupButton'>" . \strtoupper($group) . "</button>";
    // load the group data 
    while ($db_row = mysql_fetch_assoc($groups_results)) {
        foreach ($db_row as $col_name => $col_value) {
            if ($col_name != 'id' and $col_value != $group) {
                $out .= "<input type='submit' name='navigator' value='$col_value'>";
            }
        }
    }


    $out .= "</form>"; // End the form 
    $out .= "</div>";
    return $out;
}

// CAN BE USED TWO WAYS. 
// CHECK VALID USER WHEN SHORTCODES ARE REQUESTED
// OR IT CAN RETURN THE USER ID
function check_user_id() {
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

function check_user_last_access($user_id) {
    global $wpdb;  // wordpress database connection

    $db_records = $wpdb->prefix . "tot_db_records"; // load records
    $query_records = "SELECT `group_selected` FROM {$db_records} where user_id = '$user_id'"; // add where id = userid 
    $record_results = mysql_query($query_records) or die(mysql_error()); // get group_selected from database

    while ($db_row = mysql_fetch_assoc($record_results)) {
        foreach ($db_row as $col_name => $col_value) {
            $ret = $col_value;
        }
    }

    if ($col_value == "") {
        // nothing setup for this user, adding a new entry now
        load_table_w_array('tot_db_records', array('group_selected' => 'user information', 'user_id' => $user_id));
        $ret .= "user information";
    }
    return $ret;
}

function page_timer($start) {
    $finish = microtime(TRUE);
    $totaltime = $finish - $start;
    return $totaltime;
}
?>