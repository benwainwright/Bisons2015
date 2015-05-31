<?php

// Load official GoCardless library
include_once( __DIR__ . '/../GoCardless/init.php' );

$webhook = file_get_contents('php://input');
$webhook_array = json_decode( $webhook, true );
$webhook_valid = GoCardless::validate_webhook( $webhook_array['payload'] );

if (TRUE === $webhook_valid)
{
    $data = $webhook_array['payload'];

    // Create webhook log
    $hook_log = array(
        'post_status' => 'publish',
        'post_date' => date('Y-m-d H:i:s'),
        'post_type' => 'goCardlessWebhook'
    );

	// Include appropriate resource handler
	include_once( __DIR__ . '/' . $data['resource_type'] . '/all.php');

	// If action handler exists, include it
	$handler =  __DIR__ . '/' . $data['resource_type'] . '/' . $data['action'] . '.php';
	if ( file_exists( $handler ) )
	{
		include_once( $handler );

		// Success header
		header('HTTP/1.1 200 OK');
	}

} 
else 
{
    header('HTTP/1.1 403 Invalid signature');
}