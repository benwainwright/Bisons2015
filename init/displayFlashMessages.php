<?php

global $bisonPlayersFlashMessage;
global $bisonsMembership;

$status = $bisonsMembership->getStatus( get_current_user_id() );

$hasJoined   = get_user_meta( get_current_user_id(), 'joined', true );
$noDD        = 'None' === $status;
$DDCancelled = 'cancelled' === $status;

if ( isset ( $_GET['nonce'] ) ) {
	$validFlashMessageInQueryString = wp_verify_nonce( $_GET['nonce'],
			'bisonsFlashmessageNonce' ) && isset( $_GET['flash'] );
}

if ( isset ( $validFlashMessageInQueryString ) ) {

	$bisonPlayersFlashMessage[] = array(
		'priority' => 10000,
		'message'  => $_GET['flash']
	);

} else if ( $hasJoined && ( $noDD || $DDCancelled ) ) {

	$bisonPlayersFlashMessage[] = array(
		'priority' => 1,
		'message'  => "Although you have submitted a membership form you still need to organise payment or your payment has been cancelled. Click the 'subs' link above to get it sorted!"
	);

} else if ( ! $hasJoined ) {

	$bisonPlayersFlashMessage[] = array(
		'priority' => 1,
		'message'  => "Looks like you are not yet a club member. Click the <strong>join</strong> link above to sign up!"
	);

}
