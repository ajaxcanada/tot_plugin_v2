<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// creates a table with the name specified
// CREATE A TABLE
function create_table($new_table_name) {
    global $wpdb;
    // MAKE PROPER NAME
    $proper_name = str_replace(' ', '_', $new_table_name);
    // LOAD NEW TABLE NAME USING WORDPRESS 
    $db_table = $wpdb->prefix . 'tot_' . $proper_name;

// create the new database table
    if ($wpdb->get_var("show tables like '$db_table'") != $db_table) {
        $sql = "CREATE TABLE " . $db_table . " (
            `id`                int(3)      NOT NULL AUTO_INCREMENT,
            `user_id`           varchar(32) NOT NULL,
            `date_created`     timestamp   NOT NULL,
            `date_modified`     datetime    NOT NULL,
            UNIQUE KEY id (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        // put some data in field so table shows
        //ta
        // NOW ADD a NEW record for this id
        $group_array_data = [
            'group_name' => $proper_name,
            'group_title' => $new_table_name
        ];
        // UPDATE THE GROUP TABLE
        $db_table = $wpdb->prefix . 'tot_db_groups';
        $group_array_data_type = ['%s', '%s'];
        $wpdb->INSERT($db_table, $group_array_data, $group_array_data_type);


        //return "table:'$proper_name' created succesfully";
        return js_navigation($proper_name);
    } else {
        return 'table error, may be a table with that name already' . mysql_error();
    }
}

// ADD A COLUMN TO A TABLE
function add_column_to_table($new_column_name) {
    global $wpdb;
    global $myMsg;
    $new_field_type = "text";

    $table_name = check_user_last_access();
    $proper_column_name = str_replace(' ', '_', $new_column_name);

    // LOAD NEW TABLE NAME USING WORDPRESS AND tot_ 
    $db_table = $wpdb->prefix . 'tot_' . $table_name;
    $new_field_atributes = "text";

    // ADD THE COLUMN TO THE TABLE
    if ($proper_column_name != '') {
        mysql_query("ALTER TABLE " . $db_table . " ADD " . $proper_column_name . " " . $new_field_type);

        // NOW ADD a NEW record for this id
        $group_array_data = [
            'field_name' => $proper_column_name,
            'field_type' => $new_field_type,
            'field_title' => $new_column_name,
            'field_group' => $table_name
        ];
        // UPDATE THE GROUP TABLE
        $db_table = $wpdb->prefix . 'tot_db_fields';
        $group_array_data_type = ['%s', '%s', '%s'];
        $wpdb->INSERT($db_table, $group_array_data, $group_array_data_type);
        //$out .= " table=" . $db_table . " data=" . $group_array_data . " array=" . $group_array_data_type;
        return create_record_data_fields($table_name);
    }
}

function delete_table($table_name) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'tot_' . $table_name;
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}
