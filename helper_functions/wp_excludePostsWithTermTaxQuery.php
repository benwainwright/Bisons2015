<?php

function wp_excludePostsWithTermTaxQuery( $tax )
{
	$tax_query = array(
		array(
			'taxonomy' => $tax,
			'field'    => 'slug'
		)
	);

	$taxonomy = get_terms( $tax );

	foreach ( $taxonomy as $tax ) {
		$taxesLight[] = $tax->slug;
	}

	$tax_query[0]['terms']    = $taxesLight;
	$tax_query[0]['operator'] = 'NOT IN';

	return $tax_query;

}
