<?php
if ( ! INCLUDED ) {
	exit;
}

$billsFile = __DIR__ . '/bills.csv';

$bills = importCSV( $billsFile );

$query = new WP_Query( array( 'post_type' => 'GCLBillLog', 'posts_per_page' => - 1 ) );

while ( $query->have_posts() ) {
	$query->the_post();

	wp_delete_post( get_the_id(), true );
}

$users = get_users();

foreach($users as $user) {

	delete_user_meta($user->ID, 'GCLUserID');

}




foreach ( $bills as $index => $billRow ) {

	$bill = GoCardless_Bill::find( $billRow['Bill ID'] );

	$user = null;

	if ( count( $user = get_users( array( 'meta_key' => 'GCLSubID', 'meta_value' => $bill->source_id ) ) ) == 0 ) {
		$user = get_users( array( 'meta_key' => 'GCLSubID', 'meta_value' => $bill->id ) );
	}


	$user = ($user[0]);

	if (null !== $user) {
		$date = date( 'Y-m-d H:i:s', isset ( $bill->paid_at ) ? strtotime( $bill->paid_at ) : time() );


		// Create webhook log
		$hook_log                = array(
			'post_status' => 'publish',
			'post_date'   => $date,
			'post_type'   => 'GCLBillLog'
		);
		$hook_log['post_author'] = $user->ID;

		// Add a GCLUserID meta tag to that user if it doesn't exist
		update_user_meta( $user->ID, 'GCLUserID', $bill->user_id );

		// Create the bill
		$id = wp_insert_post( $hook_log );
		update_post_meta( $id, 'id', $bill->id );
		update_post_meta( $id, 'source_id', $bill->source_id );
		update_post_meta( $id, 'action', $bill->status );
		update_post_meta( $id, 'status', $bill->status );
		update_post_meta( $id, 'amount', $bill->amount );
		update_post_meta( $id, 'amount_minus_fees', $bill->amount_minus_fees );
		update_post_meta( $id, 'source_type', $bill->source_type );

	}
}



