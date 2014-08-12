<?php
/*
 */
// function to create the DB / Options / Defaults					
function install_plugin_create_fields_table() {
    global $wpdb;
    
    $db_fields_table = $wpdb->prefix . 'tot_db_fields';

    // create the tot fields database table
    if($wpdb->get_var("show tables like '$db_fields_table'") != $db_fields_table) 
    {
        $sql = "CREATE TABLE " . $db_fields_table . " (
        `id` int(3) NOT NULL AUTO_INCREMENT,
        `field_name` varchar(32) DEFAULT 'enter field name' NOT NULL, 
        `field_title` varchar(32) DEFAULT 'enter title' NOT NULL,
        `field_default` varchar(32) DEFAULT '' NULL,
        `field_help` varchar(32) DEFAULT '' NULL,
        `field_width` varchar(32) DEFAULT '60px' NOT NULL,
        `field_group` varchar(32) DEFAULT 'none' NOT NULL,
        `field_sub_group` varchar(32) DEFAULT 'none' NOT NULL,
        `field_order` int(3) DEFAULT '0' NOT NULL,
        `field_values` varchar(32),
        `field_element` varchar(32),
        `field_validation` varchar(32),
        `field_cb_required` varchar(32),
        `field_cb_sortable` varchar(32),
        `field_cb_readonly` varchar(32),
        UNIQUE KEY id (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

function install_plugin_create_records_table(){
   	global $wpdb;
  	
	$db_records_table = $wpdb->prefix . 'tot_db_records';
 
	// create the tot fields database table
	if($wpdb->get_var("show tables like '$db_records_table'") != $db_records_table) 
	{
		$sql = "CREATE TABLE " . $db_records_table . " (
		`id` int(3) NOT NULL AUTO_INCREMENT,
		`user_name` varchar(64) NOT NULL,
		`data_name` varchar(64) NOT NULL,
		`data_type` varchar(64) NOT NULL,
		`data_value` text NULL,
		`date_recorded` timestamp NOT NULL,
		UNIQUE KEY id (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
 
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
}

function install_plugin_create_groups_table(){
   	global $wpdb;
  	
	$tot_db_groups = $wpdb->prefix . 'tot_db_groups';
	// create the tot fields database table
	//hwi_tot_db_groups
	if($wpdb->get_var("show tables like '$tot_db_groups'") != $tot_db_groups) 
	{
		$sql = "CREATE TABLE " . $tot_db_groups . " (
		`id` int(3) NOT NULL AUTO_INCREMENT,
		`group_name` varchar(64) NOT NULL,
		UNIQUE KEY id (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
 
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}
}

function insert_data() {
   global $wpdb;

   // add record to the fields table
   $rows_affected = $wpdb->insert( ($wpdb->prefix . "tot_db_fields"), 
   array('field_name' => 'house_name', 
   'field_title' => 'House Name', 
   'field_help' => "Enter a name for this house" ) );

   // add record to the fields table
   $rows_affected = $wpdb->insert( ($wpdb->prefix . "tot_db_fields"), 
   array('field_name' => 'house_address', 
   'field_title' => 'House Address', 
   'field_help' => "Enter the address for this house" ) );

   // add record to the groups table
   $rows_affected = $wpdb->insert( $wpdb->prefix . "tot_db_groups", array('group_name' => 'house description' ) );
   $rows_affected = $wpdb->insert( $wpdb->prefix . "tot_db_groups", array('group_name' => 'house detail' ) );
   
   // add records to the records table
   $rows_affected = $wpdb->insert( ($wpdb->prefix . "tot_db_records"), array(
   'data_name' => 'house_name', 
   'data_type' => 'data_type', 
   'data_value' => 'data value' 
   ) );   }
   
   
   
function db_uninstall(){
	global $wpdb;
	
	// db field table
	$db_table_name = $wpdb->prefix. 'tot_db_fields';
	$wpdb->query("DROP TABLE IF EXISTS $db_table_name");
	
	// db records table
	$db_table_name = $wpdb->prefix. 'tot_db_records';
	$wpdb->query("DROP TABLE IF EXISTS $db_table_name");
	
	// db records table
	$db_table_name = $wpdb->prefix. 'tot_db_groups';
	$wpdb->query("DROP TABLE IF EXISTS $db_table_name");

}
?>