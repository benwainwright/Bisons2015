<?php

$query = new WP_Query( array( 'post_type' => 'GCLBillLog', 'posts_per_page' => - 1 ) );

while ( $query->have_posts() ) {

	$query->the_post();

	$id = get_the_author_meta( 'ID' );

	delete_user_meta($id, 'GCLsubscriptionStatus');
	delete_user_meta($id, 'payMethod');
	delete_user_meta($id, 'singlePaymentID');

}

$query = new WP_Query( array( 'post_type' => 'GCLBillLog', 'posts_per_page' => - 1, 'orderby' => 'date', 'order' => 'DESC' ) );

$active = false;

while ( $query->have_posts() ) {

	$query->the_post();

	$id = get_the_author_meta( 'ID' );

	$source = null;

	if ( get_post_meta( get_the_id(), 'source_id', true ) ) {



			if ( 'subscription' === get_post_meta( get_the_id(), 'source_type', true ) ) {


				try {
					$source = GoCardless_Subscription::find( get_post_meta( get_the_id(), 'source_id', true ) );
				} catch ( GoCardless_ApiException $e ) {


					$error = array(
						'code'  => $e->getCode,
						'error' => $e->getMessage(),
						'file'  => $e->getFile(),
						'line'  => $e->getLine(),
						'trace' => $e->getTrace()
					);

					new dBug( $error );
				}
			} else {
				if ( 'pre_authorization' === get_post_meta( get_the_id(), 'source_type', true ) ) {
					try {
						$source = GoCardless_PreAuthorization::find( get_post_meta( get_the_id(), 'source_id', true ) );
					} catch ( GoCardless_ApiException $e ) {


						$error = array(
							'code'  => $e->getCode,
							'error' => $e->getMessage(),
							'file'  => $e->getFile(),
							'line'  => $e->getLine(),
							'trace' => $e->getTrace()
						);

						new dBug( $error );
					}
				}
			}

		if ($source->status == 'active') {
			$active = true;
		}


		if ($id == 2) {
			new dBug(get_post_meta( get_the_id(), 'id', true ) );
			new dBug(get_post_meta( get_the_id(), 'source_id', true ) );
			new dBug(get_post_meta( get_the_id(), 'status', true ) );
			new dBug($source);
			echo "<br />";
		}


			if ( null !== $source || get_post_meta( get_the_id(), 'amount', true ) < 15 ) {
				update_user_meta( $id, 'payMethod', 'dd' );

				if ( $active ) {
					update_user_meta( $id, 'GCLsubscriptionStatus', $source->status );
				}

				else {
					update_user_meta( $id, 'GCLsubscriptionStatus', $source->status );
				}

				update_user_meta( $id, 'GCLStatusChangeDate', get_the_date('U') );
			} else {

				update_user_meta( $id, 'singlePaymentID', get_post_meta( get_the_id(), 'id', true ) );
				update_user_meta( $id, 'payMethod', 'single' );
				update_user_meta( $id, 'GCLStatusChangeDate', get_the_date('U') );
			}
		}
	
}
