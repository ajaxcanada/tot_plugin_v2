<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// The JavaScript
function my_action_javascript() {
  //Set Your Nonce
  $ajax_nonce = wp_create_nonce( 'my-special-string' );
  ?>
  <script>
  jQuery( document ).ready( function( $ ) {
 
    var data = {
      action: 'my_action',
      security: '<?php echo $ajax_nonce; ?>',
      whatever: 1234
    };
 
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    $.post( ajaxurl, data, function( response)  {
      alert( 'Got this from the server: ' + response );
    });
  });
  </script>
  <?php
}
add_action( 'admin_footer', 'my_action_javascript' );


