<?php
switch ( get_post_meta($mem_form->ID, 'payment_status', true) )
{
	// Subscription created or payments successful? Update to Sub ended status
	case 7: case 8: case 9: case 10: update_post_meta($mem_form->ID, 'payment_status', 11);
}
update_post_meta($mem_form->ID, 'mem_status', 'Inactive' );