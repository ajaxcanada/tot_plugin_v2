<?php
/*
  Plugin Name: ToolsOnTools Plugin V3
  Description: Custom Database Interface
  Version: 2.0.0
  Author: Allen Jacques
  Author URI: http://toolsontools.com/
 */
require WP_CONTENT_DIR . '/plugins/tot_plugin_files/plugin_activations.php';
require WP_CONTENT_DIR . "/plugins/tot_plugin_files/plugin_code_db.php";
require WP_CONTENT_DIR . "/plugins/tot_plugin_files/plugin_code_forms.php";
require WP_CONTENT_DIR . "/plugins/tot_plugin_files/plugin_process_forms.php";
require WP_CONTENT_DIR . "/plugins/tot_plugin_files/plugin_main_form.php";
require WP_CONTENT_DIR . "/plugins/tot_plugin_files/plugin_code_css.php";
require WP_CONTENT_DIR . "/plugins/tot_plugin_files/plugin_sms_notifications.php";
require WP_CONTENT_DIR . "/plugins/tot_plugin_files/plugin_defines.php";
require WP_CONTENT_DIR . "/plugins/tot_plugin_files/AJAX_PHP.php";
// ADDED FOR SMS CAPABILITY
require WP_CONTENT_DIR . "/plugins/tot_plugin_files/twilio/Services/Twilio.php";
// NOTE TO SELF. KEEP THIS REGISTER_ACTIVATION_HOOK's THAT ADD TABLES IN THE FIRST FILE THAT LOADS. 
// THEY DONT WORK IN AN INCLUDED FILE. WELCOME TO WORDPRESS :)
register_activation_hook(__FILE__, 'install_plugin_create_records_table'); // used to be records
register_activation_hook(__FILE__, 'install_plugin_create_fields_table'); // used to be fields
register_activation_hook(__FILE__, 'install_plugin_create_groups_table'); // used to be groups
register_activation_hook(__FILE__, 'insert_data'); // inserts initial data into tables
register_deactivation_hook(__FILE__, 'db_uninstall'); // uninstalls tables
// THIS LOADS CSS IN THE HEADER

function db_css_includes() { // included for styling 
    //	load css specific files
    wp_register_style("db_styling", plugins_url('style.css', __FILE__));
    wp_enqueue_style("db_styling");
}

add_action('wp_head', 'db_css_includes'); //activates the style.css
// ====================================================
//  jQuery core
//  THIS LOADS JAVASCRIPT IN THE HEADER
//  NOTE TO SELF. THIS CODE ONLY WORKS IF THERE ARE SINGLE QUOTES ON ('ready.js', ... DONT USE ("ready.js"), 
function tot_plugin_js_includes() {
    wp_enqueue_script("jquery");
    wp_register_script("tot_ready", plugins_url('ready.js', __FILE__));
    wp_enqueue_script("tot_ready", plugins_url('ready.js', __FILE__));
}

add_action('wp_head', 'tot_plugin_js_includes');
// =======================================================
// LOGIN/LOGOUT
add_filter('wp_nav_menu_items', 'add_loginout_link', 10, 3);

function add_loginout_link($items, $args) {
    if (is_user_logged_in() && $args->theme_location == 'primary-menu') {
        $items .= '<li><a href="' . wp_logout_url() . '">Log Out</a></li>';
    } elseif (!is_user_logged_in() && $args->theme_location == 'primary-menu') {
        $items .= '<li><a href="' . site_url('wp-login.php') . '">Log In</a></li>';
        $items .= '<li><a href="' . wp_registration_url() . '" title="' . __('Register') . '">' . __('Register') . '</a>';
    }
    return $items;
}

// SECURITY AND LOAD WORDPRESS AJAX REQUIRED
function theme_name_scripts() {
    wp_enqueue_script('script-name', get_template_directory_uri() . '/js/example.js', array('jquery'), '1.0.0', true);
    wp_localize_script('script-name', 'MyAjax', array(
        // URL to wp-admin/admin-ajax.php to process the request
        'ajaxurl' => admin_url('admin-ajax.php'),
        // generate a nonce with a unique ID "myajax-post-comment-nonce"
        // so that you can check it later when an AJAX request is sent
        'security' => wp_create_nonce('my-special-string')
    ));
}
add_action('wp_enqueue_scripts', 'theme_name_scripts');

// The function that handles the AJAX request
function my_action_callback() {
    check_ajax_referer('my-special-string', 'security');
    $db_name = filter_input(INPUT_POST, 'dbase', FILTER_SANITIZE_SPECIAL_CHARS);
    console.log('php');
    echo do_get_db_fields($db_name);
    die(); // this is required to return a proper result
    //  $whatever = intval( $_POST['whatever'] );
    //  $whatever += 20;
    //  echo $whatever;
}
add_action('wp_ajax_my_action', 'my_action_callback');


// TRYING TO HOOK THIS INTO AJAX TO GET AN OUTPUT FROM DATABASE 082414
function do_get_db_fields($db_name) {
    global $wpdb;
    $table_name = $wpdb->prefix . "tot_db_groups"; // load db records
    $query = "SELECT * FROM {$table_name}"; // WHERE user_id={$user_id}"; // records string to pass to mysql query
    $result = mysql_query($query) or die(mysql_error());
    while ($row = mysql_fetch_assoc($result)) {  // load the group_rows of fields data 
        //CYCLE THROUGH THE DATA ROW BY ROW TO PULL THE TABLE DATA
        foreach ($row as $fieldname => $fieldvalue) {
            // HERE TO FILTER OFF SOME FIELDS.
            //switch ($fieldname) {
                //case 'id':
                //case 'user_id':
                //case 'date_recorded':
                //    break;
                //default:
                    //$out .= "<label for='$fieldname'>$fieldname</label>";
                    $out .= $fieldname . " " . $fieldvalue;
                //$out .= $fieldname." ". $fieldvalue." "; // BUILD THE RECORD INFOR the new record name
            //}
            $out .= " "; // BUILD THE RECORD INFOR the new record name
        }
        $out .= " <br> "; // BUILD THE RECORD INFOR the new record name
    }

    return $db_name.$out;
}

// ====================================================
//add_action( 'woo_nav_inside', 'woo_custom_add_searchform', 10 );
?>