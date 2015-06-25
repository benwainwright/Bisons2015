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
          if ( is_object ( $query ) && isset ( $query->query['post_type'] ) ) 
                $current_post_type = $query->query['post_type'];

          // For each pair in the array
          foreach ( $restricted_area_permissions as $type => $permission )
          {
	            // Setup state for testing
	            $hasPermission = current_user_can( $permission );
	            $isSingle = $query->is_single;
	            $isType = $current_post_type == $type;
	            $isTypeArchive = $query->is_post_type_archive ( $type);
	            $userNeeds2FA = current_user_can( 'needs_two_factor_for_restricted_areas' );
	            $canSkip2FA = isset( $_SESSION['bisons_skip2FA'] ) ? $_SESSION['bisons_skip2FA'] : false ;
	            $twoFactorIsOn = get_option( 'bisonsTwoFactorAuth' );

          		if ( ( ! $hasPermission ) && ( ( $isSingle && $isType ) || $isTypeArchive ) )
	            {
                    // Redirect to the login url, passing the current url in the querystring
            		$url = wp_login_url( add_query_arg ( array() ) );
                    wp_redirect ( $url );
                    exit ( );
                }

	          // If on a restricted page with a user requiring 2FA and
	          // no skip session cookie
	          else if ( ( ( $isSingle && $isType ) || $isTypeArchive  ) && $userNeeds2FA  && ! $canSkip2FA && $twoFactorIsOn) {

		          wp_redirect ( site_url( '2FA.php?next=' . urlencode($_SERVER['REQUEST_URI']) ) );
		          exit();

	          }
          }
    }
}

add_action ( 'pre_get_posts', 'redirect_restricted_areas' );


function block_from_dashboard()
{

	$userNeeds2FA = current_user_can( 'needs_two_factor_for_restricted_areas' );
	$canSkip2FA = isset( $_SESSION['bisons_skip2FA'] ) ? $_SESSION['bisons_skip2FA'] : false ;
	$twoFactorIsOn = get_option( 'bisonsTwoFactorAuth' );
	$canSeeDashBoard = current_user_can( 'see_dashboard' );
	$isDashboard = is_admin();
	$isMainQuery = is_main_query();

    if ( $isDashboard && ! $canSeeDashBoard && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) )
    {
        $url = wp_login_url( add_query_arg ( array() ) );
        wp_redirect( $url );
        exit ( );

    } elseif ( $isDashboard && $isMainQuery && ! $canSkip2FA && $userNeeds2FA && $twoFactorIsOn ) {

	    wp_redirect ( site_url( '2FA.php?next=' . urlencode($_SERVER['REQUEST_URI']) ) );
	    exit();

    }
}
add_action ( 'init', 'block_from_dashboard'); 
