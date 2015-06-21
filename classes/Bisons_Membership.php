<?php

class Bisons_Membership {

	public $preAuth;

	public $user;

	public $queryString;

	public $currentURL;

	public $GCLid;

	public $postData;

	public $currentMonthlyFee;

	public $goCardlessURL;

	public function __construct() {

		$this->queryString = count( $_GET ) > 0 ? $_GET : false;

		$this->postData = isset ( $_POST ) ? $_POST : false;

		$this->user = ( isset ( $this->queryString['player_id'] ) && current_user_can( 'committee_perms' ) )
			? $this->queryString['player_id'] : get_current_user_id();

		$this->currentURL = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

		$this->GCLid = get_user_meta( $this->user, 'GCLUserID' ) ? get_user_meta( $this->user, 'GCLUserID' ) : false;

	}


	public function remoteConfirmPreauth( $confirm_params ) {
		try {
			return GoCardless::confirm_resource( $confirm_params );

		} catch ( Exception  $e ) {
			$this->error( $e->getMessage() );
		}
	}

	public function remotePreAuthURL( $preAuthDetails ) {
		try {
			return GoCardless::new_pre_authorization_url( $preAuthDetails );

		} catch ( Exception  $e ) {
			$this->error( $e->getMessage() );
		}
	}

	public function remoteCreateBill( $bill ) {

		try {
			$this->preAuth->create_bill( $bill );
		} catch ( Exception $e ) {
			$this->error( $e->getMessage() );
		}
	}

	public function remoteFindPreAuth( $id ) {

		try {
			$this->preAuth = GoCardless_PreAuthorization::find( $id );
		} catch ( Exception $e ) {
			$this->error( $e->getMessage() );
		}

	}

	public function nextPaymentDate( $userId ) {

		$currentYear     = date( 'Y' );
		$currentMonth    = date( 'n' );
		$daysInThisMonth = cal_days_in_month( CAL_GREGORIAN, $currentMonth, $currentYear );
		$specDay         = (int) get_user_meta( $userId, 'dayOfMonth', true );
		$specDay         = $specDay > $daysInThisMonth ? $daysInThisMonth : $specDay;
		$whichWeekdayPos = get_user_meta( $userId, 'whichWeekDay', true );
		$whichWeekday    = get_user_meta( $userId, 'weekDay', true );

		$payWhen = get_user_meta( $userId, 'payWhen', true );

		if ( $currentMonth == 12 ) {
			$firstDayOfNextMonth = mktime( 0, 0, 0, 0, 0, $currentYear + 1 );
		} else {
			$firstDayOfNextMonth = mktime( 0, 0, 0, $currentMonth + 1, 1 );
		}

		switch ( $payWhen ) {

			case "first":
				$nextPaymentDate = $firstDayOfNextMonth;
				break;

			case "last":
				$nextPaymentDate = mktime( 0, 0, 0, $currentMonth, $daysInThisMonth );
				break;

			case "specificDay":
				$nextPaymentDate = mktime( 0, 0, 0, $currentMonth, $specDay );
				if ( $nextPaymentDate < time() ) {
					$nextPaymentDate = mktime( 0, 0, 0, $currentMonth + 1, $specDay );
				}
				break;

			case "specificWeekDay":
				$dateString      = $whichWeekdayPos . ' ' . $whichWeekday . ' of  ' . date( 'F' ) . ' ' . date( 'Y' );
				$nextPaymentDate = strtotime( $dateString );
				if ( $nextPaymentDate < time() ) {
					$dateString      = $whichWeekdayPos . ' ' . $whichWeekday . ' of ' . date( 'F',
							$firstDayOfNextMonth ) . ' ' . ( $currentMonth == 12 ? date( 'Y' ) + 1 : date( 'Y' ) );
					$nextPaymentDate = strtotime( $dateString );
				}


		}

		return $nextPaymentDate;

	}

