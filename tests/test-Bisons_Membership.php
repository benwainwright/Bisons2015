<?php

class MembershipTest extends WP_UnitTestCase {


	function setUp() {

		parent::setUp();
		$this->user                       = $this->factory->user->create();
		$this->notJoinedUser              = $this->factory->user->create();
		$this->joinedButNotSubscribedUser = $this->factory->user->create();
		$this->joinedAndSubscribed        = $this->factory->user->create();

		$fee = $this->factory->post->create( array( 'post_type' => 'membership_fee' ) );

		$this->testFee = $fee;

		update_post_meta( $fee, 'fee-name', 'My Fee' );
		update_post_meta( $fee, 'fee-amount', 700 );
		update_post_meta( $fee, 'fee-type', '' );
		update_post_meta( $fee, 'fee-order', 1 );
		update_post_meta( $fee, 'fee-description', 'Testing this fee' );
		update_post_meta( $fee, 'initial-payment', 0 );
		update_post_meta( $fee, 'supporter-player', 'player' );
		update_post_meta( $fee, 'fees-tables', 'true' );


	}

	function assertUserMeta( $user, $array ) {

		foreach ( $array as $keyName => $expected ) {

			$actual = get_user_meta( $user, $keyName );

			$this->assertEquals( $expected, $actual );
		}
	}

	function testGetUrl() {

		$user = $this->notJoinedUser;

		$_POST = array(
			'payMethod'                   => 'dd',
			'playermembershiptypemonthly' => $this->testFee,
			'firstname'                   => 'Ben',
			'surname'                     => 'Wainwright',
			'email_addy'                  => 'bwainwright28@gmail.com',
			'streetaddyl1'                => 'Mustay',
			'streetaddyl2'                => 'Tock',
			'streetaddytown'              => 'Green',
			'postcode'                    => 'BS32'
		);


		$args = array(
			'user'            => array(
				'first_name'       => 'Ben',
				'last_name'        => 'Wainwright',
				'email'            => 'bwainwright28@gmail.com',
				'billing_address1' => 'Mustay',
				'billing_address2' => 'Tock',
				'billing_town'     => 'Green',
				'billing_postcode' => 'BS32'
			),
			'max_amount'      => '200.00',
			'name'            => 'My Fee',
			'interval_length' => 1,
			'interval_unit'   => 'month',
			'state'           => 'dd+700',
			'description'     => 'Testing this fee'

		);

		$queryString = array();

		$GCL = $this->getMockBuilder( 'Bisons_Membership' )
		            ->setMethods( array(
			            'remoteConfirmPreauth',
			            'remoteFindPreAuth',
			            'remoteCreatePreauthBill',
			            'remotePreAuthURL'
		            ) )
		            ->setConstructorArgs( array( $queryString ) )
		            ->getMock();


		$GCL->expects( $this->at( 0 ) )
		    ->method( 'remotePreAuthURL' )
		    ->with( $args );

		$GCL->getGCLUrl();

		$_POST['payMethod']                  = 'sp';
		$_POST['playermembershiptypesingle'] = $this->testFee;
		$args['state']                       = 'sp+700';


		$GCL->__construct();
		$GCL->expects( $this->once( 1 ) )
		    ->method( 'remotePreAuthURL' )
		    ->with( $args );

		$GCL->getGCLUrl();
	}


