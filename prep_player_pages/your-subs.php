<?php

global $bisonsMembership;
global $bisonPlayersFlashMessage;
global $wp_query;


$formUser = bisonsGetUser();

if ( isset ( $_GET['resource_id'] ) ) {
	global $bisonsMembership;
	$bisonsMembership->confirmPreauth($_GET, $formUser);
}

// If a membership form exists, load it from WordPress
if ( get_user_meta( $formUser, 'joined', true ) ) {

	$data = &$wp_query->query['bisons_data'];


	$fees = new WP_Query ( array( 'post_type' => 'membership_fee', 'nopaging' => true ) );
	while ( $fees->have_posts() ) {
		$fees->the_post();

		$the_fee = array(
			'id'              => get_the_id(),
			'name'            => get_post_meta( get_the_id(), 'fee-name', true ),
			'initial-payment' => get_post_meta( get_the_id(), 'initial-payment', true ),
			'amount'          => get_post_meta( get_the_id(), 'fee-amount', true ),
			'description'     => get_post_meta( get_the_id(), 'fee-description', true )
		);

		$isSupporter = get_post_meta( get_the_id(), 'supporter-player', true ) === 'Supporter';
		$isPlayer    = get_post_meta( get_the_id(), 'supporter-player', true ) === 'Player';
		$isMonthly   = get_post_meta( get_the_id(), 'fee-type', true ) == "Monthly Direct Debit";

		if ( $isSupporter && $isMonthly ) {
			$data['supporterFees']['direct_debits'] [] = $the_fee;
		} else if ( $isSupporter && ! $isMonthly ) {
			$data['supporterFees']['single_payments'] [] = $the_fee;
		} else if ( $isPlayer && $isMonthly ) {
			$data['playerFees']['direct_debits'] [] = $the_fee;
		} else if ( $isPlayer && ! $isMonthly ) {
			$data['playerFees']['single_payments'] [] = $the_fee;
		}

	}

	$method = get_user_meta( $formUser, 'payMethod', true );

	if ( 'dd' === $method ) {

		$feeid = ( get_user_meta( $formUser, 'playermembershiptypemonthly', true ) != '' )
			? get_user_meta( $formUser, 'playermembershiptypemonthly', true )
			: get_user_meta( $formUser, 'supportermembershiptypemonthly', true );

	} elseif ( 'sp' == $method ) {

		$feeid = ( get_user_meta( $formUser, 'playermembershiptypesingle', true ) != '' )
			? get_user_meta( $formUser, 'playermembershiptypesingle', true )
			: get_user_meta( $formUser, 'supportermembershiptypesingle', true );

	}

	$data['description'] = get_post_meta($feeid, 'fee-description', true);


	$data['user']            = $formUser;
	$data['joined']          = true;
	$data['payMethod']       = get_user_meta( $formUser, 'payMethod', true ) ? get_user_meta( $formUser,
		'payMethod', true ) : false;
	$data['currentMonthlyFee'] = pence_to_pounds( current_user_meta( 'currentFee' ), false );
	$data['GCLUserID']       = get_user_meta( $formUser, 'GCLUserID', true ) ? get_user_meta( $formUser,
		'GCLUserID', true ) : false;
	$data['query']           = new WP_Query( array(
		'post_type'      => 'GCLBillLog',
		'posts_per_page' => 10,
		'author'         => $formUser
	) );
	$data['paymentInfo']     = $bisonsMembership->getPaymentInfo( $formUser );
	$data['subName']         = get_user_meta( $formUser, 'GCLSubName', true ) ? get_user_meta( $formUser,
		'GCLSubName',
		true ) : 'None';
	$data['payWhen']         = get_user_meta( $formUser, 'payWhen', true );
	$data['nextPaymentDate'] = date( 'jS M, Y', $bisonsMembership->nextPaymentDate( get_current_user_id() ) );
	$data['dayOfMonth']      = get_user_meta( $formUser, 'dayOfMonth', true );

} else {


	// If no membership form found, redirect to the membership form with a flash message
	$flashMessage = "You haven&apos;t joined the club yet! To put that right, fill in the form below!";
	//wp_redirect( home_url( 'players-area/membership-form/?nonce=' . wp_create_nonce( 'bisonsFlashmessageNonce' ) . '&flash=' . urlencode( $flashMessage ) ) );
	exit();
}