<?php

$retries = get_post_meta($mem_form->ID, 'retries', true) ? get_post_meta($mem_form->ID, 'retries', true) : 0;

$bill = GoCardless_Bill::find( $resource['source_id'] );

// Retry the bill three times if possible
if ( $retries < 3 && $bill->can_be_retried() ) {

	$bill->retry();

	if( 0 === $retries ) {
		send_mandrill_template($mem_form->post_author, 'payment-failed-1', false, false, 'Payment Failed', 'membership@bisonsrfc.co.uk');
	}

	$retries++;
	update_post_meta( $mem_form->ID, 'retries', $retries);

}

// Downgrade membership
else {
	switch ( get_post_meta($mem_form->ID, 'payment_status', true) ) {
		case 2: update_post_meta($mem_form->ID, 'payment_status', 3);
		case 7: case 8: update_post_meta($mem_form->ID, 'payment_status', 10);
	}
	update_post_meta($mem_form->ID, 'mem_status', 'Inactive' );
}
