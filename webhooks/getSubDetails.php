<?php

$query = new WP_Query( array( 'post_type' => 'GCLBillLog', 'posts_per_page' => -1) );

while ($query->have_posts()) {

	$query->the_post();

	$id = get_the_author_meta('ID');


	if ( get_post_meta( get_the_id(), 'source_id', true) ) {

		update_user_meta($id, 'payMethod', 'dd' );

		if ('subscription' === get_post_meta( get_the_id(), 'source_type', true) ) {

			try {
				$source = GoCardless_Subscription::find( $bill->source_id );
			} catch( GoCardless_ApiException $e ) {
				new dBug($e);
			}
		}

		else {
			if ('pre_authorization' === get_post_meta( get_the_id(), 'source_type', true) ) {
				try{
				$source = GoCardless_PreAuthorization::find( $bill->source_id);
				} catch( GoCardless_ApiException $e ) {


					$error = array(
						'code' => $e->getCode,
						'error' => $e->getMessage(),
						'file'  => $e->getFile(),
						'line'  => $e->getLine(),
						'trace'  => $e->getTrace()
					);

					new dBug($error);
				}

			}
		}

		update_user_meta($id, 'GCLsubscriptionStatus', $source->status);
	}

	else {
		update_user_meta($user->ID, 'singlePaymentID', get_post_meta(get_the_id(), 'id', true));
		update_user_meta($id, 'payMethod', 'single' );
	}
}