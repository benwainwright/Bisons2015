<?php

function bisonsGocardlessPreauthorizationExpired($resource, $data) {
	try {
		$preAuth = GoCardless_PreAuthorization::find( $resource['id'] );
		$user    = get_users( array( 'meta_key' => 'GCLUserID', 'meta_value' => $preAuth->user_id ) )[0];

	} catch ( Exception $e ) {
		wp_send_json_error( $e );
		exit;
	}

}