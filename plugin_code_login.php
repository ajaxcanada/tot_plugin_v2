<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//  ADD LOGIN MENU
function add_loginout_link($items, $args) {
    if (is_user_logged_in() && $args->theme_location == 'primary-menu') {
        $items .= '<li><a href="' . wp_logout_url() . '">Log Out</a></li>';
    } elseif (!is_user_logged_in() && $args->theme_location == 'primary-menu') {
        $items .= '<li><a href="' . site_url('wp-login.php') . '">Log In</a></li>';
        $items .= '<li><a href="' . wp_registration_url() . '" title="' . __('Register') . '">' . __('Register') . '</a>';
    }
    return $items;
}
// WP CRAP TO HOOK MENU IN
add_filter('wp_nav_menu_items', 'add_loginout_link', 10, 3);

// ====================================================
add_action('woo_nav_inside', 'woo_custom_add_searchform', 10);


//DOESNT WORK LOKL
//
//function redirect_login_page(){
//
// // Store for checking if this page equals wp-login.php
// $page_viewed = basename($_SERVER['REQUEST_URI']);
//
// // Where we want them to go
// $login_page  = 'http://www.toolsontools.com/';
//
// // Two things happen here, we make sure we are on the login page
// // and we also make sure that the request isn't coming from a form
// // this ensures that our scripts & users can still log in and out.
// if( $page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
//
//  // And away they go...
//  wp_redirect($login_page);
//  exit();
//
// }
//}
//
//add_action('init','redirect_login_page');