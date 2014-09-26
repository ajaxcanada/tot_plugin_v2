<?php

/*
  Plugin Name: ToolsOnTools Plugin V3
  Description: Custom Database Interface
  Version: 2.0.0
  Author: Allen Jacques
  Author URI: http://toolsontools.com/
 */
// REQUIRED FILES
require WP_CONTENT_DIR . '/plugins/tot_plugin_files/AJAX_PHP.php';
require WP_CONTENT_DIR . '/plugins/tot_plugin_files/plugin_activations.php';
require WP_CONTENT_DIR . '/plugins/tot_plugin_files/plugin_code_css.php';
require WP_CONTENT_DIR . '/plugins/tot_plugin_files/plugin_forms_db.php';
require WP_CONTENT_DIR . '/plugins/tot_plugin_files/plugin_defines.php';
require WP_CONTENT_DIR . '/plugins/tot_plugin_files/plugin_form_main.php';
require WP_CONTENT_DIR . '/plugins/tot_plugin_files/plugin_form_manager.php';
require WP_CONTENT_DIR . '/plugins/tot_plugin_files/plugin_process_forms.php';
require WP_CONTENT_DIR . '/plugins/tot_plugin_files/plugin_sms_notifications.php';
require WP_CONTENT_DIR . '/plugins/tot_plugin_files/plugin_wp_enque.php';
require WP_CONTENT_DIR . '/plugins/tot_plugin_files/plugin_table_manager.php';
require WP_CONTENT_DIR . '/plugins/tot_plugin_files/plugin_form_admin.php';

// ADDED FOR SMS CAPABILITY
require WP_CONTENT_DIR . '/plugins/tot_plugin_files/twilio/Services/Twilio.php';

/*
 * 
// NOTE TO SELF. KEEP THIS REGISTER_ACTIVATION_HOOK's THAT ADD TABLES IN THE FIRST FILE THAT LOADS. 
// THEY DONT WORK IN AN INCLUDED FILE. WELCOME TO WORDPRESS :)
 * 
 */

// ACTIVATE/DEACTIVATE FOR TESTING

register_activation_hook(__FILE__, 'install_plugin_create_records_table'); // records
register_activation_hook(__FILE__, 'install_plugin_create_fields_table'); // fields
register_activation_hook(__FILE__, 'install_plugin_create_groups_table'); // groups
register_activation_hook(__FILE__, 'install_plugin_create_media'); // media
register_activation_hook(__FILE__, 'insert_data'); // inserts initial data into tables
register_deactivation_hook(__FILE__, 'db_uninstall'); // uninstalls tables

// THIS LOADS CSS IN THE HEADER
function plugin_css_includes() { // included for styling 
    //	load css specific files
    wp_register_style("plugin_styling", plugins_url('style.css', __FILE__));
    wp_enqueue_style("plugin_styling");

    wp_register_style("main_form_styling", plugins_url('style-main.css', __FILE__));
    wp_enqueue_style("main_form_styling");

    wp_register_style("record_form_styling", plugins_url('style-manager.css', __FILE__));
    wp_enqueue_style("record_form_styling");

    wp_register_style("admin_form_styling", plugins_url('style-admin.css', __FILE__));
    wp_enqueue_style("admin_form_styling");

}

add_action('wp_head', 'plugin_css_includes'); //activates the style.css
?>