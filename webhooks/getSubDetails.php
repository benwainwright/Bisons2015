<?php

$query = new WP_Query( array( 'post_type' => 'GCLBillLog', 'posts_per_page' => - 1 ) );

while ( $query->have_posts() ) {

	$query->the_post();

	delete_post_meta(get_the_id(), 'GCLsubscriptionStatus');
	delete_post_meta(get_the_id(), 'payMethod');
	delete_post_meta(get_the_id(), 'singlePaymentID');


	$id = get_the_author_meta( 'ID' );


	if ( get_post_meta( get_the_id(), 'source_id', true ) ) {

		if ( ! get_user_meta( $id, 'GCLsubscriptionStatus', true ) ){

			echo "test";

		update_user_meta( $id, 'payMethod', 'dd' );



		if ( 'subscription' === get_post_meta( get_the_id(), 'source_type', true ) ) {

			echo "test2";

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
				echo "test3";
			}
		}
			echo "test4";
		new dBug( $source );

		update_user_meta( $id, 'GCLsubscriptionStatus', $source->status );

	}


	} else {

		echo "test5";
		update_user_meta( $user->ID, 'singlePaymentID', get_post_meta( get_the_id(), 'id', true ) );
		update_user_meta( $id, 'payMethod', 'single' );
	}
	echo "test6";
}
echo "test7";