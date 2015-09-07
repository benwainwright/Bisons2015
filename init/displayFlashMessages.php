<?php

global $bisonPlayersFlashMessage;
global $bisonsMembership;

$status = $bisonsMembership->getStatus( get_current_user_id() );

$hasJoined                      = get_user_meta( get_current_user_id(), 'joined', true );
$validFlashMessageInQueryString = wp_verify_nonce( $_GET['nonce'],
		'bisonsFlashmessageNonce' ) && isset( $_GET['flash'] );

if ( isset ( $_GET['nonce'] ) ) {
	$validFlashMessageInQueryString = wp_verify_nonce( $_GET['nonce'],
			'bisonsFlashmessageNonce' ) && isset( $_GET['flash'] );
}

if ( $validFlashMessageInQueryString ) {
	if ( ! $hasJoined ) {
		$bisonPlayersFlashMessage[] = array(
			'priority' => 1,
			'message'  => "Looks like you are not yet a club member. Click the <strong>join</strong> link above to sign up!"
		);

	}
}
