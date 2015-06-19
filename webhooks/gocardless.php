<?php

// Load official GoCardless library
include_once( __DIR__ . '/../GoCardless/init.php' );

foreach ( glob( __DIR__ . '/gclWebhookHandlers/*/*' ) as $fileName ) {
	include_once( $fileName );
}

$webhook      = file_get_contents( 'php://input' );
$webhookArray = json_decode( $webhook, true );

try {
	$hookIsValid = GoCardless::validate_webhook( $webhookArray['payload'] );
} catch ( Exception $e ) {

	wp_send_json_error( $e );
	header( 'HTTP/1.1 403 Invalid signature' );

}

if ( $hookIsValid ) {

	$data          = $webhookArray['payload'];
	$action        = ucwords( $data['action'] );
	$resource_type = str_replace( '_', '', ucwords( $data['resource_type'] ) );

	// loop through each resource
	foreach ( $data[ $data['resource_type'] . 's' ] as $resource ) {
		$returnResource = call_user_func( "bisonsGocardless$resource_type", $resource, $data );
		$returnAction   = call_user_func( "bisonsGocardless$resource_type$action", $resource, $data );

		if ( $returnResource && $returnAction ) {
			$returnResource = array_merge( $returnResource, $returnAction );
		} else if ( ! $returnResource ) {
			$returnResource = $returnAction;
		}
	}

	$return[] = $returnResource;


	// Success header
	header( 'HTTP/1.1 200 OK' );
	header( 'Content-type: application/json' );
	wp_send_json_success( $return );
} else {
	wp_send_json_error();
	header( 'HTTP/1.1 403 Invalid signature' );
}