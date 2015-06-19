<?php
function bisonsGocardlessBill( $resource, $data ) {


	// Determine user
	try {
		$bill = GoCardless_Bill::find( $resource['id'] );
		$user   = get_users( array( 'meta_key' => 'GCLUserID', $bill->user_id ) )[0];
		$source = null;


		// If there is a source ID, lookup the status of the subscription/preauth
		if ( isset ( $resource['source_type'] ) ) {
			if ( 'subscription' === $resource['source_type'] ) {
				$source = GoCardless_Subscription::find( $resource['source_id'] );
			} else {
				if ( 'pre_authorization' === $resource['source_type'] ) {
					$source = GoCardless_PreAuthorization::find( $resource['source_id'] );
				}
			}

		}

	// Catch any exceptions thrown and output them as JSON if caught
	} catch ( Exception $e ) {
		wp_send_json_error( $e );
		exit;
	}

	if ( null !== $source ) {
		update_user_meta( $user->ID, 'GCLsubscriptionStatus', $source->status );
	}

	// Check if bill already exists
	$query = new WP_Query(
		array(
			'post_type'      => 'GCLBillLog',
			'posts_per_page' => 1,
			'meta_key'       => 'id',
			'meta_value'     => $bill->id
		) );

	if ( $query->have_posts() ) {
		$query->the_post();
		if ( isset ( $bill->paid_at ) ) {
			update_post_meta( get_the_id(), 'paid_at', strtotime( $resource['paid_at'] . ' UTC' ) );
		}

		update_post_meta( get_the_id(), 'action', $data['action'] );
		update_post_meta( get_the_id(), 'status', $resource['status'] );
		$action   = 'log_updated';
		$id       = get_the_id();
		$postMeta = array(
			'action' => $data['action'],
			'status' => $resource['status']
		);
	} else {
		$date = date( 'Y-m-d H:i:s' );

		// Create new webhook log
		$hook_log = array(
			'post_status' => 'publish',
			'post_date'   => $date,
			'post_type'   => 'GCLBillLog'
		);

		$hook_log['post_author'] = $user->ID;

		// Log webhook
		$id = wp_insert_post( $hook_log );

		update_post_meta( $id, 'id', $resource['id'] );
		update_post_meta( $id, 'source_id', $resource['source_id'] );
		update_post_meta( $id, 'action', $data['action'] );
		update_post_meta( $id, 'status', $resource['status'] );
		update_post_meta( $id, 'amount', $resource['amount'] );
		update_post_meta( $id, 'amount_minus_fees', $resource['amount_minus_fees'] );
		update_post_meta( $id, 'source_type', $resource['source_type'] );
		$action   = 'log_created';
		$postMeta = array(
			'action'            => $data['action'],
			'status'            => $resource['status'],
			'id'                => $resource['id'],
			'source_id'         => $resource['source_id'],
			'amount'            => $resource['amount'],
			'amount_minus_fees' => $resource['amount_minus_fees'],
			'source_type'       => $resource['source_type']
		);
	}


	if ( $id > 0 ) {
		$return[] = array(
			'type'    => 'bill',
			'action'  => $action,
			'post_id' => $id,
			'data'    => $postMeta
		);
	}

	return $return ? $return : null;
}