<?php



// Check if bill already exists
$query = new WP_Query( array( 'post_type' => 'GCLBillLog', 'posts_per_page' => 1 ) );

if ($query->have_posts()) {
	$query->the_post();

	if ( isset ( $bill['paid_at'] ) ) {
		update_post_meta( get_the_id(), 'paid_at', strtotime( $resource['paid_at'] . ' UTC'));
	}

	update_post_meta( get_the_id(), 'action', $data['action'] );
	update_post_meta( get_the_id(), 'status', $resource['status'] );

}

else {

	// Create new webhook log
	$hook_log = array(
		'post_status' => 'publish',
		'post_date'   => $date,
		'post_type'   => 'GCLBillLog'
	);

	$hook_log['post_author'] = $user->ID;

	$date = date( 'Y-m-d H:i:s');

	// Log webhook
	$id = wp_insert_post( $hook_log );

	update_post_meta( $id, 'id', $resource['source_id'] );
	update_post_meta( $id, 'source_id', $resource['source_id'] );
	update_post_meta( $id, 'action', $data['action'] );
	update_post_meta( $id, 'status', $resource['status'] );
	update_post_meta( $id, 'amount', $resource['amount'] );
	update_post_meta( $id, 'amount_minus_fees', $resource['amount_minus_fees'] );
	update_post_meta( $id, 'source_type', $resource['source_type'] );

	$return = array( 'log_post_id' => $id );

}