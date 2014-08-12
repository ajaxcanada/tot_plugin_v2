<?php
// *************************************************************
// HIDE THE HEADER AND TITLE OFF THE PAGE
function hide_header_on_this_page(){
    $css_out .= "<style>";
    $css_out .= "#header {display: none; }";
    $css_out .= ".entry-title {display: none;}";
    $css_out .= "</style>";
    return $css_out;
}

// *************************************************************
// CREAT THE MAIN FORM HERE

if(!function_exists(view_user_main_form)) {
function view_user_main_form(){
	global $user_nav_selection;
        $start = microtime(TRUE);  // starts a microtimer called start
        // SET THE DEAFULT NAVIGATION PAGE
        if (! isset($user_nav_selection)){
            $user_nav_selection= "house description";
        }

        $out .= hide_header_on_this_page();
        $out .= Create_main_styles();
	$out .= "<html><body>"; // start of the html
	$out .= "<div id='data_set'>"; // DIV_PAGE ==================================
	$out .= create_message_area(); // MESSAGE DIV CODE
	$out .= create_navigation(); // NAVIGATION DIV CODE
	$out .= create_main_area($user_nav_selection); // MAIN DIV CODE
    	$out .= create_main_footer($start); // FOOTER CODE
        	
	//$out .= "</div> ";	// </ END OF MAIN FORM DIV
	$out .= "</body></html> ";	// </ end of our html
	return $out;
    }
}

// *************************************************************
// CREATE THE MAIN FORM HERE
function create_main_area($user_nav_selection){
    global $wpdb;  // wordpress database connection
    
    // FIRST LOAD THE FIELD TABLE BASED ON THE USER SELECTION OR DEFAULT
    $db_fields = $wpdb->prefix."tot_db_fields"; // load fields records
    $query_fields = "SELECT `field_name` FROM {$db_fields} where field_group = '".$user_nav_selection."'"; //fields string to pass to mysql query
    $fields_results= mysql_query($query_fields) or die(mysql_error());// get fields from database

    $num_cols = mysql_num_fields($fields_results);
    $num_rows = mysql_num_rows($fields_results);
    $values = array();

    // INIT ARRAY
    for ($c=1;$c<=$num_cols;$c++) { for ($r=1;$r<=$num_rows;$r++) { $values['col_'.$c][$r] = array(); }}
    $c = 1;  $r = 1; // INIT VARIABLES
    // LOAD THE FIELDNAMES INTO THE ARRAY
    while($fieldrow = mysql_fetch_assoc($fields_results)){  // load the group_rows of fields data 
        $c=1; // reset back to column 1
        foreach($fieldrow as $field_name => $field_value){
            $values['col'.$c][$r] = $field_value;
            $c++;
        }
        $r++;
    }

    // USE THE ARRAY DATA TO CREATE A SEARCH STRING
    $db_fields_names .= "`id`, `user_name`, ";
    for ($r=1;$r<=$num_rows;$r++) { 
	for ($c=1;$c<=$num_cols;$c++) {
            $db_fields_names .= "`" . $values['col'.$c][$r] . "`, "; 
        }
    }

    // add id, username, date_recorded (add modified) this onto the end of the array    
    $db_fields_names .= "`date_recorded`";
    // send the field name out for debug

    $db_records = $wpdb->prefix."tot_db_records"; // load db records
    $query_records = "SELECT ". $db_fields_names ." FROM {$db_records}"; // records string to pass to mysql query
    $records_results= mysql_query($query_records) or die(mysql_error()); // get records from database

    // $data[1] = "Enter the name for the new column of data in the database";

    // DIV_MAIN_FORM ============================
    $out .= "<div id='TOT_MAIN_USER_FORM'>"; 

    $out .= "<form name='main_form_data' method='POST'>"; 
    //$out .= "<br>"; 

    $out .= "<input type='hidden' name='main_form'>"; // unique identifier for this form
    $out .= wp_nonce_field('db_update_nonce_field1','db_update_secure_nonce_field'); // SECURITY
//    $out .= "user nav ". $user_nav_selection; 
//    $out .= " Record Name:<span title = '$data[1]'><input type='text' tooltip='test' name='new_record_name_input'></span><br>"; // capture the new record name
  
    // OUTPUT THE DATA ==========================
    while($row = mysql_fetch_assoc($records_results)){  // load the group_rows of fields data 
        $out .= "<div id='col_" . $r_count++."'>" ;
        foreach($row as $fieldname => $fieldvalue){
            switch($fieldname){
                case 'id':
                case 'user_name':
                case 'date_recorded':
                    $out .= "<input type='hidden' name='$fieldname' value='$fieldvalue'>"; break;
                default:
                    $out .= "<label for='$fieldname'>$fieldname</label>";
                    $out .= "<input type='text' name='$fieldname' value='$fieldvalue'>"; // capture the new record name
            }
            
            //$out .=  $fieldvalue ; // output column to screen
            // if($count++ == 8){break;}
       }
       $out .= " </div> ";
       //this is test  $out .= "<br>"; 
    } 

    $out .= "<input type='submit' name='UPDATE_RECORD' value='update record'>"; // the add new button
    $out .= "<input type='submit' name='DELETE_RECORD' value='delete record'>"; // the add new button
    $out .= "</form>"; // End the form 
    $out .= "</div>";  // end DIV_MAIN_FORM  ======================================
return $out;
}


