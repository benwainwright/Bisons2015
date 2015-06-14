<?php
function prepare_pages( $the_query )
{

	global $wp_query;

    if ( $wp_query->is_main_query() )
    {
        if ( ! is_object ( $wp_query ) )
            return false;
        
		if (isset ( $wp_query->query['post_type'] ) && ! is_archive() ) {
	        switch ( $wp_query->query['post_type'] )
	        {

	            case 'player-page':
	                if( file_exists( dirname( __FILE__  ) . '/../prep_player_pages/' .  $wp_query->query['name'] . '.php' ) )
	                   include_once( dirname( __FILE__  ) . '/../prep_player_pages/' . $wp_query->query['name'] . '.php' );
	            break;

	        }
		}

	    $wp_query->query_vars['id'];
    }

}
add_action ( 'wp', 'prepare_pages' );
