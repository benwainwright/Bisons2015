<?php

// Load official GoCardless library
include_once( __DIR__ . '/../GoCardless/init.php' );
include_once( __DIR__ . '/../helper_functions/getMembersShipFormFromGCLID.php');

$webhook = file_get_contents('php://input');
$webhook_array = json_decode( $webhook, true );

if (GoCardless::validate_webhook( $webhook_array['payload'] )) {
    $data = $webhook_array['payload'];

	$mem_form = getMembershipFormFromGCLID($bill['source_id'] );
	$mem_form = $mem_form ? $mem_form :  getMembershipFormFromGCLID($bill['id']  );
	$mem_form = $mem_form[0]->ID;


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