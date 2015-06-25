<?php

function bisonsGocardlessBillCancelled($resource, $data) {

	try {
		$bill   = GoCardless_Bill::find( $resource['id'] );
		$user   = get_users( array( 'meta_key' => 'GCLUserID',  'meta_value' => $bill->user_id ) )[0];
		$source = null;
	}
	catch(Exception $e) {

	}

}
