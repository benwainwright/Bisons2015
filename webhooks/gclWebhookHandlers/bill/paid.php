<?php
function bisonsGocardlessBillPaid($resource, $data) {
	delete_post_meta( $mem_form->ID, 'retries' );
	update_user_meta( $user->ID, 'last_payment', $date );
	update_post_meta( $the_post, 'mem_status', 'Active' );

	switch ( get_user_meta( $user->ID, 'payment_status', true ) ) {
		// Single payments pending or failed? Update to single payment paid status
		case 2:
		case 3:
			update_user_meta( $user->ID, 'payment_status', 4 );
			break;

		// Sub created or failed? Update to payments successful status
		case 7:
		case 10:
			update_post_meta( $mem_form, 'payment_status', 8 );
	}

	
}