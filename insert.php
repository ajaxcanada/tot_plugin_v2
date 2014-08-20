

/* 
 * AJax created this from samples pulled off the web.
 * 
 * 
 */
<?php
// CONNECT TO WORDPRESS
global $wpdb;
//LOAD THE TABLE NAME
$db_records = $wpdb->prefix . "tot_db_records"; 
// LOAD THE QUERY 
$query_records = "SELECT " . $db_fields_names . " FROM {$db_records} WHERE user_id={$user_id}"; // records string to pass to mysql query
// EXECUTE THE QUERY
$records_results = mysql_query($query_records) or die(mysql_error()); // get records from database

while ($row = mysql_fetch_assoc($records_results)) {  
    foreach ($row as $fieldname => $fieldvalue) {
            switch ($fieldname) {
                case 'id':
                case 'user_id':
                case 'date_recorded': break;
                default:
//                    $out .= "<input type='text' name='$fieldname' id='$fieldname' value='$fieldvalue'>"; // capture the new record name
            }
}

     
//$customers = $wpdb->get_results($wpdb->prepare("SELECT * FROM tblCustomers 
//      WHERE State = %s", 'NY'));
// foreach ($customers as $cust) {
//   $custid = $cust->ID;
  
 }
//$query_records = "SELECT " . $db_fields_names . " FROM {$db_records} WHERE user_id={$user_id}"; // records string to pass to mysql query

?>