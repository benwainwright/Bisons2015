<?php

function bisonsGocardlessBillCancelled($resource, $data) {

	try {
		$bill   = GoCardless_Bill::find( $resource['id'] );
		$user   = get_users( array( 'meta_key' => 'GCLUserID',  'meta_value' => $bill->user_id ) )[0];
		$source = null;
	}
	catch(Exception $e) {

	}
	switch ( get_user_meta( $user->ID, 'payment_status', true ) ) {
		case 2:
			update_user_meta( $user->ID, 'payment_status', 5 );
	}
}
