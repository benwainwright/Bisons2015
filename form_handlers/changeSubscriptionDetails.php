<?php
if ( ! INCLUDED ) {
	exit;
}

// Access globals
global $bisonsMembership;


// Declare tests
$status                  = $bisonsMembership->getStatus( $form_user );
$membershipIsActive      = ! ( 'None' === $status || 'cancelled' === $status );
$hasSubmittedConfirmForm = isset( $_POST['confirm_change'] );


if ( $hasSubmittedConfirmForm ) {
	$didConfirm = $_POST['confirm_change'] === 'ok';
} else {
	$didConfirm = false;
}

if ( ! $didConfirm && $membershipIsActive ) {

	$nextFeeData = $bisonsMembership->calculateNextFeeOnDateChange();
	$hypDate     = date( 'jS \o\f F Y', $nextFeeData['nextPaymentDate'] );

	switch ( $_POST['payWhen'] ) {

		case "first":
			$newPaymentString = "the <strong>first</strong> day of every month";
			break;

		case "last":
			$newPaymentString = "the <strong>last</strong> day of every month";
			break;

		case "specificDay":
			$which = $_POST['dayOfMonth'];
			$ends  = array( 'th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th' );
			if ( ( $which % 100 ) >= 11 && ( $which % 100 ) <= 13 ) {
				$abbreviation = $which . 'th';
			} else {
				$abbreviation = $which . $ends[ $which % 10 ];
			}
			$newPaymentString = "the <strong>$abbreviation</strong> day of every month";
			break;

		case "specific":
			$whichWeekdayPos  = $_POST['whichWeekDay'];
			$whichWeekday     = $_POST['weekDay'];
			$newPaymentString = "the <strong>$whichWeekdayPos $whichWeekday</strong> of every month";
	}


	setlocale( LC_MONETARY, 'en_GB.UTF-8' );
	$difference = pence_to_pounds( abs( $nextFeeData['differenceInPence'] ) );
	$nextFee    = pence_to_pounds( $nextFeeData['nextFee'] );
	$diffDays   = abs( $nextFeeData['differenceInDays'] );
	$currentFee = pence_to_pounds( $nextFeeData['currentFee'] );
	$diffDays   = $diffDays > 1 ? $diffDays . ' days' : $diffDays . ' day';


	if ( $nextFeeData['differenceInDays'] > 0 ) {
		$forwardOrBack      = 'back';
		$increasedOrReduced = 'increased';
	} else if ( $nextFeeData['differenceInDays'] < 0 ) {
		$forwardOrBack      = 'forward';
		$increasedOrReduced = 'reduced';
	}

	if ( $forwardOrBack ) {

		$flashMessage = "Moving your payment date to the <strong>$newPaymentString</strong> would mean moving your next payment date $forwardOrBack by <strong>" . $diffDays .
		                "</strong>. To account for this, your next payment (which will be charged on the <strong>$hypDate</strong>) " .
		                "will be $increasedOrReduced by <strong>$difference</strong> to <strong>$nextFee</strong>. All following payments will be made at the usual amount of <strong>$currentFee</strong>" .
		                "<input type='hidden' name='payWhen' value='" . $_POST['payWhen'] . "' /> " .
		                "<input type='hidden' name='dayOfMonth' value='" . $_POST['dayOfMonth'] . "' /> " .
		                "<input type='hidden' name='whichWeekDay' value='" . $_POST['whichWeekDay'] . "' /> " .
		                "<input type='hidden' name='weekDay' value='" . $_POST['weekDay'] . "' /> " .
		                "<input type='hidden' name='nextFee' value='$nextFee' /> " .
		                "<input type='hidden' name='nonce' value=" . wp_create_nonce( 'wordpress_form_submit' ) . " />" .
		                "<input type='hidden' name='wp_form_id' value='changeSubscriptionDetails' />";;


		$bisonPlayersFlashMessage[] = array(
			'priority'       => 10,
			'message'        => $flashMessage,
			'confirmButtons' => true
		);
	}
} else if ( $didConfirm || ! $membershipIsActive ) {

	$bisonsMembership->updateMembershipInfo();

	$gclSubID = get_user_meta( $form_user, 'GCLSubID', true );

	if ( $gclSubID ) {
		$bisonsMembership->remoteFindPreAuth( $gclSubID );
	}

	if ( $bisonsMembership->preAuth->status !== 'active' ) {
		$bisonsMembership->getGCLUrl();
	} else {
		$nextDate = date( 'Y-m-d', $bisonsMembership->nextPaymentDate( $form_user ) );
		$bisonsMembership->requestNextBill( $form_user, $_POST['nextFee'], $nextDate );
	}


}