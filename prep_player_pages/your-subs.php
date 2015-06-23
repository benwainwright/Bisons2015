<?php
global $bisonsMembership;
global $bisonPlayersFlashMessage;


$data = &$wp_query->query['bisons_data'];

$form_user = ( isset ( $_GET['player_id'] ) && current_user_can( 'committee_perms' ) )
	? $_GET['player_id'] : get_current_user_id();


if ( $_POST && ! isset( $_POST['confirm_change'] )) {



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

			$which            = $_POST['dayOfMonth'];

			$ends = array('th','st','nd','rd','th','th','th','th','th','th');
			if (($which %100) >= 11 && ($which%100) <= 13)
				$abbreviation = $which. 'th';
			else
				$abbreviation = $which. $ends[$which % 10];
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

	if ($forwardOrBack) {

		$flashmessage = "Moving your payment date to the <strong>$newPaymentString</strong> would mean moving your next payment date $forwardOrBack by <strong>" . $diffDays .
		                "</strong>. To account for this, your next payment (which will be charged on the <strong>$hypDate</strong>) " .
		                "will be $increasedOrReduced by <strong>$difference</strong> to <strong>$nextFee</strong>. All following payments will be made at the usual amount of <strong>$currentFee</strong>";
						"<input type='hidden' name='payWhen' value='" . $_POST['payWhen']. "' /> " .
						"<input type='hidden' name='payWhen' value='" . $_POST['dayOfMonth']. "' /> " .
						"<input type='hidden' name='payWhen' value='" . $_POST['whichWeekDay']. "' /> " .
						"<input type='hidden' name='payWhen' value='" . $_POST['weekDay']. "' /> " ;


		$bisonPlayersFlashMessage[] = array(
			'priority'       => 10,
			'message'        => $flashmessage,
			'confirmButtons' => true
		);
	}
}

else if ($_POST['confirm_change'] === 'ok') {
	$bisonsMembership->updateMembershipInfo();
}


// If a membership form exists, load it from WordPress
if ( get_user_meta( $form_user, 'joined', true ) ) {


	$data['user']              = $form_user;
	$data['joined']            = true;
	$data['payMethod']         = get_user_meta( $form_user, 'payMethod', true ) ? get_user_meta( $form_user,
		'payMethod', true ) : false;
	$data['payStatus']         = $bisonsMembership->getStatus( $form_user );
	$data['currentMonthlyFee'] = pence_to_pounds(current_user_meta( 'currentFee' ), false);
	$data['GCLUserID']         = get_user_meta( $form_user, 'GCLUserID', true ) ? get_user_meta( $form_user,
		'GCLUserID', true ) : false;
	$data['query']             = new WP_Query( array(
		'post_type'      => 'GCLBillLog',
		'posts_per_page' => 10,
		'author'         => $form_user
	) );
	$data['paymentInfo']       = $bisonsMembership->getPaymentInfo( $form_user );
	$data['subName']           = get_user_meta( $form_user, 'GCLSubName', true ) ? get_user_meta( $form_user, 'GCLSubName',
		true ) : 'None';
	$data['payWhen']           = get_user_meta( $form_user, 'payWhen', true );
	$data['nextPaymentDate']   = date( 'jS M, Y', $bisonsMembership->nextPaymentDate( get_current_user_id() ) );
	$data['dayOfMonth']        = get_user_meta( $form_user, 'dayOfMonth', true );

} else {
	// If nomembership form found, redirect to the membership form with a flash message
	$flashmessage = 'You haven\'t joined the club yet! To put that right, fill in the form below!';
	wp_redirect( home_url( 'players-area/membership-form/?nonce=' . wp_create_nonce( 'bisons_flashmessage_nonce' ) . '&flash=' . urlencode( $flashmessage ) ) );
	exit();
}