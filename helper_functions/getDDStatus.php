<?php

function getDDStatus($id) {
	if ( get_user_meta( $id, 'payMethod', true ) == 'single' ) {

		// Work out if there is single payment for the current season
		$userSinglePaymentID = get_user_meta( $id, 'singlePaymentID', true );

		$taxQuery = wp_excludePostsWithTermTaxQuery( 'seasons' );

		$queryArray = array(
			'post_type'  => 'GCLBillLog',
			'meta_query' => 'id',
			'meta_value' => $userSinglePaymentID,
			'tax_query'  => $taxQuery
		);

		$query  = new WP_Query( $queryArray );

		$dd_status = $query->post_count ? 'Paid in Full' : 'Unpaid';

	} else {

		$dd_status = get_user_meta( $id, 'GCLsubscriptionStatus', true );

	}

	return $dd_status ? $dd_status : 'None';
}