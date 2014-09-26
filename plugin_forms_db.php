<?php
// *************************************************************
// short code to show a record. this runs when the form is first opened. 
// unwritten RULE; keep this in same file as the function
add_shortcode('ajaxshortcode', 'admin_page');

function admin_page($atts) {
    //$users_wp_user_id = $current_user->ID;
    // fetch shortcodes user_id and TYPE of form (type not implimented)
    $shortcode_attributes = shortcode_atts(array(
        'what' => 'form_name',
            ), $atts);

    $db_select_items = $shortcode_attributes['what'];
    // a switch to tell what form to load
    switch ($db_select_items) {
        case 'fields':
            echo view_db_fields_form();
            break;
        case 'groups':
            echo view_db_groups_form();
            break;
        case 'records':
            echo view_db_records_form();
            break;
        case 'add_record_field': // THIS IS IN plugin_code_forms.php
            echo record_form();
            break;
        case 'main':
            echo view_user_main_form();
            break;
        case 'admin':
            echo view_admin_form();
            break;
        case "login":
            login_form();
            break;
    }
}

function login_form() {
    if (!is_user_logged_in()) { // Display WordPress login form:
        $args = array(
            'redirect' => admin_url(),
            'form_id' => 'loginform-custom',
            'label_username' => __('Your ToolsOnTools Username'),
            'label_password' => __('Password'),
            'label_remember' => __('Remember Me custom text'),
            'label_log_in' => __('Log In to ToolsOnTools'),
            'remember' => true
        );
        wp_login_form($args);
    } else { // If logged in:
        echo " << you are already logged in " . wp_loginout(home_url()); // Display "Log Out" link.
        echo " | ";
        wp_register('', ''); // Display "Site Admin" link.
    }
}

// *************************************************************
// create the records table form
function view_db_records_form() {
    $start = microtime(TRUE);  // starts a microtimer called start

    global $myMsg;
    global $wpdb;
    $db_records = $wpdb->prefix . "tot_db_records"; // load db records
    $query = "SELECT * FROM {$db_records}"; // records string to pass to mysql query
    $records_results = mysql_query($query) or die(mysql_error()); // get records from database

    $out .= hide_header_on_this_page();
    $out .= "<html><body>";
    $out .= "<div id='data_set'>";
    $out .= "<form name='new_record' method='POST'>";
    $out .= "<div id='db_message'>Modify the users records here";
    $out .= "<br>$myMsg</div>";

    $out .= "<div id='record-body'>";

    $out .= "<div id='db_row'>";
    $out .= "<input type='submit' name='UPDATE_RECORD' value='Save' disabled>";
    $out .= "<input type='submit' name='DELETE_RECORD' value='Delete' disabled>";

    while ($row = \mysql_fetch_assoc($records_results)) {
        foreach ($row as $cname => $cvalue) {
            switch ($cname) {
                case "id": // looking for field id so we can output a hidden field
                    break;
                default: // looking for fields table headers
                    $out_headers .= "<input type='text' value='" . $cname . "'>";
                    break;
            }
        }
        break;
    }
    $out .= $out_headers;
    $out .= "</div>";

    $row_number = 0;
    while ($row = \mysql_fetch_assoc($records_results)) {
        $out .= "<div id='db_row'>";
        $out .= "<form name='form " . $row_number++ . "' method='POST'>";

        $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field');
        $out .= "<input type='hidden' name='records_form'>";  // so we know the name of the form
        $out .= "<input type='submit' name='UPDATE_RECORD' value='Save'>";
        $out .= "<input type='submit' name='DELETE_RECORD' value='Delete'>";
        foreach ($row as $cname => $cvalue) {
            // $out .= "$cname: $cvalue\t";
            switch ($cname) {
                case "id": // looking for field id so we can output a hidden field
                    $out .= "<input type='hidden' name='" . $cname . "' value='" . $cvalue . "'>";
                    break;
                case "data_name": // check for a matching name						

                    $out .= "<input type='text' name='" . $cname . "' value='" . $cvalue . "'>";
                    break;
                default: // looking for fields table headers
                    $out .= "<input type='text' name='" . $cname . "' value='" . $cvalue . "'>";
                    break;
            }
        }
        $out .= "</form>";
        $out .= "</div>"; // END OF db_row DIV
    }
    $out .= "<div id='db_row'>";
    $out .= "<form name='new_record' method='POST'>";
    $out .= "<input type='hidden' name='records_form'>"; // unique identifier for this form
    $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field');
    $out .= "<input type='submit' name='ADD_RECORD' value='new'>";
    $out .= "</form>"; // END OF new_record FORM
    $out .= "</div>";
    $out .= "<br></div>"; // END OF record-body DIV

    $finish = microtime(TRUE);
    $t = time();
    $totaltime = $finish - $start;
    $out .= " Date " . (date("Y/m/d", $t)) . "  <span id='my_timer'>    Elapsed time=";
    $out .= $totaltime . "</span>    CREATE A NEW RECORD=> ";

    $out .= "</div></body></html> "; // </ end of our html
    return $out;
}

