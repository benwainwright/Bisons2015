<?php


/**
 * Checks the master list of payment dates. If the result of nextPaymentDate()
 * is not the same as the payment date stored in the list for each user,
 * schedule a new
 */
function BisonsSchedulePayments() {

	$args = array(
		'meta_key'     => 'nextBillDate',
		'meta_compare' => '>',
		'meta_value'   => time()
	);

	$users = get_users( $args );

	foreach ( $users as $user ) {
		bisonsScheduleNextPayment($user->ID);
	}
}