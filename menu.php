<?php 
$params = array(
          'menu' => 'nav_bar',
          'container' => 'nav' );

if ( is_user_logged_in () ) $params [ 'container_class' ] = 'loggedinmenu';
wp_nav_menu( $params ); 