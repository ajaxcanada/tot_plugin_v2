<?php



/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


// ====================================================
function FUNCTION_NAME() {
    // Step 1 - REGISTER THE SCRIPT 
    // Registers a script file in WordPress to be linked to a page later using the wp_enqueue_script() 
    //      function, which safely handles the script dependencies.    
    // wp_register_script( $handle,  $src,                              $deps,  $ver,       $in_footer );
    wp_register_script("function_name", plugins_url('ready.js', __FILE__));
    //  $handle     name of the registered script handle we are attaching the data for
    //  $src        URL to the script USE: plugins_url('ready.js',__FILE__))
    //  $deps       ARRAY() The name of the variable which will contain the data
    //==========================================================================
    // Step 2 - LOCALIZE THE SCRIPT WITH OUR DATA IF NEEDED
    // USE THIS FOR UI STRINGS SO THEY CAN BE LOCLAIZED
    // THIS IS USED ON THE FRONT END
    // Localizes a registered script with data for a JavaScript variable.
    //      This lets you offer properly localized translations of any strings used in your script. 
    //      This is necessary because WordPress currently only offers a localization API in PHP, 
    //      not directly in JavaScript. Though localization is the primary use, 
    //      it can be used to make any data available to your script that you can normally only get from the server side of WordPress.
    // REFERENCE wp_localize_script($handle,    $name,            $data );
    wp_localize_script('function_name', 'my_ajax_script', array('ajaxurl' => admin_url('admin-ajax.php')));
    // $handle      The registered script handle you are attaching the data for
    // $name        The name of the variable which will contain the data
    // $data        The data itself. The data can be either a single- or multi- (as of 3.3) dimensional array. Like json_encode(), 
    //              the data will be a JavaScript object if the array is an associate array (a map), otherwise the array will be a JavaScript array.
    //==========================================================================
    // Step 3 - ENQUEUE THE FUNCTION
    // Links a script file to the generated page at the right time according to the script dependencies, 
    //      if the script has not been already included and if all the dependencies have been registered. 
    //      You could either link a script with a handle previously registered using the wp_register_script() function, 
    //      or provide this function with all the parameters necessary to link a script.
    //  ***** This is the recommended method of linking JavaScript to a WordPress generated page.
    //  REFERENCE wp_enqueue_script( $handle,   $src,               $deps, $ver, $in_footer );
    wp_enqueue_script("function_name", plugins_url('ready.js', __FILE__));
    // $handle      The registered script handle you are attaching the data for
    //  $src        URL to the script USE: plugins_url('ready.js',__FILE__))
    //  $deps       ARRAY() The name of the variable which will contain the data
    // wp_enqueue_script( 'function', get_template_directory_uri().'/my_js_stuff.js', 'jquery', true);
}

// STEP 4 HOOK INTO WORDPRESS
// Hooks a function on to a specific action.
// add_action( $hook, $function_to_add, $priority, $accepted_args
// *** DONT NEED THIS HERE AS THIS IS JUST AJAX 
// *** add_action('WP_FUNCTION_NAME', 'FUNCTION_NAME');
// MAKES AJAX FUNCTIONS AVAILABLE ON CLIENT SIDE *******
add_action("wp_ajax_nopriv_your_FUNCTION_NAME", "FUNCTION_NAME");  // add for non registered users
// MAKES AJAX FUNCTIONS AVAILABLE ON SERVER SIDE *******
add_action("wp_ajax_your_FUNCTION_NAME", "FUNCTION_NAME"); // for admin - valid loggged in users 
// $hook                The name of the action to which $function_to_add is hooked
// $function_to_add     The name of the function you wish to be hooked
//
//// =======================================================
//function pw_load_scripts() {
//    wp_enqueue_script('pw-script', plugin_dir_url(__FILE__) . 'js/pw-script.js');
//}
//add_action('wp_enqueue_scripts', 'pw_load_scripts');
// ====================================================
//   ================  WordPress AJAX Backend Example
//// The JavaScript
//function my_action_javascript() {
//  //Set Your Nonce
//  $ajax_nonce = wp_create_nonce( 'my-special-string' );
//  ? >
//  < script >
//  jQuery( document ).ready( function( $ ) {
// 
//    var data = {
//      action: 'my_action',
//      security: '//< ? php echo $ajax_nonce;  ? > ',
//      whatever: 1234
//    };
// 
//    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
//    $.post( ajaxurl, data, function( response)  {
//      alert( 'Got this from the server: ' + response );
//    });
//  });
//  < / script >
// < ? php
//}
//add_action( 'admin_footer', 'my_action_javascript' );
// 
//// The function that handles the AJAX request
//function my_action_callback() {
//  global $wpdb; // this is how you get access to the database
// 
//  check_ajax_referer( 'my-special-string', 'security' );
//  $whatever = intval( $_POST['whatever'] );
//  $whatever += 10;
//  echo $whatever;
// 
//  die(); // this is required to return a proper result
//}
//add_action( 'wp_ajax_my_action', 'my_action_callback' );
