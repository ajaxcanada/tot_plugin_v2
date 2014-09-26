<?php

add_action('init', 'process_post'); // This loads the function thats used to capture form information when they press a button
// THIS IS USED TO CHANGE THE GROUP THAT WAS SELECTED. 
// UPDATE THE DATABASE AND THE FORM WILL LOAD THIS GROUP

function change_group($group_requested) {
    $user_id = get_current_user_id();
    global $wpdb;
    $db_table = $wpdb->prefix . "tot_db_records";
    $user_id = get_current_user_id();
    $wpdb->query("UPDATE $db_table SET group_selected = '$group_requested' WHERE user_id = '$user_id'");
}

function save_file() {
    // THIS HAS A DEBUG COMMENT AT THE BOTTOM TO STOP IT FROM OUTPUTTING
    $id = get_current_user_id();
    // SETS THE PATH TO WHERE FILES ARE SAVED
    define('UPLOADS', 'members/' . $id);
    // TURNS OFF THE DATE IN FOLDER FEATURE USED BY MEDIA LIBRARY
    add_filter('option_uploads_use_yearmonth_folders', '__return_false', 100);

    if ($_FILES) {
        $files = $_FILES["tot_attachments"];
        foreach ($files['name'] as $key => $value) {
            if ($files['name'][$key]) {
                $file = array(
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                );
                $_FILES = array("tot_attachments" => $file);
                foreach ($_FILES as $file => $array) {
                    // LOAD THE FILE INFORMATION INTO A VARIABLE
                    $newupload = handle_attachment($file);

                    // REMOVE COMMENTS TO DISPLAY DEBUG INFO
                    //$debug_Info = debug_save_file($newupload, $key, $files);

//                    $out_debug .= "'"
//                            . "<br> ID =" . $newupload
//                            . "'";
//
//                    $out_debug .= "'"
//                            . "<br> Name = " . $files['name'][$key]
//                            . "<br> error = " . $files['error'][$key]
//                            . "'";
//
//                    // GETS THE FILE INFORMATION IN AN ARRAY
//                    $uploads = wp_upload_dir();
//                   
//                    $out_debug .= "'"
//                            . "<br> url = " . $uploads['url']
//                            . "<br> basedir = " . $uploads['basedir']
//                            . "<br> baseurl = " . $uploads['baseurl']
//                            . "<br> error =" . $uploads['error']
//                            . "'";
//
//                    $out_debug .= "dir_list = " . $dir_list . " <br> img src='" . $uploads['url'] . "/" . $files['name'][$key] . "' /";
//                    $out_debug .= "<img src='" . $uploads['url'] . "/" . $files['name'][$key] . "'  WIDTH=32 HEIGHT=32 />";
//                    //return $out_debug;
                }
            }
        }
    }
    //return "debug info " . $out_debug . " wow " . $newupload;
}

function delete_file($filename) {
    global $myMsg;
    // THIS HAS A DEBUG COMMENT AT THE BOTTOM TO STOP IT FROM OUTPUTTING
    $id = get_current_user_id();
    // SETS THE PATH TO WHERE FILES ARE SAVED
    define('UPLOADS', 'members/' . $id);
    add_filter('option_uploads_use_yearmonth_folders', '__return_false', 100);
    $uploads = wp_upload_dir();
//    $out_debug .= "'"
//            . "<br> url = " . $uploads['url']
//            . "<br> basedir = " . $uploads['basedir']
//            . "<br> baseurl = " . $uploads['baseurl']
//            . "<br> error =" . $uploads['error']
//            . "'";

    $myMsg = "not in service"; // not in service$out_debug;// . $temp;
    //$baseurl = $uploads['baseurl'];
    return $filename;
}

// =======================================================================
// THIS SAVES FILES INTO THE USERS FOLDER. NO ERROR HANDLING THO0UGH:(
// =======================================================================
function handle_attachment($file_handler) {
    // check to make sure its a successful upload. SHOULD DEAL WITH ERROR
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK)
        __return_false();

    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');
    
    $uploadedfile = $_FILES['tot_attachments'];
    $upload_overrides = array('test_form' => false);
    $attach_id = wp_handle_upload($uploadedfile, $upload_overrides);

    // THIS UPLOADS MEDIA INTO WORDPRESS MEDIA LIBRARY. CREATES DEFAULT WP SIZED IMAGES.
    // $attach_id = media_handle_upload($file_handler, $post_id);

    return $attach_id;
}

