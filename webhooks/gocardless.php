<?php

// Load official GoCardless library
include_once( __DIR__ . '/../GoCardless/init.php' );

$webhook = file_get_contents('php://input');
$webhook_array = json_decode( $webhook, true );
$webhook_valid = GoCardless::validate_webhook( $webhook_array['payload'] );

if (TRUE === $webhook_valid)
{
    $data = $webhook_array['payload'];


	// Include appropriate resource handler
	include_once( __DIR__ . '/gclWebhookHandlers/' . $data['resource_type'] . '/all.php');

	// If action handler exists, include it
	$handler =  __DIR__ . '/gclWebhookHandlers/' . $data['resource_type'] . '/' . $data['action'] . '.php';
	if ( file_exists( $handler ) )
	{
		include_once( $handler );

		// Success header
		header('HTTP/1.1 200 OK');
	}

	header('Content-type: application/json');
	echo $return ? json_encode( $return ) : '';
} 
else 
{
    header('HTTP/1.1 403 Invalid signature');
}