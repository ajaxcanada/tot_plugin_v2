<?php

// *************************************************************
// create the record cell with fields table atributes
// *************************************************************
// 1 manage-form
// 2 message
// 3 send_SMS
// 4 new_record_entry
// 5 fields-table
// 6 DIV_ROW
// 7 footer_info
// =================================================	

function record_form() {
    $out .= create_top_html();    // my record form
    // RECORD FIELDS HERE
    $out .= create_group_form_html();
    $out .= create_record_form_html();    // OUTPUT THE RECORDS
    //$out .= send_SMS_Message_thru_twilio();
    $out .= "<div id=field-selector>";
    // CREATE NAVIGATION AREA
    $out .= create_field_naviation();
    // END OF THE DIV
    $out .= "</div>"; // end of div
    // NEW DIV FOR FIRLD DATA
    $out .= "<div id='field-table' class='field-table'>"; 
    $out .= create_field_data_html();
    $out .= "</div>"; /// 5 end div
    // CREATE THE BOTTOM OF THE FORM
    $out .= create_record_bottom_html();
    return $out;
}

// CREATE THE TOP HTML OF THE MANAGER PAGE
function create_top_html() {
    global $myMsg; // messages used by TOT. these are at the top of the page. 
    $out .= hide_header_on_this_page();
    $out .= "<html><body>"; // start of the html
    $out .= "<div id='manage-form'>this is teh new div for the groups"
            . ""; // D I V 1 - the entire form so we can easily add style scroll
    $out .= "<div id='message'>"; // START  OF MESSAGE_DIV
    $out .= $myMsg . "Groups are converted and stored as tables";
    $out .= "</div>"; // END OF MESSAGE_DIV

    return $out;
}
// CREATE THE GROUPS FIRST
function create_group_form_html() {
    $out .= "<div id='group-form'>"; // 4 MAIN_RECORD_DIV
    $out .= "<form name='group_form' method='POST'>"; // Form - new_record
    $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field'); // SECURITY
    
    $out .= "</form>"; // end of form
    $out .= "</div>"; // end of SMS_MESSAGE
    return $out;
}

// CREATE THE RECORD FORM AT THE TOP OF THE PAGE
function create_record_form_html() {
    $out .= "<div id='new_record_entry'>"; // 4 MAIN_RECORD_DIV
    $out .= create_message_area(); // MESSAGE CODE
    $out .= "<form name='new_record_field' method='POST'>"; // Form - new_record
    $out .= "<input type='hidden' name='record_field_form'>"; // unique identifier for this form
    $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field'); // SECURITY
    // create INPUT for new record name
    $out .= "<label for='new_record_name_input'>field name:</label>";
    $out .= "<input type='text' name='new_record_name_input' placeholder='new record name'>"; // capture the new record name
    // CREATE BUTTONS
    $out .= "<input type='submit' name='ADD_RECORD_COLUMN' value='add new column'>"; // the add new button
    $out .= "<input type='submit' name='DELETE_RECORD_COLUMN' value='Delete column'><br>"; // the add new button
    // create INPUT for a new group
    $out .= "<label for='new_record_group'>field group:</label>";
    $out .= "<input type='text' name='new_record_group' id='new_record_group' placeholder='new group name'></label>"; // capture the new record group
    $out .= "</form>"; // end of form
    $out .= "<hr>";
    $out .= "</div>"; // end of SMS_MESSAGE
    return $out;
}

// CREATE THE NAVIGATION SELECTOR BUTTONS
function create_field_naviation($fields_to_select) {
    global $wpdb;  // wordpress database connection
    $db_fields = $wpdb->prefix . "tot_db_fields"; // load fields records
    //    $wpdb->query("UPDATE " . $db_table . " SET " . $field_name . " = '" . $field_value . "' WHERE user_id = '" . $user_id . "'");
    $query_fields = "SELECT DISTINCT field_group FROM " . $db_fields . " ";
    $fields_results = mysql_query($query_fields) or die(mysql_error()); // get fields from database

    while ($fieldrow = mysql_fetch_assoc($fields_results)) {  // load the group_rows of fields data 
        foreach ($fieldrow as $field_name => $field_value) {
            if ('field_group' == $field_name) {
                $out .= "<button value='$field_value'>{$field_value}</button>";
            }
        }
    }
    return $out;
}

