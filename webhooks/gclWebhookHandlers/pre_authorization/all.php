<?php
foreach ( $data['pre_authorizations'] as $preAuth )
{

	$date = date( 'Y-m-d H:i:s' );

	// Create webhook log
	$hook_log = array(
		'post_status' => 'publish',
		'post_date' => $date,
		'post_type' => 'GCLSubLog'
	);

	// Look for membership forms that match the source id. If not look for forms that match the id


	$hook_log['post_author'] = $mem_form[0]->post_author;
	$mem_form = $mem_form[0]->ID;


	// Log webhook
	$id = wp_insert_post( $hook_log );

	update_post_meta($id, 'id', $preAuth['source_id']);
	update_post_meta($id, 'status', $preAuth['status']);
	update_post_meta($id, 'uri', $preAuth['uri']);

}