	public function getGCLUrl() {

		$data = $this->postData;


		if ( ! $this->GCLid ) {

			$user = array(
				'first_name'       => $data['firstname'],
				'last_name'        => $data['surname'],
				'email'            => $data['email_addy'],
				'billing_address1' => $data['streetaddyl1'],
				'billing_address2' => $data['streetaddyl2'],
				'billing_town'     => $data['streetaddytown'],
				'billing_postcode' => $data['postcode']
			);
		}

		$state = $data['payMethod'];

		if ( 'dd' == $state ) {

			$feeid = ( $data['playermembershiptypemonthly'] != '' )
				? $data['playermembershiptypemonthly']
				: $data['supportermembershiptypemonthly'];
		} elseif ( 'sp' == $state ) {
			$feeid = ( $data['playermembershiptypesingle'] != '' )
				? $data['playermembershiptypesingle']
				: $data['supportermembershiptypesingle'];

		}

		$amount                  = get_post_meta( $feeid, 'fee-amount', true );
		$this->currentMonthlyFee = $amount;

		$preAuthDetails = array(
			'max_amount'      => '200.00',
			'name'            => get_post_meta( $feeid, 'fee-name', true ),
			'interval_length' => 1,
			'interval_unit'   => 'month',
			'user'            => $user,
			'state'           => $state,
		);

		$preAuthDetails['state'] .= "+$amount";

		if ( isset ( $_POST['socialTop'] ) ) {
			$preAuthDetails['state'] .= '+socialTopPlease';
		}

		if ( $description = get_post_meta( $feeid, 'fee-description', true ) ) {
			$preAuthDetails['description'] = $description;
		}


		$this->goCardlessURL = $this->remotePreAuthURL( $preAuthDetails );

		return $this->goCardlessURL;
	}


	public function joinClub() {
		$this->getGCLUrl();
		$this->updateMembershipInfo();
	}

	// TODO sanitize input
	public function updateMembershipInfo() {

		$this->getGCLUrl();

		$post = $this->postData;


		$userID = $this->user;

		update_user_meta( $userID, 'joined', 1 );

		$newUserInfo = array(
			'ID'         => $userID,
			'user_email' => $post['email_addy'],
			'first_name' => $post['firstname'],
			'last_name'  => $post['surname'],
		);

		wp_update_user( $newUserInfo );

		$errors = array();

		$singlelinefields = array(
			'joiningas',
			'gender',
			'othergender',
			'dob-day',
			'dob-month',
			'dob-year',
			'contact_number',
			'streetaddyl1',
			'streetaddyl2',
			'streetaddytown',
			'postcode',
			'medconsdisabyesno',
			'allergiesyesno',
			'injuredyesno',
			'nokfirstname',
			'noksurname',
			'nokrelationship',
			'nokcontactnumber',
			'sameaddress',
			'nokstreetaddy',
			'nokpostcode',
			'othersports',
			'hoursaweektrain',
			'playedbefore',
			'whereandseasons',
			'height',
			'weight',
			'howmanycigsperday',
			'howdidyouhear',
			'whatcanyoubring',
			'topsize',
			'payWhen',
			'dayOfMonth',
			'weekDay',
			'whichWeekDay',
			'payMethod',
			'fainting',
			'dizzyturns',
			'breathlessness',
			'fainting',
			'bloodpressure',
			'diabetes',
			'palpitations',
			'chestpain',
			'suddendeath',
			'smoking',
			'palpatations'

		);


		foreach ( $singlelinefields as $fieldname ) {
			update_user_meta( $userID, $fieldname, $post[ $fieldname ] );
		}


		if ( $post['medconsdisabyesno'] == "Yes" ) {
			$i         = 1;
			$realCount = 1;
			while ( isset( $post[ 'condsdisablities_name_row' . $i ] ) ) {

				if ( $post[ 'condsdisablities_name_row' . $i ] != '' ) {
					update_user_meta( $userID, 'condsdisablities_name_row' . $realCount,
						$post[ 'condsdisablities_name_row' . $i ] );
					update_user_meta( $userID, 'condsdisablities_drugname_row' . $realCount,
						$post[ 'condsdisablities_drugname_row' . $i ] );
					update_user_meta( $userID, 'condsdisablities_drugdose_freq_row' . $realCount,
						$post[ 'condsdisablities_drugdose_freq_row' . $i ] );
					update_user_meta( $userID, 'condsdisablities_rowcount', $realCount );
					$realCount ++;
				}
				$i ++;
			}
		}


		if ( $post['allergiesyesno'] == "Yes" ) {
			$i         = 1;
			$realCount = 1;
			while ( isset( $post[ 'allergies_name_row' . $i ] ) ) {
				if ( $post[ 'allergies_name_row' . $i ] != '' ) {
					update_user_meta( $userID, 'allergies_name_row' . $realCount,
						$post[ 'allergies_name_row' . $i ] );
					update_user_meta( $userID, 'allergies_drugname_row' . $realCount,
						$post[ 'allergies_drugname_row' . $i ] );
					update_user_meta( $userID, 'allergies_drugdose_freq_row' . $realCount,
						$post[ 'allergies_drugdose_freq_row' . $i ] );
					update_user_meta( $userID, 'allergies_rowcount', $realCount );
					$realCount ++;
				}
				$i ++;
			}
		}


		if ( $post['injuredyesno'] == "Yes" ) {
			$i         = 1;
			$realCount = 1;
			while ( isset( $post[ 'injuries_name_row' . $i ] ) ) {
				if ( $post[ 'injuries_name_row' . $i ] != '' ) {
					update_user_meta( $userID, 'injuries_name_row' . $realCount, $post[ 'injuries_name_row' . $i ] );
					update_user_meta( $userID, 'injuries_when_row' . $realCount, $post[ 'injuries_when_row' . $i ] );
					update_user_meta( $userID, 'injuries_treatmentreceived_row' . $realCount,
						$post[ 'injuries_treatmentreceived_row' . $i ] );
					update_user_meta( $userID, 'injuries_who_row' . $realCount, $post[ 'injuries_who_row' . $i ] );
					update_user_meta( $userID, 'injuries_status_row' . $realCount,
						$post[ 'injuries_status_row' . $i ] );
					update_user_meta( $userID, 'injuries_rowcount', $realCount );
				}
				$i ++;
			}

			for ( $i = 1; isset( $post[ 'injuries_name_row' . $i ] ) && $post[ 'injuries_name_row' . $i ] != ''; $i ++ ) {
				update_user_meta( $userID, 'injuries_name_row' . $i, $post[ 'injuries_name_row' . $i ] );
				update_user_meta( $userID, 'injuries_when_row' . $i, $post[ 'injuries_when_row' . $i ] );
				update_user_meta( $userID, 'injuries_treatmentreceived_row' . $i,
					$post[ 'injuries_treatmentreceived_row' . $i ] );
				update_user_meta( $userID, 'injuries_who_row' . $i, $post[ 'injuries_who_row' . $i ] );
				update_user_meta( $userID, 'injuries_status_row' . $i, $post[ 'injuries_status_row' . $i ] );
				update_user_meta( $userID, 'injuries_rowcount', $i );
			}
		}

		update_user_meta( $userID, 'lastModified', time() );

		update_user_meta( $userID, 'memtype', $post['memtype'] );
	}