function store_image() {
// =======================================================================
// STORE THE NAMES OF IMAGES IN THE MEDIA TABLE. 
// NOT IMPLIMENTED YET
// =======================================================================
//mysql_query("ALTER TABLE " . $db_table . " ADD " . $proper_name . " " . $new_field_atributes);
    global $wpdb;

    $tot_db_media = $wpdb->prefix . 'tot_db_media';
    // create the tot fields database table
    //if ($wpdb->get_var("show tables like '$tot_db_media'") != $tot_db_media) {

    $db_table = $wpdb->prefix . "$tot_db_media";
    $fields_array_data = ['field_name' => $proper_name, 'field_title' => $new_field_name, 'field_group' => $new_group];

    $fields_array_data_type = ['%s', '%s', '%s'];
    $wpdb->INSERT($db_table, $fields_array_data, $fields_array_data_type);
}

// =======================================================================
// THIS PROCESSES THE POST. ORIGINAL SCRIPT. 
// NEEDS TO BE CLEANED UP ONCE NEW CONTROLS AND JQUERY ARE ADDED
// =======================================================================
function process_post() {
    global $wpdb;
    global $myMsg;

    $form_nonce = filter_input(INPUT_POST, 'db_update_secure_nonce_field', FILTER_SANITIZE_STRING);
    //  VERIFY THE NONCE
    if (!empty($form_nonce) && (wp_verify_nonce($form_nonce, 'db_update_nonce_field'))) {
        // CHECK IF A NAVIGATION BUTTON IS PRESSED. CHANGE GROUP SERVER SIDE
        $group_requested = filter_input(INPUT_POST, 'navigator', FILTER_SANITIZE_SPECIAL_CHARS);
        if (isset($group_requested)) {
            // note this is only used when jquery ajax are not working 
            $user_id = get_current_user_id();
            change_group($group_requested);
            //$myMsg .= 'PROCESS_POST SAYS> userID =' . $user_id . "the group Selected " . $group_requested;
        }

        //  FILTER POST DATA FOR INPUTS. VALUES ARE THE COMMAND REQUEST
        $user_id = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT);

        $update_button_pressed .= filter_input(INPUT_POST, 'update_record_x', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($update_button_pressed != "") {
            $myMsg .= 'update record';
        }

        $delete_button_pressed .= filter_input(INPUT_POST, 'delete_record_x', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($delete_button_pressed != "") {
            delete_file();
            $myMsg .= 'delete record';
        }

        $add_button_pressed .= filter_input(INPUT_POST, 'add_record_x', FILTER_SANITIZE_SPECIAL_CHARS);
        if ($add_button_pressed != "") {
            $myMsg = 'add record';
        }

        //  FILTER POST DATA FOR BUTTON INPUTS. VALUES ARE THE COMMAND REQUEST
        $which_button_pressed .= filter_input(INPUT_POST, 'UPLOAD_BUTTON', FILTER_SANITIZE_SPECIAL_CHARS);
        $myMsg .= "DEBUG return = " . $which_button_pressed;
        switch ($which_button_pressed) {
            case 'Upload':
                $return_message = save_file();
                if ($return_message != "") {
                    $myMsg .= "BUTTON PRESSED= " . $return_message. "<br>";
                };
                break;
            //default:
            //exit();
            //break;
        }

        //========================= GOOD CODE ABOVE THIS LINE. LOL ============================================================
        // check the hidden field on each form to get form name. check security
        // *************************************************************
        if (isset($_POST['fields_form']) && wp_verify_nonce($_POST['db_update_secure_nonce_field'], 'db_update_nonce_field')) {
            $db_field_items = return_db_fields("fields"); // fetch table column names
            $db_table = $wpdb->prefix . "tot_db_fields"; //tot_db_fields
            $fields_array_data = ['field_name' => "enter_a_name", 'field_title' => "enter_a_title"]; // defaults for the fields table
            $fields_array_data_type = ['%s', '%s'];
        } elseif (isset($_POST['groups_form']) && wp_verify_nonce($_POST['db_update_secure_nonce_field'], 'db_update_nonce_field')) {
            $db_table = $wpdb->prefix . "tot_db_groups"; // set the table name to tot_db_groups
            //$db_items = return_db_fields('groups'); // get the group table field names
            $fields_array_data = ['Group_name' => "enter_a_name"]; // defaults for the groups table
            $fields_array_data_type = ['%s'];
        } elseif (isset($_POST['records_form']) && wp_verify_nonce($_POST['db_update_secure_nonce_field'], 'db_update_nonce_field')) {
            $db_table = $wpdb->prefix . "tot_db_records"; // set the table name to tot_db_groups

            $fields_array_data = ['user_name' => $current_user, 'data_name' => "enter_a_name"]; // records defaults 
            $fields_array_data_type = ['%s'];
        } elseif (isset($_POST['navigator']) && wp_verify_nonce($_POST['db_update_secure_nonce_field'], 'db_update_nonce_field')) {

            // not used any more. 
            //$data_group = $_POST['navigator'];
            // user pressed a navigation button
        } elseif (isset($_POST['record_field_form']) && wp_verify_nonce($_POST['db_update_secure_nonce_field'], 'db_update_nonce_field')) {
            $db_table = $wpdb->prefix . "tot_db_records"; // set the table name to tot_db_groups
            $db_field_table = $wpdb->prefix . "tot_db_fields"; // set the table name to tot_db_groups

            $fields_array_data = ['user_name' => $current_user, 'data_name' => "enter_a_name"]; // records defaults 
            $fields_array_data_type = ['%s'];
        } elseif (isset($_POST['send_sms_message']) && wp_verify_nonce($_POST['db_update_secure_nonce_field'], 'db_update_nonce_field')) {
            $number = $_POST['phone']; // records defaults 
            $name = $_POST['sms_user_name']; // records defaults 
            $message = $_POST['message']; // records defaults 

            $return_message = send_sms($number, $name, $message);
        }

        $field_id = $_POST[id];  //$field_names = explode(", ",$db_field_items);
        // user selected delete
        // *************************************************************
        if (isset($_POST['DELETE_RECORD']) && wp_verify_nonce($_POST['db_update_secure_nonce_field'], 'db_update_nonce_field')) {
            //echo " record id = " . $_POST['id'] . " button = " . $_POST[DELETE_RECORD] . " == " . $field_id ;
            $wpdb->query("DELETE FROM $db_table WHERE id = '$field_id'");
            // may want to add a jquery pop up: are you sure?!
            // *************************************************************
        }

        // user selected UPDATE or SAVE
        // *************************************************************
        if (isset($_POST['UPDATE_RECORD']) && wp_verify_nonce($_POST['db_update_secure_nonce_field'], 'db_update_nonce_field')) {
            // *************************************************************
            foreach ($_POST as $param_name => $param_val) {       //cycle through the fields here
                $clean_param = rtrim($param_name, '_');       // strip off extra _ off the end opf the data

                switch ($clean_param) {           // jump over these pieces of data
                    case 'fields_form': break;
                    case 'groups_form': break;
                    case 'id':break;
                    case 'db_update_secure_nonce_field':break;
                    case '_wp_http_referer':break;
                    case 'UPDATE_RECORD':break;
                    default:
                        //echo "==> UPDATE $db_table SET $clean_param = '$param_val' WHERE id = '$field_id' <br />\n";
                        $wpdb->query("UPDATE $db_table SET $clean_param = '$param_val' WHERE id = '$field_id'");
                }  // end of switch
            }  // end foreach
        }  // end if
        // *************************************************************
        if (isset($_POST['ADD_RECORD']) && wp_verify_nonce($_POST['db_update_secure_nonce_field'], 'db_update_nonce_field')) {
            //echo "user selected add fields";
            $wpdb->INSERT($db_table, $fields_array_data, $fields_array_data_type);
        }  // end if
        // *************************************************************
        if (isset($_POST['ADD_RECORD_COLUMN']) && wp_verify_nonce($_POST['db_update_secure_nonce_field'], 'db_update_nonce_field')) {
            if (empty($_POST['new_record_name_input'])) {
                //$myMsg .= "name of the column field is blank >>" . $new_field_name . "<< ";
            } else {
                $new_field_name = $_POST['new_record_name_input'];
                $new_field_atributes = "text";

                //$myMsg .= "Adding column;

                $proper_name = str_replace(' ', '_', $new_field_name);
                if (isset($_POST['new_record_group'])) {
                    $new_group = $_POST['new_record_group'];
                    //$myMsg .= $new_group;
                }

                mysql_query("ALTER TABLE " . $db_table . " ADD " . $proper_name . " " . $new_field_atributes);

                $fields_array_data = ['field_name' => $proper_name, 'field_title' => $new_field_name, 'field_group' => $new_group];

                $fields_array_data_type = ['%s', '%s', '%s'];
                $wpdb->INSERT($db_field_table, $fields_array_data, $fields_array_data_type);
            }
        }  // end if
        // *************************************************************
        if (isset($_POST['DELETE_RECORD_COLUMN']) && wp_verify_nonce($_POST['db_update_secure_nonce_field'], 'db_update_nonce_field')) {
            if (empty($_POST['new_record_name_input'])) {
                //echo "user selected add new column named". 
            } else {
                $db_records = $wpdb->prefix . "tot_db_records";
                $query_records = "SELECT * FROM {$db_records}"; // 
                $record_results = mysql_query($query_records) or die(mysql_error()); // get db

                $new_field_name = $_POST['new_record_name_input'];
                $proper_name = str_replace(' ', '_', $new_field_name);

                // check if column exists and delete it
                while ($row = mysql_fetch_assoc($record_results)) {
                    foreach ($row as $cname => $cvalue) {
                        if ($proper_name == $cname) {
                            mysql_query("ALTER TABLE " . $db_table . " DROP " . $proper_name);
                            break 2;
                        }
                    }
                }
                //check if row exists and delete it
                $db_fields = $wpdb->prefix . "tot_db_fields";
                $query_fields = "SELECT `field_name`, `id` FROM {$db_fields}"; // 
                $field_results = mysql_query($query_fields) or die(mysql_error()); // get db
                // something not working
                while ($row = mysql_fetch_assoc($field_results)) {
                    foreach ($row as $cname => $cvalue) {
                        //$myMsg .= $count++  . " w ". $proper_name . " w ". $new_field_name . " | ";
                        if ($proper_name == $cvalue) {
                            //$myMsg .= "proper Name" . $proper_name;
                            $wpdb->query("DELETE FROM $db_fields WHERE `field_name` = '" . $cvalue . "'");
                            break 2;
                        }
                    }
                }
            }
        }
    }
}

// *************************************************************
// add action
// REFERENCE  These actions are called when a logged-in user opens the home page in Version 3.3.1. 
// This list may show only the first time each action is called, and in many cases no function is hooked to the action. 
// Themes and plugins can cause actions to be called multiple times and at differing times during a request. 
// As proof of this, you can see action calls specific to the Twenty Eleven theme on this list. 
// Cron tasks may also fire when a user visits the site, adding additional action calls. 
// This list should be viewed as a guide line or approximation of WordPress action execution order, and not a concrete specification.
// *************************************************************
// // Check that the nonce is valid, and the user can edit this post.
//if ( 
//	isset( $_POST['my_image_upload_nonce'], $_POST['post_id'] ) 
//	&& wp_verify_nonce( $_POST['my_image_upload_nonce'], 'my_image_upload' )
//	&& current_user_can( 'edit_post', $_POST['post_id'] )
//) {
//	// The nonce was valid and the user has the capabilities, it is safe to continue.
//
//	// Remember, 'my_image_upload' is the name of our file input in our form above.
//	$attachment_id = media_handle_upload( 'my_image_upload', $_POST['post_id'] );
//	
//	if ( is_wp_error( $attachment_id ) ) {
//		// There was an error uploading the image.
//	} else {
//		// The image was uploaded successfully!
//	}
//      } else {
//	// The security check failed, maybe show the user an error.
//      }
// THIS LOADS THE HOOK (FUNCTION) TO RETRIEVE USER INPUTS 
// AFTER POSTING (USER SUBMIT OF SOME SORT). 
// THIS IS WHERE SECURITY BEGINS. 
// *************************************************************
?>