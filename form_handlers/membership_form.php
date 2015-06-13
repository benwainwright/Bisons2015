<?php
if ( ! INCLUDED ) {
	exit;
}

$formUser = ( isset ( $_POST['form_belongs_to'] ) && current_user_can( 'committee_perms' ) )
	? $_POST['form_belongs_to'] : get_current_user_id();

update_user_meta( $formUser, 'joined', 1 );

$newUserInfo = array(
	'ID'         => $formUser,
	'user_email' => $_POST['email_addy'],
	'first_name' => $_POST['firstname'],
	'last_name'  => $_POST['surname'],
);

wp_update_user( $newUserInfo );

// No GCL sub so create one
if ( ! get_user_meta( $formUser, 'GCLUserID' ) ) {

	$user = array(
		'first_name'       => $_POST['firstname'],
		'last_name'        => $_POST['surname'],
		'email'            => $_POST['email_addy'],
		'billing_address1' => $_POST['streetaddyl1'],
		'billing_address2' => $_POST['streetaddyl2'],
		'billing_town'     => $_POST['streetaddytown'],
		'billing_postcode' => $_POST['postcode'],
	);


	$return_addy = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

	switch ( $_POST['paymethod'] ) {

		case "Monthly Direct Debit":
			$feeid = ( $_POST['playermembershiptypemonthly'] != '' )
				? $_POST['playermembershiptypemonthly']
				: $_POST['supportermembershiptypemonthly'];

			$amount           = get_post_meta( $feeid, 'fee-amount', true );
			$amount_in_pounds = pence_to_pounds( $amount, false );
			$setup_fee        = pence_to_pounds( get_post_meta( $feeid, 'initial-payment', true ), false );

			$preAuthDetails = array(
				'max_amount'     => '200.00',
				'name'            => get_post_meta( $feeid, 'fee-name', true ),
				'interval_length' => 1,
				'interval_unit'   => 'month',
				'user'            => $user,
				'state'           => "DD",
			);

			if ( $description = get_post_meta( $feeid, 'fee-description', true ) ) {
				$preAuthDetails['description'] = $description;
			}

			if ( $setup_fee > 0 ) {
				$preAuthDetails['setup_fee'] = $setup_fee;
				$preAuthDetails['description'] .= ' Note that your first payment will be debited as a separate payment on the same date as the one off fee';
			}

			$gocardless_url = GoCardless::new_pre_authorization_url( $preAuthDetails );


			break;

		case "Single Payment":
			$feeid = ( $_POST['playermembershiptypesingle'] != '' )
				? $_POST['playermembershiptypesingle']
				: $_POST['supportermembershiptypesingle'];


			$subscription_details = array(
				'amount'   => pence_to_pounds( get_post_meta( $feeid, 'initial-payment', true ), false ),
				'name'     => get_post_meta( $feeid, 'fee-name', true ),
				'currency' => 'GBP',
				'user'     => $user,
				'state'    => "SP",
			);

			if ( $description = get_post_meta( $feeid, 'fee-description', true ) ) {
				$subscription_details['description'] = $description;
			}

			$gocardless_url = GoCardless::new_bill_url( $subscription_details );

			break;
	};
}

$errors = array();

$singlelinefields = array(
	'Joining as'                          => 'joiningas',
	'First Name'                          => 'firstname',
	'Surname'                             => 'surname',
	'Gender'                              => 'gender',
	'Other Gender Details'                => 'othergender',
	'Date of Birth'                       => array(
		'dob-day',
		'dob-month',
		'dob-year'
	),
	'Email Address'                       => 'email_addy',
	'Contact Number'                      => 'contact_number',
	'Line 1 (Address)'                    => 'streetaddyl1',
	'Line 2 (Address)'                    => 'streetaddyl2',
	'Town (Address)'                      => 'streetaddytown',
	'Postcode'                            => 'postcode',
	'Medical Conditions or Disabilities?' => 'medconsdisabyesno',
	'Allergies?'                          => 'allergiesyesno',
	'Injuries?'                           => 'injuredyesno',
	'First Name (Next of Kin)'            => 'nokfirstname',
	'Surname (Next of Kin)'               => 'noksurname',
	'Relationship (Next of Kin)'          => 'nokrelationship',
	'Contact Number (Next of Kin)'        => 'nokcontactnumber',
	'Next of Kin Lives at Same Address?'  => 'sameaddress',
	'Street Address (Next of Kin)'        => 'nokstreetaddy',
	'Postcode (Next of Kin)'              => 'nokpostcode',
	'Other Sports and Fitness?'           => 'othersports',
	'Training hours a week?'              => 'hoursaweektrain',
	'Played Before?'                      => 'playedbefore',
	'Where and for how many seasons?'     => 'whereandseasons',
	'Height'                              => 'height',
	'Weight'                              => 'weight',
	'How many cigarettes per day?'        => 'howmanycigsperday',
	'How did you hear about the Bisons?'  => 'howdidyouhear',
	'What can you bring to the Bisons'    => 'whatcanyoubring',
	'Top Size'                            => 'topsize',
	'Payment Date'                        => 'payWhen',
	'DayOfMonth'                          => 'Day Of Month',
	'weekDay'                             => 'Weekday',
	'whichWeekDay'                        => 'Which weekday?'
);


