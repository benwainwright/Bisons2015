<?php

function redirect_restricted_areas( $query )
{
    global $pagenow; 
    
    // Determine whether we are currently viewing the login page
    $on_login_page = in_array($pagenow, array(
            'wp-login.php',
            'wp-register.php'
    ));

    // Array containing post types and permissions needed to view them
    $restricted_area_permissions = array (
          'player-page'     => 'view_players_area',
          'committee-page'  => 'view_committee_area'
    );
      
      
    // If not viewing login page and the main query is being handled
    if ( ! $on_login_page && !is_admin() && $query->is_main_query() )
    {
          // If the query is an object, get the current post type
          if ( is_object ( $query ) ) 
                $current_post_type = $query->query['post_type'];
          
          // For each pair in the array
          foreach ( $restricted_area_permissions as $type => $permission )
          {
                  // If the user has the permission and the current page 
                  // is a single pageOR the archive for that post type
                  // 
          		if ( ( ! current_user_can( $permission ) ) 
          		&& ( ( $query->is_single && $current_post_type == $type ) 
          		|| $query->is_post_type_archive ( $type)  ) )     
                {
                    // Redirect to the login url, passing the current url in the querystring
            		$url = wp_login_url( add_query_arg ( array() ) );
                    wp_redirect ( $url );
                        
                    // Stop script execution
                    exit ( );
                }       
          }      
    }
}

add_action ( 'pre_get_posts', 'redirect_restricted_areas' );


function block_from_dashboard()
{
    if ( is_admin() && ! current_user_can( 'see_dashboard' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) 
    {
        $url = wp_login_url( add_query_arg ( array() ) );
        wp_redirect( $url );
        exit ( );
    }
}
add_action ( 'init', 'block_from_dashboard'); 