	function testNextPaymentDate() {
		$u          = $this->user;
		$membership = $this->getMockBuilder( 'Bisons_Membership' )
		                   ->setMethods( array(
			                   'remoteConfirmPreauth',
			                   'remoteFindPreAuth',
			                   'remoteCreatePreauthBill',
			                   'remotePreAuthURL'
		                   ) )
		                   ->getMock();

		update_user_meta( $u, 'payWhen', 'first' );

		$twentyFirstOfJune = 1434874008;
		$thirtiethOfJune   = 1435622400;
		$firstOfJuly       = 1435708800;
		$fifteenthOfJuly   = 1436918400;
		$lastOfDecember    = 1451520000;

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $twentyFirstOfJune ) );
		$this->assertEquals( '01-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $thirtiethOfJune ) );
		$this->assertEquals( '01-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $firstOfJuly ) );
		$this->assertEquals( '01-08-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $fifteenthOfJuly ) );
		$this->assertEquals( '01-08-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $lastOfDecember ) );
		$this->assertEquals( '01-01-2016', $date );

		update_user_meta( $u, 'payWhen', 'last' );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $twentyFirstOfJune ) );
		$this->assertEquals( '30-06-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $thirtiethOfJune ) );
		$this->assertEquals( '31-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $firstOfJuly ) );
		$this->assertEquals( '31-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $fifteenthOfJuly ) );
		$this->assertEquals( '31-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $lastOfDecember ) );
		$this->assertEquals( '31-01-2016', $date );

		update_user_meta( $u, 'payWhen', 'specificDay' );
		update_user_meta( $u, 'dayOfMonth', '9' );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $twentyFirstOfJune ) );
		$this->assertEquals( '09-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $thirtiethOfJune ) );
		$this->assertEquals( '09-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $firstOfJuly ) );
		$this->assertEquals( '09-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $fifteenthOfJuly ) );
		$this->assertEquals( '09-08-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $lastOfDecember ) );
		$this->assertEquals( '09-01-2016', $date );

		update_user_meta( $u, 'payWhen', 'specificDay' );
		update_user_meta( $u, 'dayOfMonth', '30' );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $twentyFirstOfJune ) );
		$this->assertEquals( '30-06-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $thirtiethOfJune ) );
		$this->assertEquals( '30-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $firstOfJuly ) );
		$this->assertEquals( '30-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $fifteenthOfJuly ) );
		$this->assertEquals( '30-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $lastOfDecember ) );
		$this->assertEquals( '30-01-2016', $date );

		update_user_meta( $u, 'payWhen', 'specificWeekday' );
		update_user_meta( $u, 'whichWeekDay', 'first' );
		update_user_meta( $u, 'weekDay', 'Wednesday' );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $twentyFirstOfJune ) );
		$this->assertEquals( '01-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $thirtiethOfJune ) );
		$this->assertEquals( '01-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $firstOfJuly ) );
		$this->assertEquals( '05-08-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $fifteenthOfJuly ) );
		$this->assertEquals( '05-08-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $lastOfDecember ) );
		$this->assertEquals( '06-01-2016', $date );

		update_user_meta( $u, 'payWhen', 'specificWeekday' );
		update_user_meta( $u, 'whichWeekDay', 'third' );
		update_user_meta( $u, 'weekDay', 'friday' );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $twentyFirstOfJune ) );
		$this->assertEquals( '17-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $thirtiethOfJune ) );
		$this->assertEquals( '17-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $firstOfJuly ) );
		$this->assertEquals( '17-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $fifteenthOfJuly ) );
		$this->assertEquals( '17-07-2015', $date );

		$date = date( 'd-m-Y', $membership->nextPaymentDate( $u, $lastOfDecember ) );
		$this->assertEquals( '15-01-2016', $date );

	}


	function testConfirmPreAuthDDWithSocialTop() {

		$u = $this->user;

		update_user_meta( $u, 'payWhen', 'specificWeekday' );
		update_user_meta( $u, 'whichWeekDay', 'first' );
		update_user_meta( $u, 'weekDay', 'wednesday' );

		$_GET = array(
			'resource_id'   => '12345123',
			'resource_type' => 'pre_authorization',
			'resource_uri'  => 'my_uri',
			'signature'     => 'sig',
			'state'         => 'dd+700+socialTopPlease',
			'player_id'     => $u
		);

		$membership = $this->getMockBuilder( 'Bisons_Membership' )
		                   ->setMethods( array(
			                   'remoteConfirmPreauth',
			                   'remoteFindPreAuth',
			                   'remoteCreatePreauthBill',
			                   'remotePreAuthURL',
			                   'nextPaymentDate'
		                   ) )
		                   ->getMock();

		$membership->method( 'nextPaymentDate' )
		           ->willReturn( 1435225413 );

		$membership->method( 'remoteConfirmPreauth' )
		           ->willReturn( true );

		$preAuth             = new stdClass();
		$preAuth->user_id    = $u;
		$preAuth->name       = 'test membership data';
		$membership->preAuth = $preAuth;

		$membership->method( 'remoteFindPreAuth' )
		           ->willReturn( $preAuth );


		$bill1 = array(
			'amount'             => '7.00',
			'name'               => 'BisonsRFC Subscription Fee',
			'charge_customer_at' => '2015-06-25'
		);

		$bill2 = array(
			'amount' => '10.00',
			'name'   => 'BisonsRFC Social Top'
		);


		$membership->expects( $this->exactly( 2 ) )
		           ->method( 'remoteCreatePreauthBill' )
		           ->with( $this->logicalOr(
			           $this->equalTo( $bill1 ),
			           $this->equalTo( $bill2 )
		           ) );


		$membership->confirmPreAuth();
	}

	function testConfirmPreAuthDDWithoutSocialTop() {

		$u = $this->user;

		update_user_meta( $u, 'payWhen', 'specificWeekday' );
		update_user_meta( $u, 'whichWeekDay', 'first' );
		update_user_meta( $u, 'weekDay', 'wednesday' );

		$_GET = array(
			'resource_id'   => '12345123',
			'resource_type' => 'pre_authorization',
			'resource_uri'  => 'my_uri',
			'signature'     => 'sig',
			'state'         => 'dd+700',
			'player_id'     => $u
		);

		$membership = $this->getMockBuilder( 'Bisons_Membership' )
		                   ->setMethods( array(
			                   'remoteConfirmPreauth',
			                   'remoteFindPreAuth',
			                   'remoteCreatePreauthBill',
			                   'remotePreAuthURL',
			                   'nextPaymentDate'
		                   ) )
		                   ->getMock();

		$membership->method( 'nextPaymentDate' )
		           ->willReturn( 1435225413 );

		$membership->method( 'remoteConfirmPreauth' )
		           ->willReturn( true );

		$preAuth             = new stdClass();
		$preAuth->user_id    = $u;
		$preAuth->name       = 'test membership data';
		$membership->preAuth = $preAuth;

		$membership->method( 'remoteFindPreAuth' )
		           ->willReturn( $preAuth );


		$bill1 = array(
			'amount'             => '7.00',
			'name'               => 'BisonsRFC Subscription Fee',
			'charge_customer_at' => '2015-06-25'
		);


		$membership->expects( $this->exactly( 1 ) )
		           ->method( 'remoteCreatePreauthBill' )
		           ->with(
			           $this->equalTo( $bill1 )
		           );


		$membership->confirmPreAuth();
	}

	function testConfirmPreAuthSPWithSocialTop() {

		$u = $this->user;

		update_user_meta( $u, 'payWhen', 'specificWeekday' );
		update_user_meta( $u, 'whichWeekDay', 'first' );
		update_user_meta( $u, 'weekDay', 'wednesday' );

		$_GET = array(
			'resource_id'   => '12345123',
			'resource_type' => 'pre_authorization',
			'resource_uri'  => 'my_uri',
			'signature'     => 'sig',
			'state'         => 'sp+4800+socialTopPlease',
			'player_id'     => $u
		);

		$membership = $this->getMockBuilder( 'Bisons_Membership' )
		                   ->setMethods( array(
			                   'remoteConfirmPreauth',
			                   'remoteFindPreAuth',
			                   'remoteCreatePreauthBill',
			                   'remotePreAuthURL',
			                   'nextPaymentDate'
		                   ) )
		                   ->getMock();

		$membership->method( 'nextPaymentDate' )
		           ->willReturn( 1435225413 );

		$membership->method( 'remoteConfirmPreauth' )
		           ->willReturn( true );

		$preAuth             = new stdClass();
		$preAuth->user_id    = $u;
		$preAuth->name       = 'test membership data';
		$membership->preAuth = $preAuth;

		$membership->method( 'remoteFindPreAuth' )
		           ->willReturn( $preAuth );


		$bill1 = array(
			'amount' => '48.00',
			'name'   => 'BisonsRFC Subscription Fee'
		);

		$bill2 = array(
			'amount' => '10.00',
			'name'   => 'BisonsRFC Social Top'
		);


		$membership->expects( $this->exactly( 2 ) )
		           ->method( 'remoteCreatePreauthBill' )
		           ->with( $this->logicalOr(
			           $this->equalTo( $bill1 ),
			           $this->equalTo( $bill2 )
		           ) );


		$membership->confirmPreAuth();
	}

	function testConfirmPreAuthSPWithoutSocialTop() {

		$u = $this->user;

		update_user_meta( $u, 'payWhen', 'specificWeekday' );
		update_user_meta( $u, 'whichWeekDay', 'first' );
		update_user_meta( $u, 'weekDay', 'wednesday' );

		$_GET = array(
			'resource_id'   => '12345123',
			'resource_type' => 'pre_authorization',
			'resource_uri'  => 'my_uri',
			'signature'     => 'sig',
			'state'         => 'sp+4800',
			'player_id'     => $u
		);

		$membership = $this->getMockBuilder( 'Bisons_Membership' )
		                   ->setMethods( array(
			                   'remoteConfirmPreauth',
			                   'remoteFindPreAuth',
			                   'remoteCreatePreauthBill',
			                   'remotePreAuthURL',
			                   'nextPaymentDate'
		                   ) )
		                   ->getMock();

		$membership->method( 'nextPaymentDate' )
		           ->willReturn( 1435225413 );

		$membership->method( 'remoteConfirmPreauth' )
		           ->willReturn( true );

		$preAuth             = new stdClass();
		$preAuth->user_id    = $u;
		$preAuth->name       = 'test membership data';
		$membership->preAuth = $preAuth;

		$membership->method( 'remoteFindPreAuth' )
		           ->willReturn( $preAuth );


		$bill1 = array(
			'amount' => '48.00',
			'name'   => 'BisonsRFC Subscription Fee'
		);


		$membership->expects( $this->exactly( 1 ) )
		           ->method( 'remoteCreatePreauthBill' )
		           ->with(
			           $this->equalTo( $bill1 )
		           );


		$membership->confirmPreAuth();
	}


	function testScheduleNextPaymentNewDate() {

		$u = $this->user;

		$membership = $this->getMockBuilder( 'Bisons_Membership' )
		                   ->setMethods( array(
			                   'remoteConfirmPreauth',
			                   'remoteFindPreAuth',
			                   'remoteCreatePreauthBill',
			                   'remotePreAuthURL',
			                   'nextPaymentDate'
		                   ) )
		                   ->getMock();


		$membership->method( 'nextPaymentDate' )
		           ->willReturn( 1435225413 );


		update_user_meta( $u, 'nextPaymentDate', 1435921276);
		update_user_meta( $u, 'currentFee', 700);


		$membership->scheduleNextPayment($u);

		$args = array(
			date('Y-m-d', 1435225413),
			700
		);


		$this->assertTrue(has_action('bisonsCronRequestNextBill'));
		$this->assertInternalType('int', wp_next_scheduled( 'bisonsCronRequestNextBill', $args));


	}

	function testScheduleNextPaymentPastDate() {

		$u = $this->user;

		$membership = $this->getMockBuilder( 'Bisons_Membership' )
		                   ->setMethods( array(
			                   'remoteConfirmPreauth',
			                   'remoteFindPreAuth',
			                   'remoteCreatePreauthBill',
			                   'remotePreAuthURL',
			                   'nextPaymentDate'
		                   ) )
		                   ->getMock();


		$membership->method( 'nextPaymentDate' )
		           ->willReturn( 1425553276 );


		update_user_meta( $u, 'nextPaymentDate', 1435225413 );
		update_user_meta( $u, 'currentFee', 700);


		$membership->scheduleNextPayment($u);

		$args = array(
			date('Y-m-d', 1425553276),
			700
		);


		$this->assertTrue(has_action('bisonsCronRequestNextBill'));
		$this->assertInternalType('int', wp_next_scheduled( 'bisonsCronRequestNextBill', $args));


	}

	function testScheduleNextPaymentSameDate() {

		$u = $this->user;

		$membership = $this->getMockBuilder( 'Bisons_Membership' )
		                   ->setMethods( array(
			                   'remoteConfirmPreauth',
			                   'remoteFindPreAuth',
			                   'remoteCreatePreauthBill',
			                   'remotePreAuthURL',
			                   'nextPaymentDate'
		                   ) )
		                   ->getMock();


		$membership->method( 'nextPaymentDate' )
		           ->willReturn( 1435225413 );


		update_user_meta( $u, 'nextPaymentDate', 1435225413);
		update_user_meta( $u, 'currentFee', 700);


		$membership->scheduleNextPayment($u);

		$args = array(
			date('Y-m-d', 1435225413),
			700
		);


		$this->assertTrue(has_action('bisonsCronRequestNextBill'));
		$this->assertFalse(wp_next_scheduled( 'bisonsCronRequestNextBill', $args));
	}

}