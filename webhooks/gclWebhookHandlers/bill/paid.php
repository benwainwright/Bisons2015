<?php
function bisonsGocardlessBillPaid($resource, $data) {



	try {
		$bill   = GoCardless_Bill::find( $resource['id'] );
		$user   = get_users( array( 'meta_key' => 'GCLUserID', 'meta_value' => $bill->user_id ) )[0];
		$source = null;
	}
	catch(Exception $e) {

	}
	delete_post_meta( $user->ID, 'retries' );
	update_user_meta( $user->ID, 'last_payment', $date );

}