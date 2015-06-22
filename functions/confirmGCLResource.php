<?php

function confirmGCLPreauth( $queryString, $user ) {

	$confirm_params = array(
		'resource_id'   => $queryString['resource_id'],
		'resource_type' => $queryString['resource_type'],
		'resource_uri'  => $queryString['resource_uri'],
		'signature'     => $queryString['signature']
	);

	if ( isset( $queryString['state'] ) ) {
		$confirm_params['state'] = $queryString['state'];
	}

	try {
		$confirmed_resource = GoCardless::confirm_resource( $confirm_params );

	} catch ( Exception  $e ) {
		errorMessage( $e->getMessage() );
	}

	if ( $confirmed_resource ) {

		$vars = explode( '+', $queryString['state'] );
		$type = $vars[0];
		try {
			$preAuth = GoCardless_PreAuthorization::find( $queryString['resource_id'] );
		} catch ( Exception $e ) {
			errorMessage( $e->getMessage() );
		}

		$amount = $vars[1];

		update_user_meta( $user, 'payMethod', $type );  // Single payment pending
		update_user_meta( $user, 'currentFee', $amount );
		update_user_meta( $user, 'GCLUserID', $bill->user_id );
		update_user_meta( $user, 'GCLSubID', $queryString['resource_id'] );
		update_user_meta( $user, 'memName', $resource->name );

		if ( $type == "dd" ) {
			bisonsScheduleNextPayment( $user );
		} else {
			try {
				$bill = array(
					'name'               => __( 'BisonsRFC Subscription Fee', 'bisonsRFC' ),
					'amount'             => pence_to_pounds( $amount, false ),
					'charge_customer_at' => date( 'Y-m-d' )
				);

				$preAuth->create_bill( $bill );
			} catch ( Exception $e ) {
				errorMessage( $e->getMessage() );
			}
		}

		if ( isset( $vars[2] ) ) {
			try {
				$bill = array(
					'name'               => __( 'BisonsRFC Social Top', 'bisonsRFC' ),
					'amount'             => '10.00',
					'charge_customer_at' => date( 'Y-m-d' )
				);

				new dBug($bill);


				$preAuth->create_bill( $bill );
			} catch ( Exception $e ) {
				errorMessage( $e->getMessage() );
			}
		}
	}
	return $confirmed_resource;
}