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

		$this->addHooks();
	}


	public function remoteConfirmPreauth( $confirm_params ) {
		try {
			return GoCardless::confirm_resource( $confirm_params );

		} catch ( Exception  $e ) {
			$this->error( $e, $confirm_params );
		}
	}

	public function remotePreAuthURL( $preAuthDetails ) {
		try {
			return GoCardless::new_pre_authorization_url( $preAuthDetails );

		} catch ( Exception  $e ) {
			$this->error( $e, $preAuthDetails );
		}
	}

	public function remoteCreatePreauthBill( $bill ) {

		try {
			return $this->preAuth->create_bill( $bill );
		} catch ( Exception $e ) {
			$this->error( $e, $bill );
		}
	}

	public function remoteFindPreAuth( $id ) {

		try {
			$this->preAuth = GoCardless_PreAuthorization::find( $id );
		} catch ( Exception $e ) {
			;
			$this->error( $e, $id );
		}

	}

	/**
	 * Given a user id, this function will calculate when the next payment should be
	 * based on user settings in the database
	 *
	 * @param $userId
	 * @param bool $time
	 *
	 * @return int
	 */
	public function nextPaymentDate( $userId, $time = false, $hypotheticals = false ) {


		$nextPaymentDate   = false;
		$time              = $time ? $time : time();
		$currentDayOfMonth = (int) date( 'j', $time );
		$currentYear       = (int) date( 'Y', $time );
		$currentMonth      = (int) date( 'n', $time );

		$daysInThisMonth = cal_days_in_month( CAL_GREGORIAN, $currentMonth, $currentYear );

		if ( is_array( $hypotheticals ) ) {
			$specDay         = (int) $hypotheticals['dayOfMonth'];
			$whichWeekdayPos = $hypotheticals['whichWeekDay'];
			$whichWeekday    = $hypotheticals['weekDay'];
			$payWhen         = $hypotheticals['payWhen'];

		} else {
			$specDay         = (int) get_user_meta( $userId, 'dayOfMonth', true );
			$whichWeekdayPos = get_user_meta( $userId, 'whichWeekDay', true );
			$whichWeekday    = get_user_meta( $userId, 'weekDay', true );
			$payWhen         = get_user_meta( $userId, 'payWhen', true );
		}


		$specDay = $specDay > $daysInThisMonth ? $daysInThisMonth : $specDay;


		if ( $currentMonth === 12 ) {
			$firstDayOfNextMonth = mktime( 0, 0, 0, 1, 1, $currentYear + 1 );
			$daysInNextMonth     = cal_days_in_month( CAL_GREGORIAN, 1, $currentYear + 1 );


		} else {
			$firstDayOfNextMonth = mktime( 0, 0, 0, $currentMonth + 1, 1 );
			$daysInNextMonth     = cal_days_in_month( CAL_GREGORIAN, $currentMonth + 1, $currentYear );

		}

		switch ( $payWhen ) {

			case "first":
				$nextPaymentDate = $firstDayOfNextMonth;
				break;

			case "last":

				if ( $currentDayOfMonth !== $daysInThisMonth ) {
					$nextPaymentDate = mktime( 0, 0, 0, $currentMonth, $daysInThisMonth );
				} else {
					$nextPaymentDate = mktime( 0, 0, 0, $currentMonth + 1, $daysInNextMonth );
				}
				break;

			case "specificDay":

				$nextPaymentDate = mktime( 0, 0, 0, $currentMonth, $specDay );


				if ( $nextPaymentDate < $time || $specDay === $currentDayOfMonth ) {
					$nextPaymentDate = mktime( 0, 0, 0, $currentMonth + 1, $specDay );
				}
				break;

			case "specificWeekday":

				$dateString = $whichWeekdayPos . ' ' . $whichWeekday . ' of  ' . date( 'F',
						$time ) . ' ' . date( 'Y', $time );

				$nextPaymentDate = strtotime( $dateString );

				if ( $nextPaymentDate < $time || date( 'Y-m-d', $nextPaymentDate ) === date( 'Y-m-d', $time ) ) {
					$dateString      = $whichWeekdayPos . ' ' . $whichWeekday . ' of ' . date( 'F',
							$firstDayOfNextMonth ) . ' ' . ( $currentMonth === 12 ? date( 'Y', $time ) + 1 : date( 'Y',
							$time ) );
					$nextPaymentDate = strtotime( $dateString );
				}
		}


		return $nextPaymentDate;

	}

	public function getGCLUrl() {

		$data = $this->postData;

		$user = array(
			'first_name'       => $data['firstname'],
			'last_name'        => $data['surname'],
			'email'            => $data['email_addy'],
			'billing_address1' => $data['streetaddyl1'],
			'billing_address2' => $data['streetaddyl2'],
			'billing_town'     => $data['streetaddytown'],
			'billing_postcode' => $data['postcode']
		);

		$state = $data['payMethod'];

		if ( 'dd' === $state ) {

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

		if ( isset ( $data['socialTop'] ) ) {
			$preAuthDetails['state'] .= '+socialTopPlease';
		}

		if ( $description = get_post_meta( $feeid, 'fee-description', true ) ) {
			$preAuthDetails['description'] = $description;
		}

		$this->goCardlessURL = $this->remotePreAuthURL( $preAuthDetails );

		return $this->goCardlessURL;


	}


	public function joinClub() {

		$this->updateMembershipInfo();

		return $this->getGCLUrl();

		global $bisonPlayersFlashMessage;

		$bisonPlayersFlashMessage[] = array(
			'priority' => 100,
			'message'  => 'In a moment, you will be redirected to a direct debit mandate form at GoCardless. Once you have finished setting up your payment information, you will be returned to this site. See you in a bit!'
		);

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


	public function calculateNextFeeOnDateChange() {

		$currentDate      = $this->nextPaymentDate( $this->user );
		$p                = $this->postData;
		$hypothetical     = $this->nextPaymentDate( $this->user, false, $p );
		$differenceInDays = ( ( ( $hypothetical - $currentDate ) / 60 ) / 60 ) / 24;
		$currentFee       = get_user_meta( $this->user, 'currentFee', true );
		$feeDifference    = ( $currentFee / 30 ) * $differenceInDays;
		$nextFee          = $currentFee + $feeDifference;

		return array(
			'nextPaymentDate'   => $hypothetical,
			'currentFee'        => $currentFee,
			'nextFee'           => round( $nextFee ),
			'differenceInDays'  => $differenceInDays,
			'differenceInPence' => round( $feeDifference )

		);
	}

	public function changeSubscriptionDate() {

		$p = $this->postData;
		update_user_meta( $this->user, 'payMethod', $p['payMethod'] );
		update_user_meta( $this->user, 'dayOfMonth', $p['dayOfMonth'] );
		update_user_meta( $this->user, 'payWhen', $p['payWhen'] );
		update_user_meta( $this->user, 'whichWeekDay', $p['whichWeekDay'] );
		update_user_meta( $this->user, 'weekDay', $p['weekDay'] );
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
			$this->remoteFindPreAuth( $confirm_params['resource_id'] );
			$amount = $vars[1];

			update_user_meta( $this->user, 'payMethod', $type );  // Single payment pending
			update_user_meta( $this->user, 'currentFee', $amount );
			update_user_meta( $this->user, 'GCLUserID', $this->preAuth->user_id );
			update_user_meta( $this->user, 'GCLSubID', $queryString['resource_id'] );
			update_user_meta( $this->user, 'GCLSubName', $this->preAuth->name );


			$bill = array(
				'name'   => __( 'BisonsRFC Subscription Fee', 'bisonsRFC' ),
				'amount' => pence_to_pounds( $amount, false )
			);

			if ( $type == "dd" ) {
				update_user_meta( $this->user, 'nextPaymentDate', $this->nextPaymentDate( $this->user ) );
				$bill['charge_customer_at'] = date( 'Y-m-d', $this->nextPaymentDate( $this->user ) );
				update_user_meta( $this->user, 'GCLSubStatus', $this->preAuth->status );
			}

			$this->remoteCreatePreauthBill( $bill );

			if ( isset( $vars[2] ) ) {
				$bill = array(
					'name'   => __( 'BisonsRFC Social Top', 'bisonsRFC' ),
					'amount' => '10.00'
				);

				$this->remoteCreatePreauthBill( $bill );
			}

			global $bisonPlayersFlashMessage;

			$bisonPlayersFlashMessage[] = array(
				'priority' => 100,
				'message'  => 'Congratulations! Your direct debit (or full payment) has now been setup - you should receive an email from GoCardless (our payment processor) very shortly.'
			);


		}

		return $confirmed_resource;
	}

	public
	function scheduleNextPayment(
		$id
	) {

		$nextPaymentDate = get_user_meta( $id, 'nextPaymentDate', true );

		if ( $nextPaymentDate != $this->nextPaymentDate( $id ) ) {

			$nextPaymentDate = $this->nextPaymentDate( $id );
			$scheduleDate    = $nextPaymentDate - 60 * 60 * 24 * 7;
			$scheduleDate    = $scheduleDate > time() ? $scheduleDate : time();
			$chargeDate      = date( 'Y-m-d', $nextPaymentDate );
			$chargeAmount    = get_user_meta( $id, 'currentFee', true );

			$args = array( $id, $chargeAmount, $chargeDate );

			if ( wp_next_scheduled( 'bisonsCronRequestNextBill', $args ) <= time() ) {

				wp_schedule_single_event( $scheduleDate, 'bisonsCronRequestNextBill', $args );
				update_user_meta( $id, 'nextBillDate', $nextPaymentDate );
			}
		}
	}

	function requestNextBill( $id, $chargeAmount, $chargeDate = false ) {

		$GCLid = get_user_meta( $id, 'GCLUserID', true );

		$this->remoteFindPreAuth( $GCLid );

		$bill = array(
			'name'   => __( 'BisonsRFC Subscription Fee', 'bisonsRFC' ),
			'amount' => $this->penceToPounds( $chargeAmount, false )
		);

		if ( $chargeDate ) {
			$bill['charge_customer_at'] = $chargeDate;
		}
		$this->remoteCreatePreauthBill( $bill );
	}


	function dailyBillCheck() {

		$name = 'bisonsDailyBillCheck';

		$timestamp = wp_next_scheduled( $name );

		if ( ! $timestamp || $timestamp < time() ) {
			wp_schedule_event( time(), 'daily', $name );
		}
	}

	function clearDailyBillCheck() {
		wp_clear_scheduled_hook( 'bisonsDailyBillCheck' );
	}

	function addHooks() {
		add_action( 'switch_theme', array( $this, 'clearDailyBillCheck' ) );
		add_action( 'after_switch_theme', array( $this, 'dailyBillCheck' ) );
		add_action( 'bisonsDailyBillCheck', array( $this, 'scheduleBills' ) );
		add_action( 'bisonsRequestNextBill', array( $this, 'requestNextBill' ), 10, 3 );
	}

	function scheduleBills() {
		$users = get_users( array( 'meta_key' => 'payMethod', 'meta_value' => 'dd' ) );

		foreach ( $users as $user ) {
			$this->scheduleNextBill( $user->ID );
		}
	}

	function clearNextBillDates() {
		$users = get_users( array( 'meta_key' => 'payMethod', 'meta_value' => 'dd' ) );

		foreach ( $users as $user ) {
			delete_user_meta( $user->ID, 'nextBillDate' );
		}
	}

	function scheduleNextBill( $id ) {

		$nextPaymentDate = get_user_meta( $id, 'nextBillDate', true );

		if ( $nextPaymentDate != getNextPaymentDate( $id ) ) {
			$nextPaymentDate = getNextPaymentDate( $id );
			$scheduleDate    = $nextPaymentDate - 60 * 60 * 24 * 7;
			$scheduleDate    = $scheduleDate > time() ? $scheduleDate : time();
			$chargeDate      = date( 'Y-m-d', $nextPaymentDate );
			$chargeAmount    = get_user_meta( $id, 'currentFee', true );


			$args = array(
				$id,
				$chargeAmount,
				$chargeDate
			);


			if ( wp_next_scheduled( 'bisonsRequestNextBill', $args ) <= time() ) {
				wp_schedule_single_event( $scheduleDate, 'bisonsRequestNextBill', $args );
			}

			update_user_meta( $id, 'nextBillDate', $nextPaymentDate );
		}
	}


	function getStatus( $id ) {
		if ( get_user_meta( $id, 'payMethod', true ) == 'sp' ) {

			// Work out if there is single payment for the current season
			$userSinglePaymentID = get_user_meta( $id, 'singlePaymentID', true );

			$taxQuery = wp_excludePostsWithTermTaxQuery( 'seasons' );

			$queryArray = array(
				'post_type'  => 'GCLBillLog',
				'meta_query' => 'id',
				'meta_value' => $userSinglePaymentID,
				'tax_query'  => $taxQuery
			);

			$query = new WP_Query( $queryArray );

			$dd_status = $query->post_count ? 'Paid in Full' : 'Unpaid';

		} else {

			$dd_status = get_user_meta( $id, 'GCLSubStatus', true );

		}

		return $dd_status ? $dd_status : 'None';
	}

	function penceToPounds( $pence, $poundsign = true ) {
		$newpence = substr( (string) $pence, - 2 );
		$pounds   = substr( $pence, 0, - 2 ) ? substr( $pence, 0, - 2 ) : "0";
		$pounds   = $pounds ? $pounds : "0";
		$newpence = $newpence ? $newpence : "00";

		return $poundsign ? "£$pounds.$newpence" : "$pounds.$newpence";
	}

	/**
	 *
	 * Takes the id of a user and queries the Wordpress database,
	 * returning user payment info in an array
	 *
	 * @param $id Wordpress User ID
	 *
	 * @return array
	 */
	function getPaymentInfo( $id ) {

		if ( get_user_meta ( $id, 'joined', true) ) {

			$args  = array( 'post_type' => 'GCLBillLog', 'author' => $id, 'posts_per_page' => - 1 );
			$query = new WP_Query( $args );

			$paymentInfo = array(
				'Subscription Status' => ucwords( $this->getStatus( $id ) ),
				'Membership Type'     => get_user_meta( $id, 'joiningas', true ),
				'Successful Payments' => 0,
				'Total Paid'          => 0,
				'Total Refunded'      => 0,
				'Last Bill'           => 0
			);

			setlocale( LC_MONETARY, 'en_GB.UTF-8' );

			while ( $query->have_posts() ) {

				$query->the_post();

				switch ( get_post_meta( get_the_id(), 'status', true ) ) {

					case "withdrawn":
					case "paid":

						$paymentInfo['Successful Payments'] ++;
						$paymentInfo['Total Paid'] += get_post_meta( get_the_id(), 'amount', true );

						if ( get_the_date( ( 'U' ) ) > $paymentInfo['Last Bill'] ) {
							$paymentInfo['Last Bill'] = get_the_date( 'U' );
						}

						break;

					case "failed":
						break;

					case "refunded":
					case "chargedback":
						$paymentInfo['Total Refunded'] += get_post_meta( get_the_id(), 'amount', true );
						break;
				}

			}
		}
		else {
			$paymentInfo = array(   'Subscription Status' => 'Not Joined' );
		}

		return $paymentInfo;

	}

	private
	static function error(
		$e,
		$data
	) {

		$f = __DIR__ . '/../logs/membership.errors.log';

		$fh = fopen( $f, 'a' );


		$message = "\n" . $e->getMessage() . "\n";
		$message .= "\nData:\n" . print_r( $data, true ) . "\n";
		$message .= "Code: " . $e->getCode() . "\n";
		$message .= "File: " . $e->getFile() . "\n";
		$message .= "Line: " . $e->getLine() . "\n";
		$message .= "Prev: " . $e->getPrevious() . "\n";
		$message .= "Trace: \n" . $e->getTraceAsString() . "\n";

		fwrite( $fh, "\n==START==" );
		fwrite( $fh, $message );
		fwrite( $fh, "==END==\n" );
	}
}

global $bisonsMembership;

$bisonsMembership = new Bisons_Membership();