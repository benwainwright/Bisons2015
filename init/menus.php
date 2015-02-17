<?php

// Register nav menus
if (function_exists( 'register_nav_menus' ) ) {
    register_nav_menus(
        array( 'nav_bar'    => 'Top navigation bar',
               'login_menu' => 'Widget login menu')
    );
}

function add_restricted_menus($items) {

      
      
    global $current_user; 
      
    if( ! is_user_logged_in() ) {
        
        $items .= "<li><a class='loginout' href='". wp_login_url( '/players-area/'  )."'>Login</a></li>";
    } else {
        
        if ( current_user_can ( 'view_players_area' ) ) $items .= "<li><a href='".site_url('/players-area/')."'>Player's Area</li>";              
        $items .= "<li><a class='loginout' href='". wp_logout_url( '/' )."'>Logout</a></li>";

    }
    return $items;
}
 
add_action('wp_nav_menu_items', 'add_restricted_menus'); 