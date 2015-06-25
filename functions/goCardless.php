<?php
function BisonsCRONAddNewBill( $userID, $chargeDate, $chargeAmount ) {

	$subID = get_user_meta($userID, 'GCLSubID', true);

	try {
		$preAuth = GoCardless_PreAuthorization::find( $subID );

		$bill = array(
			'name'               => __( 'BisonsRFC Subscription Fee', 'bisonsRFC' ),
			'amount'             => pence_to_pounds($chargeAmount, false),
			'charge_customer_at' => $chargeDate
		);


		$preAuth->create_bill( $bill );

	} catch ( Exception $e ) {
		errorMessage($e);
	}

}

function cancelNextBill($id) {
	delete_user_meta($id, 'nextBillDate');
}

function bisonsScheduleNextPayment($id) {
	$nextPaymentDate = get_user_meta($id, 'nextBillDate', true);

	if ( $nextPaymentDate != getNextPaymentDate($id)) {
		$nextPaymentDate = getNextPaymentDate($id);
		$scheduleDate = $nextPaymentDate - 60 * 60 * 24 * 7;
		$scheduleDate = $scheduleDate > time() ? $scheduleDate : time();
		$chargeDate   = date( 'Y-m-d', $nextPaymentDate );
		$chargeAmount = get_user_meta( $id, 'currentFee', true );

		$args = array(
			$id,
			$chargeDate,
			$chargeAmount
		);

		if ( wp_next_scheduled( 'BisonsCRONAddNewBill', $args ) <= time() ) {
			wp_schedule_single_event( $scheduleDate, 'BisonsCRONAddNewBill', $args );
		}

		update_user_meta($id, 'nextBillDate', $nextPaymentDate);
	}
}