foreach ( $singlelinefields as $label => $fieldname ) {
	switch ( $label ) {

		case "Email Address":
			break;

		case "First Name":
			break;

		case "Surname":
			break;

		case "Date of Birth":

			$olddobday   = get_user_meta( $formUser, 'dob-day', true );
			$olddobmonth = get_user_meta( $formUser, 'dob-month', true );
			$olddobyear  = get_user_meta( $formUser, 'dob-year', true );

			if ( $_POST['dob-day'] != $olddobday
			     || $_POST['dob-month'] != $olddobmonth
			     || $_POST['dob-year'] != $olddobyear
			) {

				update_user_meta( $formUser, 'dob-day', $_POST['dob-day'] );
				update_user_meta( $formUser, 'dob-month', $_POST['dob-month'] );
				update_user_meta( $formUser, 'dob-year', $_POST['dob-year'] );
			}
			break;


		default:
			if ( $_POST[ $fieldname ] != ( $oldfield = get_user_meta( $formUser, $fieldname, true ) ) ) {

				if ( $label == $email_addy ) {
					wp_update_user( array( 'user_email' => $_POST['email_addy'] ) );
				}

				update_user_meta( $formUser, $fieldname, $_POST[ $fieldname ] );
			}
	}
}




if ( $_POST['fainting'] != get_user_meta( $formUser, 'fainting', true ) ||
     $_POST['dizzyturns'] != get_user_meta( $formUser, 'dizzyturns', true ) ||
     $_POST['breathlessness'] != get_user_meta( $formUser, 'breathlessness', true ) ||
     $_POST['bloodpressure'] != get_user_meta( $formUser, 'bloodpressure', true ) ||
     $_POST['diabetes'] != get_user_meta( $formUser, 'diabetes', true ) ||
     $_POST['palpitations'] != get_user_meta( $formUser, 'palpitations', true ) ||
     $_POST['chestpain'] != get_user_meta( $formUser, 'chestpain', true ) ||
     $_POST['suddendeath'] != get_user_meta( $formUser, 'suddendeath', true ) ||
     $_POST['smoking'] != get_user_meta( $formUser, 'suddendeath', true )
) {
	$conditions = array();


	if ( get_user_meta( $formUser, 'fainting', true ) == 'on' ) {
		$oldconditions[] = 'Fainting';
	}
	update_user_meta( $formUser, 'fainting', $_POST['fainting'] );
	if ( $_POST['fainting'] == 'on' ) {
		$conditions[] = 'Fainting';
	}

	if ( get_user_meta( $formUser, 'fainting', true ) == 'on' ) {
		$oldconditions[] = 'Dizzy Turns';
	}
	update_user_meta( $formUser, 'dizzyturns', $_POST['dizzyturns'] );
	if ( $_POST['dizzyturns'] == 'on' ) {
		$conditions[] = 'Dizzy Turns';
	}

	if ( get_user_meta( $formUser, 'fainting', true ) == 'on' ) {
		$oldconditions[] = 'Breathlessness or being more easily tired than teammates';
	}
	update_user_meta( $formUser, 'breathlessness', $_POST['breathlessness'] );
	if ( $_POST['breathlessness'] == 'on' ) {
		$conditions[] = 'Breathlessness or being more easily tired than teammates';
	}

	if ( get_user_meta( $formUser, 'fainting', true ) == 'on' ) {
		$oldconditions[] = 'History of high blood pressure';
	}
	update_user_meta( $formUser, 'bloodpressure', $_POST['bloodpressure'] );
	if ( $_POST['bloodpressure'] == 'on' ) {
		$conditions[] = 'History of high blood pressure';
	}

	if ( get_user_meta( $formUser, 'fainting', true ) == 'on' ) {
		$oldconditions[] = 'Diabetes';
	}
	update_user_meta( $formUser, 'diabetes', $_POST['diabetes'] );
	if ( $_POST['diabetes'] == 'on' ) {
		$conditions[] = 'Diabetes';
	}

	if ( get_user_meta( $formUser, 'fainting', true ) == 'on' ) {
		$oldconditions[] = 'Palpatations';
	}
	update_user_meta( $formUser, 'palpitations', $_POST['palpitations'] );
	if ( $_POST['palpitations'] == 'on' ) {
		$conditions[] = 'Palpatations';
	}

	if ( get_user_meta( $formUser, 'fainting', true ) == 'on' ) {
		$oldconditions[] = 'Chest pain or tightness';
	}
	update_user_meta( $formUser, 'chestpain', $_POST['chestpain'] );
	if ( $_POST['chestpain'] == 'on' ) {
		$conditions[] = 'Chest Pain';
	}

	if ( get_user_meta( $formUser, 'fainting', true ) == 'on' ) {
		$oldconditions[] = 'Sudden death in immediate family of anyone under 50';
	}
	update_user_meta( $formUser, 'suddendeath', $_POST['suddendeath'] );
	if ( $_POST['suddendeath'] == 'on' ) {
		$conditions[] = 'suddendeath';
	}

	if ( get_user_meta( $formUser, 'fainting', true ) == 'on' ) {
		$oldconditions[] = 'Smoking';
	}
	update_user_meta( $formUser, 'smoking', $_POST['smoking'] );
	if ( $_POST['smoking'] == 'on' ) {
		$conditions[] = 'Smoking';
	}

	$conditionsstring = "";
	for ( $ii = 0; $conditions[ $ii ]; $ii ++ ) {
		$conditionsstring .= ( $ii ? ', ' : null ) . $conditions[ $ii ];
	}

	$oldconditionstring = "";
	for ( $ii = 0; $oldconditions[ $ii ]; $ii ++ ) {
		$oldconditionstring .= ( $ii ? ', ' : null ) . $oldconditions[ $ii ];
	}


}


