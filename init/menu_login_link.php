<?php
//Add login/logout link to naviagation menu
function add_login_out_item_to_menu( $items, $args ){

	//change theme location with your them location name
	if( is_admin() ||  $args->theme_location != 'Main Menu' )
		return $items; 

	$redirect = ( is_home() ) ? false : get_permalink();
	if( is_user_logged_in( ) )
		$link = 's<a href="' . wp_logout_url( bloginfo( 'url' ) ) . '" title="' .  __( 'Logout', 'bisonsrfc' ) .'">' . __( 'Logout', 'bisonsrfc' ) . '</a>';
	else  $link = '<a href="' . wp_login_url( bloginfo( 'url' ) . '/players-area/' ) . '" title="' .  __( 'Login', 'bisonsrfc' ) .'">' . __( 'Login', 'bisonsrfc' ) . '</a>';
	return $items.= '<li id="log-in-out-link" class="menu-item menu-type-link">'. $link . '</li>';
}

add_filter( 'wp_nav_menu_items', 'add_login_out_item_to_menu', 50, 2 );
