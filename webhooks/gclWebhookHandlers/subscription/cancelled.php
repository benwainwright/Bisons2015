<?php

function bisonsGocardlessSubscriptionCancelled($resource, $data) {

	switch ( get_user_meta( $user->ID, 'payment_status', true ) ) {
		// Subscription created or payments successful? Update to Sub cancelled status
		case 7:
		case 8:
		case 10:
			update_user_meta( $user->ID, 'payment_status', 9 );
	}
	update_user_meta( $user->ID, 'mem_status', 'Inactive' );


}