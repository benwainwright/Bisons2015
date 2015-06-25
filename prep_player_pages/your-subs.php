<?php

global $bisonsMembership;
global $bisonPlayersFlashMessage;
global $wp_query;


$formUser = bisonsGetUser();


// If a membership form exists, load it from WordPress
if ( get_user_meta( $form_user, 'joined', true ) ) {

	$data = &$wp_query->query['bisons_data'];

	$data['user']            = $form_user;
	$data['joined']          = true;
	$data['payMethod']       = get_user_meta( $form_user, 'payMethod', true ) ? get_user_meta( $form_user,
		'payMethod', true ) : false;
	$data['payStatus']       =
	$data['currentMonthlyFee'] = pence_to_pounds( current_user_meta( 'currentFee' ), false );
	$data['GCLUserID']       = get_user_meta( $form_user, 'GCLUserID', true ) ? get_user_meta( $form_user,
		'GCLUserID', true ) : false;
	$data['query']           = new WP_Query( array(
		'post_type'      => 'GCLBillLog',
		'posts_per_page' => 10,
		'author'         => $form_user
	) );
	$data['paymentInfo']     = $bisonsMembership->getPaymentInfo( $form_user );
	$data['subName']         = get_user_meta( $form_user, 'GCLSubName', true ) ? get_user_meta( $form_user,
		'GCLSubName',
		true ) : 'None';
	$data['payWhen']         = get_user_meta( $form_user, 'payWhen', true );
	$data['nextPaymentDate'] = date( 'jS M, Y', $bisonsMembership->nextPaymentDate( get_current_user_id() ) );
	$data['dayOfMonth']      = get_user_meta( $form_user, 'dayOfMonth', true );

} else {


	// If no membership form found, redirect to the membership form with a flash message
	$flashMessage = "You haven&apos;t joined the club yet! To put that right, fill in the form below!";
	//wp_redirect( home_url( 'players-area/membership-form/?nonce=' . wp_create_nonce( 'bisonsFlashmessageNonce' ) . '&flash=' . urlencode( $flashMessage ) ) );
	exit();
}