// *************************************************************
// create the groups table form
if (!function_exists('view_db_groups_form')) {

    function view_db_groups_form() {
        $out .= hide_header_on_this_page();

        global $wpdb;
        global $myMsg;
        $db_groups = $wpdb->prefix . "tot_db_groups";
        $query = "SELECT * FROM {$db_groups}";
        $results = mysql_query($query) or die(mysql_error());

        $out .= "<html>";
        $out .= "<body>";
        $out .= "<div id='data_set'>";

        $out .= "<div id='db_message'>Modify the users records here. $myMsg</div>";

        $out .= "<div id='record-body'>";

        while ($row = mysql_fetch_assoc($results)) {
            $out .= "<div id='db_row'><form name='form " . $row_number++ . "' method='POST'>";
            $out .= "<input type='hidden' name='groups_form'>";  // so we know the name of the form
            foreach ($row as $cname => $cvalue) {
                //$out .= "$cname: $cvalue\t";
                switch ($cname) {
                    case "id": // looking for field id so we can output a hidden field
                        $out .= "<input type='hidden' name='" . $cname . "' value='" . $cvalue . "'>";
                        break;
                    case "group_name": // looking for fields table headers
                        $out .= $cname . "<input type='text' name='" . $cname . "' value='" . $cvalue . "'>";
                        break;
                }
            }
            $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field');
            $out .= "<input type='submit' name='UPDATE_RECORD' value='Save'>";
            $out .= "<input type='submit' name='DELETE_RECORD' value='Delete'>";
            $out .= "</form></div>";
        }
        $out .= "<div id='db_row'><form name='new_record' method='POST'>";
        $out .= "<input type='hidden' name='groups_form'>"; // unique identifier for this form
        $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field');
        $out .= "<input type='submit' name='ADD_RECORD' value='new'></form></div>";
        $out .= "<br></div>";
        $out .= "</div>"; // </ end of our div
        $out .= "</body>"; // </ end of our body
        $out .= "</html> "; // </ end of our html
        return $out;
    }

}

// *************************************************************
// create the fields table form
function view_db_fields_form($db_select_items = "a") {

    global $wpdb;
    global $myMsg;
    $db_records_table = $wpdb->prefix . "tot_db_records"; // records table name
    $db_fields = $wpdb->prefix . "tot_db_fields"; // fields table name
    $db_groups = $wpdb->prefix . "tot_db_groups"; // groups table name
    $field_names_array = $wpdb->get_results("SELECT * FROM {$db_fields}"); // get the data out of the db
    $num_rows = $wpdb->num_rows;  // get the number of rows in the data selected
    $db_field_items = return_db_fields("fields"); // get names for the data in the table
    $field_names = explode(",", $db_field_items); // breaks down names
    $field_groups_array = $wpdb->get_results("SELECT * FROM {$db_groups}"); // load the field groups to poulate the options

    $out .= hide_header_on_this_page();
    $out .= "<html><body><div id='not_used'>";   // < Start of CREATION of the html web page
    $out .= "<div id='record-body'>";

    //CREATE THE HEADER ROW
    $out_header .= "<div id='db-header'>"; // create the div so it styles nicely

    for ($start = 0; $start < count($field_names); $start++) { // based on the number of field names cycle through data
        $out_header .= "<input type='text' name='" . $field_names[$start];
        $out_header .= " ' value='" . $field_names[$start] . "' readonly>";
    }
    $out_header .= "</div>"; // end of header div

    $out .= $out_header;

    for ($row_line = 0; $row_line < $num_rows; $row_line++) { // cycle through each row of data
        $out .= "<div id='db_row'>"; // create the div so it styles nicely
        $out .= "<form name='form" . $row_line . "' method='POST'>"; // create the form
        $out .= "<input type='hidden' name='id' value='" . $field_names_array[$row_line]->{id} . "'>"; // hide the id
        $out .= "<input type='hidden' name='fields_form'>"; // unique identifier for this form
        $out .= "<input type='submit' name='UPDATE_RECORD' value='Save'>";
        $out .= "<input type='submit' name='DELETE_RECORD' value='Delete'>";

        for ($start = 0; $start < count($field_names); $start++) { // based on the number of field names cycle through data
            $out .= "<input type='text' name='" . $field_names[$start];
            $out .= " ' value='" . $field_names_array[$row_line]->$field_names[$start] . "'>";
        }
        $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field'); // wp nonce field for security
        
        $out .= "</form></div>";
    } // end of rows
    $out .= "<br></div>"; // END OF record-body DIV

    $out .= "<div id='db_row'><form name='new_record' method='post'>"; // create a new form for the new record button
    $out .= "<input type='hidden' name='fields_form'>"; // unique identifier for this form
    $out .= wp_nonce_field('db_update_nonce_field', 'db_update_secure_nonce_field'); // wp nonce field for security

    $out .= "<input type='submit' name='ADD_RECORD' value='NEW' ></form></div>"; // add new record button

    $out .= "</div></body></html> "; // </ end of our html
    return $out; // return
}

// *************************************************************
// trimming inputs
if (!function_exists('check_input')) {

    function check_input($data) { // this function cleans off extras from data. 
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

}

// *************************************************************
// table column names. this should be built into code. note it must match the table column names of weird stuff happens to outputs
if (!function_exists('return_db_fields')) {

    function return_db_fields($form_name_string) { // This is where I put the names for the fields so i can loop through the database information
        //this is the list of field names in the table. Had to add this to be able to select and print them out. probably an easier way for sure but this works
        switch ($form_name_string) { // check from the parameter sent whicg table this is.
            case "fields": // looking for fields table headers
                return "field_name,field_title,field_default,field_help,field_width,field_group,field_sub_group,field_order,field_values,field_element,field_validation,field_cb_required,field_cb_sortable,field_cb_readonly";
                break;
            case "groups": // looking for groups table headers
                return "group_name";
                break;
        }
    }

}
?>