// CREATE THE FIELD DATA
// ALSO USED TO SERVE JS DATA
function create_field_data_html($fields_to_select) {
// DATABASE INTERFACE INFORMATION
    global $wpdb;  // wordpress database connection
    $db_fields = $wpdb->prefix . "tot_db_fields"; // load fields records
    //  SHOULD ADD A CHECK FOR THE FIELDS_TO_SELECT  
    $query_fields = 'SELECT * FROM ' . $db_fields . ' ' . $fields_to_select; // .  $column_name_string . ' ' . $group_selected_string.''; //fields string to pass to mysql query
    $fields_results = mysql_query($query_fields) or die(mysql_error()); // get fields from database
// INITILIZE THE ARRAYS
    $num_cols = mysql_num_fields($fields_results);
    $num_rows = mysql_num_rows($fields_results);
    $values = array();
    $headers = array();
// DIMENSION THE ARRAYS
    for ($c = 1; $c <= $num_cols; $c++) {
        for ($r = 1; $r <= $num_rows; $r++) {
            $values['col_' . $c][$r] = array();
        }
    } for ($c = 1; $c <=
            $num_cols; $c++) {
        $headers['col_' . $c][1] = array();
    }
// LOAD THE DATA INTO THE ARRAY
    $c = 1;
    $r = 1;
    while ($fieldrow = mysql_fetch_assoc($fields_results)) {  // load the group_rows of fields data 
        $c = 1;
        foreach ($fieldrow as $field_name => $field_value) {
            // create the headers
            if ($r == 1) {
                $headers['col' . $c][$r] = $field_name;
            }$values['col' . $c][$r] = $field_value;
            $c++;
        }$r++;
    }
// PRINT OUT TABLE USING THE ARRAY DATA
    for ($c = 1; $c <= $num_cols; $c++) {
        $out .= "<div id='row-table'>";
        $out .= "<input style='color: #000; font-weight: bold' readonly value='" . $headers['col' . $c][1] . "'>";
        for ($r = 1; $r <= $num_rows; $r++) {
            // this creates form names and id's that link to the records
            $out .= "<input name='" . $headers['col' . $c][1] . "' id='" . $values['col' . 1][$r] . "' value='" . $values['col' . $c][$r] . "'>";
        }
        $out .= "</div>";
    }
// DESTROY ARRAYS 
    unset($headers);
    unset($values);

    return $out;
}

// CREATE THE BOTTOM OF THE MANAGER PAGE
function create_record_bottom_html() {
    $out .= "<div id='footer_info'>"; // D I V information
    $out .= "</div>"; // end / D I V information
    $out .= "</div>"; // </ end of 1
    $out .= "</body>"; // </ end of our html
    $out .= "</html>"; // </ end of our html
    return $out;
}

// OPTION FOR TESTING THIS USING THE TWILIO INTERFACE
function send_SMS_Message_thru_twilio() {
    // my sms code
    // =================================================
    $out .= "<div id='send_SMS'>"; // SMS_MESSAGE_DIV
    $out .= "<form action='' name='send_sms' method='post'>"; // Form - new_record
    $out .= "S end someone a t ext message<br />";

    $out .= "<label for='phone'>Phone Number:</label>";
    $out .= "<input type='text' name='phone' placeholder='XXX XXX XXXX'>"; // capture the new record name
    $out .= "<label for='name'>Users Name:</label>";
    $out .= "<input type='text' name='sms_user_name' placeholder='enter the name'> "; // capture the  new record name
    $out .= "<label for='message'>Message to send:</label>";
    $out .= "<input type='text' name='message' placeholder='enter your message'>";  // capture the new record name

    $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field'); // SECURITY
    $out .= "<input type='submit' name='send_sms_message' value='send message'>"; // the add new button
    $out .= "</form>"; // end of form
    $out .= "</div>"; // end of SMS_MESSAGE
    return $out;
}

?>