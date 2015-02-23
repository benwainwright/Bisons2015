<?php
function modify_query( $query ) {
	
	// Setup events archive
    if ( !is_admin() && $query->is_post_type_archive( 'events' ) && $query->is_main_query() ) 
    {
        $query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', 'date' );
		$query->set('nopaging', 1);

		$query->set( 'order', 'ASC' );
		$meta_query = array (
		
	    array(
	        'key'       =>  'ical_only',
	        'compare'   =>  'NOT EXISTS'
	    ) );
		
		$query->set( 'meta_query', $meta_query);

    }
}
add_action( 'pre_get_posts', 'modify_query' );