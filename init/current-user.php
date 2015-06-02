<?php

global $current_user;
global $pagenow;
$id = wp_validate_auth_cookie ( );
if ( $id ) wp_set_current_user ( $id );
if ( ! is_object ( $current_user ) ) { 
      get_currentuserinfo();
} else if ( ! $current_user->ID ) {
      get_currentuserinfo();
}
$on_admin_page =  in_array( $pagenow, array( 'wp-login.php', 'wp-register.php' ) );