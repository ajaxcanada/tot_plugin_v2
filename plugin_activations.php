<?php

/*
 */

// function to create the DB / Options / Defaults					
function install_plugin_create_fields_table() {
    global $wpdb;

    $db_fields_table = $wpdb->prefix . 'tot_db_fields';

    // create the tot fields database table
    if ($wpdb->get_var("show tables like '$db_fields_table'") != $db_fields_table) {
        $sql = "CREATE TABLE " . $db_fields_table . " (
        `id` int(3) NOT NULL AUTO_INCREMENT,
        `field_name` varchar(32) DEFAULT 'enter field name' NOT NULL, 
        `field_title` varchar(32) DEFAULT 'enter title' NOT NULL,
        `field_group` varchar(32) DEFAULT 'none' NOT NULL,
        `field_sub_group` varchar(32) DEFAULT 'none' NOT NULL,
        `field_default` varchar(32) DEFAULT '' NULL,
        `field_help` varchar(32) DEFAULT '' NULL,
        `field_width` varchar(32) DEFAULT '60px' NOT NULL,
        `field_order` int(3) DEFAULT '0' NOT NULL,
        `field_values` varchar(32),
        `field_type` varchar(32),
        `field_validation` varchar(32),
        `field_visible` int(3) DEFAULT '1' NOT NULL, 
        `field_cb_required` int(3),
        `field_cb_sortable` int(3),
        `field_cb_readonly` int(3),
        UNIQUE KEY id (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

function install_plugin_create_records_table() {
    global $wpdb;

    $db_records_table = $wpdb->prefix . 'tot_user';

    // create the tot fields database table
    if ($wpdb->get_var("show tables like '$db_records_table'") != $db_records_table) {
        $sql = "CREATE TABLE " . $db_records_table . " (
            `id`                int(3)      NOT NULL AUTO_INCREMENT,
            `date_recorded`     timestamp   NOT NULL,
            `date_modified`     datetime    NOT NULL,
            `user_id`           varchar(32) NOT NULL,
            `group_selected`    varchar(32) NOT NULL,
            `user_address`      varchar(32) NULL,
            `user_gender`       varchar(32) NULL,
            `user_birthday`     date        NULL,
            UNIQUE KEY id (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

function install_plugin_create_groups_table() {
    global $wpdb;

    $tot_db_groups = $wpdb->prefix . 'tot_db_groups';
    // create the tot fields database table
    //hwi_tot_db_groups
    if ($wpdb->get_var("show tables like '$tot_db_groups'") != $tot_db_groups) {
        $sql = "CREATE TABLE " . $tot_db_groups . " (
		`id` int(3) NOT NULL AUTO_INCREMENT,
		`group_name` varchar(64) NOT NULL,
		`group_title` varchar(64) NOT NULL,
		UNIQUE KEY id (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

function install_plugin_create_user_media() {
    global $wpdb;

    $tot_db_media = $wpdb->prefix . 'tot_db_media';
    // create the tot fields database table

    if ($wpdb->get_var("show tables like '$tot_db_media'") != $tot_db_media) {
        $sql = "CREATE TABLE " . $tot_db_media . " (
		`id` int(5) NOT NULL AUTO_INCREMENT,
		`user_id` int(5),
                `reference_name` varchar(64),
		`image_link` varchar(64),
		`image_link` varchar(64),
		PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

// LOAD THE DEFAILT DATA INTO THE TABLES
function insert_data() {
    insert_field_data();
    insert_record_data();
    insert_group_data();
}

function insert_field_data() {
    // FIELDS
    load_table_w_array('tot_db_fields', array('field_name' => 'group_selected', 'field_title' => 'group selected', 'field_group' => 'user', 'field_visible' => '0'));
    load_table_w_array('tot_db_fields', array('field_name' => 'user_address', 'field_title' => 'User Address', 'field_group' => 'user'));
    load_table_w_array('tot_db_fields', array('field_name' => 'user_birthday', 'field_title' => 'User Birthday', 'field_group' => 'user'));
    load_table_w_array('tot_db_fields', array('field_name' => 'user_gender', 'field_title' => 'User Gender', 'field_group' => 'user'));
    
}

function insert_record_data() {
    // RECORDS
    load_table_w_array('tot_user', array('group_selected' => 'user'));
}

function insert_group_data() {
    // GROUPS
    load_table_w_array('tot_db_groups', array('group_name' => 'user','group_title' => 'user'));
}

function load_table_w_array($db, $array) {
    global $wpdb;
    $wpdb->insert(($wpdb->prefix . $db), $array);
}

function db_uninstall() {
    global $wpdb;

    // db field table
    $db_table_name = $wpdb->prefix . 'tot_db_fields';
    $wpdb->query("DROP TABLE IF EXISTS $db_table_name");

    // db records table
    $db_table_name = $wpdb->prefix . 'tot_user';
    $wpdb->query("DROP TABLE IF EXISTS $db_table_name");

    // db records table
    $db_table_name = $wpdb->prefix . 'tot_db_groups';
    $wpdb->query("DROP TABLE IF EXISTS $db_table_name");

    // db media table
    $db_media_name = $wpdb->prefix . 'tot_db_media';
    $wpdb->query("DROP TABLE IF EXISTS $db_media_name");
}

?>