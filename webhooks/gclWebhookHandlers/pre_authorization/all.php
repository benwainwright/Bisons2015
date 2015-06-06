<?php

$date = date( 'Y-m-d H:i:s' );

// Create webhook log
$hook_log = array(
	'post_status' => 'publish',
	'post_date' => $date,
	'post_type' => 'GCLSubLog'
);

// Look for membership forms that match the source id. If not look for forms that match the id


$hook_log['post_author'] = $mem_form->post_author;

// Log webhook
$id = wp_insert_post( $hook_log );

update_post_meta($id, 'id', $resource['source_id']);
update_post_meta($id, 'status', $resource['status']);
update_post_meta($id, 'uri', $resource['uri']);


