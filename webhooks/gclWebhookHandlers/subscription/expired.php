<?php
function bisonsGocardlessSubscriptionExpired($resource, $data) {
	switch ( get_user_meta( $user->ID, 'payment_status', true ) ) {
		// Subscription created or payments successful? Update to Sub ended status
		case 7:
		case 8:
		case 9:
		case 10:
			update_user_meta( $user->ID, 'payment_status', 11 );
	}
	update_user_meta( $user->ID, 'mem_status', 'Inactive' );
}