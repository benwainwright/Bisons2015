<?php

// Determine user

echo "1\n";

$bill = GoCardless_Bill::find($resource['id']);
$user = get_users(array('meta_key' => 'GCLUserID', $bill->user_id))[0];
echo "2\n";
$source = null;

echo "2\n";

// If there is a source ID, lookup the status of the subscription/preauth
if ( isset ($resource['source_type'])  ) {
	echo "3\n";
	if ('subscription' === $resource['source_type'] ) {
		$source = GoCardless_Subscription::find( $resource['source_id']);
		echo "4\n";
	}

	else {
		if ('pre_authorization' === $resource['source_type']) {
			echo "5\n";
			$source = GoCardless_PreAuthorization::find( $resource['source_id']);
		}
	}

}


if ( null !== $source ) {
	echo "6\n";
	update_user_meta($user->ID, 'GCLsubscriptionStatus', $source->status);
}
echo "7\n";

// Check if bill already exists
$query = new WP_Query(
	array( 'post_type'      => 'GCLBillLog',
	       'posts_per_page' => 1,
	       'meta_key'       => 'id',
		   'meta_value'     => $bill->id) );
echo "8\n";
if ($query->have_posts()) {
	$query->the_post();
	echo "9\n";
	if ( isset ( $bill->paid_at ) ) {
		update_post_meta( get_the_id(), 'paid_at', strtotime( $resource['paid_at'] . ' UTC'));
		echo "10\n";
	}

	update_post_meta( get_the_id(), 'action', $data['action'] );
	update_post_meta( get_the_id(), 'status', $resource['status'] );
	$action = 'log_updated';
	$id = get_the_id();
	echo "11\n";
	$postMeta = array(
		'action' =>  $data['action'],
		'status' =>  $resource['status']
	);
	echo "12\n";
}

else {
	echo "13\n";
	$date = date( 'Y-m-d H:i:s');

	// Create new webhook log
	$hook_log = array(
		'post_status' => 'publish',
		'post_date'   => $date,
		'post_type'   => 'GCLBillLog'
	);

	$hook_log['post_author'] = $user->ID;
	echo "14\n";

	// Log webhook
	$id = wp_insert_post( $hook_log );

	update_post_meta( $id, 'id', $resource['id'] );
	update_post_meta( $id, 'source_id', $resource['source_id'] );
	update_post_meta( $id, 'action', $data['action'] );
	update_post_meta( $id, 'status', $resource['status'] );
	update_post_meta( $id, 'amount', $resource['amount'] );
	update_post_meta( $id, 'amount_minus_fees', $resource['amount_minus_fees'] );
	update_post_meta( $id, 'source_type', $resource['source_type'] );
	$action = 'log_created';
	echo "15\n";
	$postMeta = array(
		'action'            =>  $data['action'],
		'status'            =>  $resource['status'],
		'id'                =>  $resource['id'],
		'source_id'         =>  $resource['source_id'],
		'amount'            =>  $resource['amount'],
		'amount_minus_fees' =>  $resource['amount_minus_fees'],
		'source_type'       =>  $resource['source_type']
	);
	echo "16\n";
}


if ($id > 0) {
	echo "17\n";
	$return[] = array(
		'type'      => 'bill',
		'action'    => $action,
		'post_id'   => $id,
		'data'      => $postMeta
	);
}