// ========================================
// USER MESSAGE AREA
function create_message_area(){
	global $myMsg;
        // DIV_MY_MESSAGE ============================
	$out .= "<div id='message'>";
	$out .= $myMsg ."<br>";
	$out .= "Your record management area"; 
	$out .= "</div>"; 
	// end DIV_MY_MESSAGE
        return $out;

}
function create_main_footer($start){
    // DIV_FOOTER	
	$out .= "<div id='TOT_FOOTER'>"; 
	$out .= "<div id='my_timer'>&nbsp;Elapsed time=";
	$out .= page_timer ($start) .  "</div>"; //    
	$out .= "</div>"; // end / D I V end DIV_PAGE
	
	$out .= "</div>";
        
        return $out;
}
function create_navigation(){
    // DIV_NAVIGATION ============================
	global $wpdb;  // wordpress database connection
	
        $nav_group_message = "These are the groups of data you can access";
	$db_groups = $wpdb->prefix."tot_db_groups"; // load fields records
	$query_groups = "SELECT * FROM {$db_groups}"; //fields string to pass to mysql query
	$groups_results= mysql_query($query_groups) or die(mysql_error());// get groups from database
	
	$out .= "<div id='TOT_NAV'><span title='$nav_group_message'>Groups</span><br>"; 
	$out .= "<form name='navigation' method='POST'>"; // Form - new_record
	$out .= "<input type='hidden' name='navigation'>"; // unique identifier for this form
	$out .= wp_nonce_field('db_update_nonce_field','db_update_secure_nonce_field'); // SECURITY
	
	while($db_row = mysql_fetch_assoc($groups_results)){  // load the group_rows of fields data 
            foreach($db_row as $col_name => $col_value){
                if($col_name != 'id'){
                        $out .= "<input type='submit' name='navigator' value='$col_value'>"; // the add new button
                }
            }
            $out .= "<br>"; // new line
	} 
	
	$out .= "</form>"; // End the form 
	$out .= "</div>"; 
	// end DIV_NAVIGATION 
        return $out;
                
}





function page_timer ($start) {
	$finish = microtime(TRUE); 	
	$totaltime = $finish - $start;
	return $totaltime;
	}

function reference_code(){
    $start = microtime(TRUE);  // starts a microtimer called start
    global $wpdb;  // wordpress database connection
    global $myMsg;	
    $db_records = $wpdb->prefix."tot_db_records"; // load db records
    $db_fields = $wpdb->prefix."tot_db_fields"; // load fields records
    $db_groups = $wpdb->prefix."tot_db_groups"; // load fields records

    $query_records = "SELECT * FROM {$db_records}"; // records string to pass to mysql query
    $query_fields = "SELECT * FROM {$db_fields}"; //fields string to pass to mysql query
    $query_groups = "SELECT * FROM {$db_groups}"; //fields string to pass to mysql query

    $records_results= mysql_query($query_records) or die(mysql_error()); // get records from database
    $fields_results= mysql_query($query_fields) or die(mysql_error());// get fields from database
    $groups_results= mysql_query($query_groups) or die(mysql_error());// get groups from database


    $out .= "<html><body>"; // start of the html
    $out .= "<div id='data_set'>"; // D I V 1 - the entire form so we can easily add style scroll

    $out .= "<div id='message'>". $myMsg ."<br>To add a new record. enter the new record information, then press ADD"; // D I V 2 messages to the user
    $out .= "</div><br>"; // end / D I V 3  / D I V 2	

    // ============================ add a record form == START


    $out .= "<div id='main_user_form'>"; // D I V 3
    $out .= "<form name='new_record_field' method='POST'>"; // Form - new_record
    $out .= "<input type='hidden' name='record_field_form'>"; // unique identifier for this form
    $out .= wp_nonce_field('db_update_nonce_field','db_update_secure_nonce_field'); // SECURITY

    $out .= " Record Name:<input type='text' name='new_record_name_input'>"; // capture the new record name
//	$out .= " Record Title:<input type='text' name='new_record_title_input'>"; // capture the new record name
    $out .= "<br>Record Group:";
    $out .= "<select id='group' name='record_group' >";

    while($group_row = mysql_fetch_assoc($groups_results)){  // load the group_rows of fields data 
            foreach($group_row as $cgroupname => $cgroupvalue){}
            $out .= "<option value='" . $cgroupvalue . "' >" . $cgroupvalue . "</option>"; // output column to screen
    }
    $out .= "</select><br>";	
    $out .= "Record Sub-group:";
    $out .= "<select id='sub_group' name='record_sub_group'>";
    $out .= "<option value='field_sub_group' >enter a sub group</option>"; // output column to screen
    $out .= "</select><br>";

    while($fieldrow = mysql_fetch_assoc($fields_results)){  // load the group_rows of fields data 
            foreach($fieldrow as $fieldname => $fieldvalue){
                    $out .= " ". $fieldvalue . " | "; // output column to screen
                    if($count++ == 2){break;}
                    }
                    $count = 0;
            $out .= "<br>"; // new line
    } 

    $out .= "<input type='submit' name='ADD_RECORD' value='new'><br>"; // the add new button
    $out .= "<input type='submit' name='ADD_RECORD_COLUMN' value='new column'>"; // the add new button
    $out .= "<input type='submit' name='DELETE_RECORD_COLUMN' value='Delete column'>"; // the add new button

    $out .= "</form>"; // End the form 

    // ============================ add a record form == END

    $out .= "</div></div><br>"; // end / D I V 3  / D I V 2	
		

    $out .= "<div id='footer_info'>"; // D I V information
    $finish = microtime(TRUE); 	
    $t=time();
    $totaltime = $finish - $start;
    $out .= "<div id='my_timer'> Date " . (date("Y/m/d",$t)) ."      Elapsed time=";
    $out .= $totaltime .  "</div>";//    CREATE A NEW RECORD=> ";
    $out .= "</div>"; // end / D I V information

    $out .= "</div></body></html> ";	// </ end of our html
    return $out;
}
?>