	public function confirmPreauth() {

		$queryString = $this->queryString;

		$confirm_params = array(
			'resource_id'   => $queryString['resource_id'],
			'resource_type' => $queryString['resource_type'],
			'resource_uri'  => $queryString['resource_uri'],
			'signature'     => $queryString['signature']
		);

		if ( isset( $queryString['state'] ) ) {
			$confirm_params['state'] = $queryString['state'];
		}

		$confirmed_resource = $this->remoteConfirmPreauth( $confirm_params );

		if ( $confirmed_resource ) {

			$vars = explode( '+', $queryString['state'] );
			$type = $vars[0];
			$this->remoteFindPreAuth( $confirm_params );
			$amount = $vars[1];

			update_user_meta( $this->user, 'payMethod', $type );  // Single payment pending
			update_user_meta( $this->user, 'currentFee', $amount );
			update_user_meta( $this->user, 'GCLUserID', $this->preAuth->user_id );
			update_user_meta( $this->user, 'GCLSubID', $queryString['resource_id'] );
			update_user_meta( $this->user, 'memName', $this->preAuth->name );

			if ( $type == "dd" ) {
				$this->scheduleNextPayment( $this->user );
			} else {

				$bill = array(
					'name'               => __( 'BisonsRFC Subscription Fee', 'bisonsRFC' ),
					'amount'             => pence_to_pounds( $amount, false ),
					'charge_customer_at' => date( 'Y-m-d' )
				);

				$this->remoteCreateBill( $bill );
			}

			if ( isset( $vars[2] ) ) {
				$bill = array(
					'name'               => __( 'BisonsRFC Social Top', 'bisonsRFC' ),
					'amount'             => '10.00',
					'charge_customer_at' => date( 'Y-m-d' )
				);

				$this->remoteCreateBill( $bill );
			}
		}

		return $confirmed_resource;
	}

	function scheduleNextPayment( $id ) {
		$nextPaymentDate = get_user_meta( $id, 'nextBillDate', true );

		if ( $nextPaymentDate != $this->nextPaymentDate( $id ) ) {
			$nextPaymentDate = $this->nextPaymentDate( $id );
			$scheduleDate    = $nextPaymentDate - 60 * 60 * 24 * 7;
			$scheduleDate    = $scheduleDate > time() ? $scheduleDate : time();
			$chargeDate      = date( 'Y-m-d', $nextPaymentDate );
			$chargeAmount    = get_user_meta( $id, 'currentFee', true );

			$args = array(
				$id,
				$chargeDate,
				$chargeAmount
			);

			if ( wp_next_scheduled( 'BisonsCRONAddNewBill', $args ) <= time() ) {
				wp_schedule_single_event( $scheduleDate, 'BisonsCRONAddNewBill', $args );
			}

			update_user_meta( $id, 'nextBillDate', $nextPaymentDate );
		}
	}

	private static function error() {

	}
}