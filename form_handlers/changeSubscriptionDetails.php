<?php
if ( ! INCLUDED ) {
	exit;
}

// Access globals
global $bisonsMembership;

$formUser = bisonsGetUser();

// Declare tests
$status                  = $bisonsMembership->getStatus( $formUser );
$membershipIsActive      = ! ( 'None' === $status || 'cancelled' === $status );
$hasSubmittedConfirmForm = isset( $_POST['confirm_change'] );
$changingFromDDtoSP = 'sp' === $_POST['payMethod'] && 'dd' == get_user_meta($formUser, 'payMethod', true);

if ( $hasSubmittedConfirmForm ) {
	$didConfirm = $_POST['confirm_change'] === 'ok';
} else {
	$didConfirm = false;
}

if ( ! $didConfirm && $membershipIsActive ) {

	if ( $changingFromDDtoSP ) {

		$seasons = wp_list_pluck( get_terms( 'seasons' ), 'name' );
		$soFar = 0;

		$args  = array(
			'post_type' => 'GCLBillLog',
			'author' => $formUser,
			'posts_per_page' => - 1 );

		$query = new WP_Query( $args );

		while ( $query->have_posts() ) {
			$query->the_post();

			$billHasBeenPaidOrWithdrawn = 'paid' === get_post_meta ( get_the_id(), 'status', true)
			                              ||  'withdrawn' === get_post_meta ( get_the_id(), 'status', true);

			if ( $billHasBeenPaidOrWithdrawn ) {
				$soFar += get_post_meta( get_the_id(), 'amount', true );
			}
		}

		if ( 'dd' === $method ) {

			$feeid = ( get_user_meta( $formUser, 'playermembershiptypemonthly', true ) != '' )
				? get_user_meta( $formUser, 'playermembershiptypemonthly', true )
				: get_user_meta( $formUser, 'supportermembershiptypemonthly', true );

		} elseif ( 'sp' == $method ) {

			$feeid = ( get_user_meta( $formUser, 'playermembershiptypesingle', true ) != '' )
				? get_user_meta( $formUser, 'playermembershiptypesingle', true )
				: get_user_meta( $formUser, 'supportermembershiptypesingle', true );
		}

		$amount = (int) get_post_meta($feeid, 'fee-amount', true) - $soFar;
		$soFarInPounds = pence_to_pounds($soFar);
		$amountInPounds = pence_to_pounds($amount);

		$flashMessage = "Since you have already paid $soFarInPounds this season, pressing OK will submit a bill for the remaining amount of $amountInPounds" .
		                "<input type='hidden' name='nextFee' value='$amount' /> " .
		                "<input type='hidden' name='nonce' value=" . wp_create_nonce( 'wordpress_form_submit' ) . " />" .
		                "<input type='hidden' name='wp_form_id' value='changeSubscriptionDetails' />";
		$bisonPlayersFlashMessage[] = array(
			'priority'       => 10,
			'message'        => $flashMessage,
			'confirmButtons' => true
		);
	} else {
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
			                "<input type='hidden' name='payMethod' value='" . $_POST['payMethod'] . "' /> " .
			                "<input type='hidden' name='payWhen' value='" . $_POST['payWhen'] . "' /> " .
			                "<input type='hidden' name='dayOfMonth' value='" . $_POST['dayOfMonth'] . "' /> " .
			                "<input type='hidden' name='whichWeekDay' value='" . $_POST['whichWeekDay'] . "' /> " .
			                "<input type='hidden' name='weekDay' value='" . $_POST['weekDay'] . "' /> " .
			                "<input type='hidden' name='nextFee' value='{$nextFeeData['nextFee']}' /> " .
			                "<input type='hidden' name='nonce' value=" . wp_create_nonce( 'wordpress_form_submit' ) . " />" .
			                "<input type='hidden' name='wp_form_id' value='changeSubscriptionDetails' />";


			$bisonPlayersFlashMessage[] = array(
				'priority'       => 10,
				'message'        => $flashMessage,
				'confirmButtons' => true
			);
		}
	}
} else if ( $didConfirm || ! $membershipIsActive ) {

	// Cancel old style subscription if present
	if ( $id = get_user_meta( $formUser, 'gcl_sub_id', true) ) {
		$subscription = GoCardless_Subscription::find( $id );
		$subscription->cancel();
		delete_user_meta($formUser, 'gcl_sub_id');
	}

	$bisonsMembership->updateMembershipInfo();
	$gclSubID = get_user_meta( $formUser, 'GCLSubID', true );

	// Preauth ID saved; get the details from GoCardless
	if ( $gclSubID ) {
		$bisonsMembership->remoteFindPreAuth( $gclSubID );
	}

	// No active preauth so need to create one
	if ( $bisonsMembership->preAuth->status !== 'active' ) {
		$bisonsMembership->getGCLUrl();

	} else {

		// Set next date
		$nextDate = date( 'Y-m-d', $bisonsMembership->nextPaymentDate( $formUser ) );

		// Cancel all unpaid bills
		$unpaidBills = $bisonsMembership->preAuth->fetch_sub_resource('bills', array( 'paid' => 'false' ));
		foreach ( $unpaidBills as $bill ) {
			$getBill = $bisonsMembership->remoteFindBill( $bill->id );

			if ( $getBill->can_be_cancelled && $getBill->name !== 'BisonsRFC Social Top') {
				$getBill->cancel();
			}
		}

		// request new bill
		$bisonsMembership->requestNextBill( $formUser, $_POST['nextFee'], $nextDate );
	}
}