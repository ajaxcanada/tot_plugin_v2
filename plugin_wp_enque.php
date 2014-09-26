<?php

/* this is the php code to act on if the browser is capable of jquery */

function my_action_get_scripts() {
// THIS NEEDS SOME WORK TO PAGE THE ADD-IN SCRIPTS ONLY LOAD ON MY PAGE. 
// SO BEFORE PRODUCTION :)

    wp_deregister_script('jquery');

    wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
    wp_register_script('jquery', 'http://code.jquery.com/jquery-latest.js');
    wp_register_script('jquery', 'http://jquery-ui.googlecode.com/svn/tags/latest/ui/jquery.effects.core.js');
    wp_register_script('jquery', 'http://jquery-ui.googlecode.com/svn/tags/latest/ui/jquery.effects.slide.js');

    wp_enqueue_script('jquery');


    wp_register_script('my_action', plugins_url('ready.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_script('my_action');

    wp_localize_script('my_action', 'load_wp_AJAX', array(
        // URL to wp-admin/admin-ajax.php to process the request
        'ajaxurl' => admin_url('admin-ajax.php'),
        // generate a nonce with a unique ID "myajax-post-comment-nonce"
        // so that you can check it later when an AJAX request is sent
        'security' => wp_create_nonce('my-special-string')
    ));

    wp_register_script('admin_action', plugins_url('radmin.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_script('admin_action');
    wp_localize_script('admin_action', 'load_wp_AJAX', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('my-special-string')
    ));

    
    
}

add_action('wp_enqueue_scripts', 'my_action_get_scripts');
// THIS IS NEEDED IF NON REGISTERED USERS WILL BE IN THE DATABASE
//add_action( 'wp_ajax_nopriv_my_action', 'my_action_callback' );
?>