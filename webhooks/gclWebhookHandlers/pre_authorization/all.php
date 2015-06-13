<?php

// Determine user
$preAuth = GoCardless_PreAuthorization::find($resource['id']);
$user = get_users(array('meta_key' => 'GCLUserID', $preAuth->user_id))[0];

// Check if bill already exists
$query = new WP_Query(
	array( 'post_type'      => 'GCLPreAuthLog',
	       'posts_per_page' => 1,
	       'meta_key'       => 'id',
	       'meta_value'     => $preAuth->id) );

if ($query->have_posts()) {
	$query->the_post();

	update_post_meta( get_the_id(), 'status', $resource['status'] );
	update_post_meta( $id, 'remaining_amount', $resource['remaining_amount'] );

	$action = 'log_updated';
	$id = get_the_id();
}

else {

	$date = date( 'Y-m-d H:i:s');

	// Create new webhook log
	$hook_log = array(
		'post_status' => 'publish',
		'post_date'   => $date,
		'post_type'   => 'GCLPreAuthLog'
	);

	$hook_log['post_author'] = $user->ID;


	// Log webhook
	$id = wp_insert_post( $hook_log );

	update_post_meta( $id, 'id', $resource['id'] );
	update_post_meta( $id, 'name', $resource['name'] );
	update_post_meta( $id, 'expires_at', $resource['expires_at'] );
	update_post_meta( $id, 'interval_length', $resource['interval_length'] );
	update_post_meta( $id, 'interval_unit', $resource['interval_unit'] );
	update_post_meta( $id, 'max_amount', $resource['max_amount'] );
	update_post_meta( $id, 'remaining_amount', $resource['remaining_amount'] );
	update_post_meta( $id, 'next_interval_start', $resource['next_interval_start'] );
	$action = 'log_created';
}


if ($id > 0) {

	$return = array(
		'type'   => 'pre_authorization',
		'action' => $action
	);
}