<?php
function json_test(){
    $db_records = $wpdb->prefix . "tot_db_fields"; // load db records
    $query_records = "SELECT * FROM {$db_records}";// WHERE user_id={$user_id}"; // records string to pass to mysql query
    $result = mysql_query($query_records) or die(mysql_error()); // get records from database
    //Create Database connection
//    $db = mysql_connect("localhost","root","root");
//    if (!$db) {
//        die('Could not connect to db: ' . mysql_error());
//    }
 
    //Select the Database
//    mysql_select_db("test_json",$db);
    
    //Replace * in the query with the column names.
//    $result = mysql_query("select * from employee", $db);  
    
    //Create an array
    $json_response = array();
    
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $row_array['id'] = $row['id'];
        $row_array['field_name'] = $row['emp_name'];
        $row_array['field_title'] = $row['designation'];
        $row_array['field_default'] = $row['date_joined'];
        $row_array['field_help'] = $row['salary'];
        $row_array['field_width'] = $row['id_dept'];
        
        //push the values in the array
        array_push($json_response,$row_array);
    }
    echo json_encode($json_response);
}
    //Close the database connection
