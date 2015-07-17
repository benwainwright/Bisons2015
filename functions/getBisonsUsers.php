<?php

function getBisonsUsers( $inactive = false )
{

	if ( ! $inactive ) {
		return get_users( array( 'meta_key' => 'inActive', 'meta_compare' => 'NOT EXISTS' ) );
	}

	else {
		return get_users( array( 'meta_key' => 'inActive', 'meta_compare' => 'EXISTS' ) );
	}
}