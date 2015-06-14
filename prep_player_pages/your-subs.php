<?php

// Enqueue form Javascript
wp_enqueue_script('dynamicforms');
wp_enqueue_script('formvalidation');

$data = &$wp_query->query['bisons_data'];

$form_user = ( isset ( $_GET['player_id'] ) && current_user_can( 'committee_perms' ) )
	? $_GET['player_id'] : get_current_user_id();


// If a membership form exists, load it from WordPress
if ( get_user_meta( $form_user, 'joined', true ) ) {

	$data['joined'] = true;
	$data['payMethod'] = get_user_meta( $form_user, 'payMethod', true ) ? get_user_meta( $form_user, 'payMethod', true ) : false;
	$data['payStatus'] = getDDStatus($form_user);
	$data['GCLUserID'] = get_user_meta( $form_user, 'GCLUserID', true ) ? get_user_meta( $form_user, 'GCLUserID', true ) : false;
	$data['query']  = new WP_Query(array ( 'post_type' => 'GCLBillLog', 'posts_per_page' => 10, 'author' => $form_user));
	$data['paymentInfo'] = getPaymentInfo($form_user);
	$data['subName'] = get_user_meta($form_user, 'mem_name', true) ?  get_user_meta($form_user, 'mem_name', true) : 'None';


} else {

	// If nomembership form found, redirect to the membership form with a flash message
	$flashmessage = 'No payment information is available just yet as you haven\'t joined the club. To put that right, fill in the form below!';
	wp_redirect( home_url( 'players-area/membership-form/?nonce=' . wp_create_nonce( 'bisons_flashmessage_nonce' ) . '&flash=' . urlencode( $flashmessage ) ) );
	exit();
}