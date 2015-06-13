<?php

// Load official GoCardless library
include_once( __DIR__ . '/../GoCardless/init.php' );

$webhook = file_get_contents('php://input');
$webhookArray = json_decode( $webhook, true );

if (GoCardless::validate_webhook( $webhookArray['payload'] )) {

    $data = $webhookArray['payload'];

	// loop through each resource
	foreach ( $data[$data['resource_type'] . 's'] as $resource )
	{

		// Include appropriate resource handler
		include_once(  __DIR__ . '/gclWebhookHandlers/' . $data['resource_type'] . '/all.php');

		// If action handler exists, include it
			if ( file_exists( $resourceHandler = __DIR__ . '/gclWebhookHandlers/' . $data['resource_type'] . '/' . $data['action'] . '.php' ) ) {
			include_once( $resourceHandler );
		}
	}


	// Success header
	header( 'HTTP/1.1 200 OK' );
	header( 'Content-type: application/json' );
	wp_send_json_success($return);
}

else {
	wp_send_json_error();
	header( 'HTTP/1.1 403 Invalid signature' );
}