if ( $_POST['medconsdisabyesno'] == "Yes" ) {
	$i         = 1;
	$realCount = 1;
	while ( isset( $_POST[ 'condsdisablities_name_row' . $i ] ) ) {

		if ( $_POST[ 'condsdisablities_name_row' . $i ] != '' ) {
			update_user_meta( $formUser, 'condsdisablities_name_row' . $realCount,
				$_POST[ 'condsdisablities_name_row' . $i ] );
			update_user_meta( $formUser, 'condsdisablities_drugname_row' . $realCount,
				$_POST[ 'condsdisablities_drugname_row' . $i ] );
			update_user_meta( $formUser, 'condsdisablities_drugdose_freq_row' . $realCount,
				$_POST[ 'condsdisablities_drugdose_freq_row' . $i ] );
			update_user_meta( $formUser, 'condsdisablities_rowcount', $realCount );
			$realCount ++;
		}
		$i ++;
	}
}


if ( $_POST['allergiesyesno'] == "Yes" ) {
	$i         = 1;
	$realCount = 1;
	while ( isset( $_POST[ 'allergies_name_row' . $i ] ) ) {
		if ( $_POST[ 'allergies_name_row' . $i ] != '' ) {
			update_user_meta( $formUser, 'allergies_name_row' . $realCount, $_POST[ 'allergies_name_row' . $i ] );
			update_user_meta( $formUser, 'allergies_drugname_row' . $realCount,
				$_POST[ 'allergies_drugname_row' . $i ] );
			update_user_meta( $formUser, 'allergies_drugdose_freq_row' . $realCount,
				$_POST[ 'allergies_drugdose_freq_row' . $i ] );
			update_user_meta( $formUser, 'allergies_rowcount', $realCount );
			$realCount ++;
		}
		$i ++;
	}
}


if ( $_POST['injuredyesno'] == "Yes" ) {
	$i         = 1;
	$realCount = 1;
	while ( isset( $_POST[ 'injuries_name_row' . $i ] ) ) {
		if ( $_POST[ 'injuries_name_row' . $i ] != '' ) {
			update_user_meta( $formUser, 'injuries_name_row' . $realCount, $_POST[ 'injuries_name_row' . $i ] );
			update_user_meta( $formUser, 'injuries_when_row' . $realCount, $_POST[ 'injuries_when_row' . $i ] );
			update_user_meta( $formUser, 'injuries_treatmentreceived_row' . $realCount,
				$_POST[ 'injuries_treatmentreceived_row' . $i ] );
			update_user_meta( $formUser, 'injuries_who_row' . $realCount, $_POST[ 'injuries_who_row' . $i ] );
			update_user_meta( $formUser, 'injuries_status_row' . $realCount, $_POST[ 'injuries_status_row' . $i ] );
			update_user_meta( $formUser, 'injuries_rowcount', $realCount );
		}
		$i ++;
	}

	for ( $i = 1; isset( $_POST[ 'injuries_name_row' . $i ] ) && $_POST[ 'injuries_name_row' . $i ] != ''; $i ++ ) {
		update_user_meta( $formUser, 'injuries_name_row' . $i, $_POST[ 'injuries_name_row' . $i ] );
		update_user_meta( $formUser, 'injuries_when_row' . $i, $_POST[ 'injuries_when_row' . $i ] );
		update_user_meta( $formUser, 'injuries_treatmentreceived_row' . $i,
			$_POST[ 'injuries_treatmentreceived_row' . $i ] );
		update_user_meta( $formUser, 'injuries_who_row' . $i, $_POST[ 'injuries_who_row' . $i ] );
		update_user_meta( $formUser, 'injuries_status_row' . $i, $_POST[ 'injuries_status_row' . $i ] );
		update_user_meta( $formUser, 'injuries_rowcount', $i );
	}
}

update_user_meta( $formUser, 'memtype', $_POST['memtype'] );