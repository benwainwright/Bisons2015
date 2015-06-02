<?php
foreach ( $data['bills'] as $bill )
{

	$date = date( 'Y-m-d H:i:s', isset ( $bill['paid_at'] ) ? strtotime( $bill['paid_at'] . ' UTC' ) : time() );

	// Create webhook log
	$hook_log = array(
		'post_status' => 'publish',
		'post_date' => $date,
		'post_type' => 'GCLBillLog'
	);


	$hook_log['post_author'] = $mem_form[0]->post_author;

	// Log webhook
	$id = wp_insert_post( $hook_log );

	update_post_meta($id, 'resource', $resource);
	update_post_meta($id, 'membership_form_id', $mem_form);
	update_post_meta($id, 'source_id', $bill['source_id']);
	update_post_meta($id, 'status', $bill['status']);
	update_post_meta($id, 'amount', $bill['amount']);
	update_post_meta($id, 'amount_minus_fees', $bill['amount_minus_fees']);
	update_post_meta($id, 'source_type', $bill['source_type']);

	$return = array('post_id' => $id);


}
