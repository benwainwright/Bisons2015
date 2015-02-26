<?php
function modify_query( $query ) {
	
	// Setup seasons archive page
	if ( !is_admin() && is_tax( 'seasons') && $query->is_main_query() )
	{
        $query->set( 'orderby', 'meta_value' );
		$query->set( 'meta_key', 'fixture-date' );
		$query->set('nopaging', 1);
	}
	
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
	
	// Setup fixtures archive
    if ( !is_admin() && $query->is_post_type_archive( 'fixtures' ) && $query->is_main_query() ) 
    {
    	
		$query->set( 'meta_key', 'fixture-date' );
        $query->set( 'orderby', 'meta_value' );
		$query->set('nopaging', 1);
		$query->set( 'order', 'ASC' );
		$taxonomy = get_terms ( array ( 'seasons' ) );
		foreach ($taxonomy as $tax) $taxeslight[] = $tax->slug;
		$meta_query = array(
					    array(
					        'taxonomy' => 'seasons',
					        'field'    => 'slug',
					        'terms'    => $taxeslight,
					        'operator' => 'NOT IN'
					    ));
						
		$query->set('tax_query', $meta_query);
		
	}

}
add_action( 'pre_get_posts', 'modify_query' );