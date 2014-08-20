<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.

Plugin Name: I've Read This
Plugin URI: http://github.com/tommcfarlin/ive-read-this/
Description: A simple plugin for allowing site members to mark when they've read a post.
Version: 1.0
Author: Tom McFarlin
Author URI: http://tommcfarlin.com/
Author Email: tom@tommcfarlin.com
License:
 
  Copyright 2012 Tom McFarlin (tom@tommcfarlin.com)
 
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.
 
  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
 
  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 
*/
 
class IveReadThis {
 
    /*--------------------------------------------*
     * Constructor
     *--------------------------------------------*/
 
    /**
     * Initializes the plugin by setting localization, filters, and administration functions.
     */
    function __construct() {
 
        load_plugin_textdomain( 'ive-read-this', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
 
        // Register site styles and scripts
        add_action( 'wp_enqueue_scripts', array( &$this, 'register_plugin_styles' ) );
        add_action( 'wp_enqueue_scripts', array( &$this, 'register_plugin_scripts' ) );
 
    } // end constructor
 
    /**
     * Registers and enqueues plugin-specific styles.
     */
    public function register_plugin_styles() {
 
        wp_register_style( 'ive-read-this', plugins_url( 'ive-read-this/css/plugin.css' ) );
        wp_enqueue_style( 'ive-read-this' );
 
    } // end register_plugin_styles
 
    /**
     * Registers and enqueues plugin-specific scripts.
     */
    public function register_plugin_scripts() {
 
        wp_register_script( 'ive-read-this', plugins_url( 'ive-read-this/js/plugin.js' ), array( 'jquery' ) );
        wp_enqueue_script( 'ive-read-this' );
 
    } // end register_plugin_scripts
 
} // end class
 
new IveReadThis();

/**
 * Adds a checkbox to the end of a post in single view that allows users who are logged in
 * to mark their post as read.
 *
 * @param   $content    The post content
 * @return              The post content with or without the added checkbox
 */
function add_checkbox( $content ) {
 
    // We only want to modify the content if the user is logged in
    if( is_user_logged_in() && is_single() ) {
 
        // Build the element that will be used to mark this post as read
        $html = '<div id="ive-read-this-container">';
            $html .= '<label for="ive-read-this">';
                $html .= '<input type="checkbox" name="ive-read-this" id="ive-read-this" value="0" />';
                $html .= __( "I've read this post.", 'ive-read-this' );
            $html .= '</label>';
        $html .= '</div><!-- /#ive-read-this-container -->';
 
        // Append it to the content
        $content .= $html;
 
    } // end if
 
    return $content;
 
} // end add_checkbox
?>
