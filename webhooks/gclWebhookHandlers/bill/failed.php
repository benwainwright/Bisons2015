<?php
switch ( get_post_meta($mem_form, 'payment_status', true) )
{
	case 2: update_post_meta($mem_form, 'payment_status', 3);
	case 7: case 8: update_post_meta($mem_form, 'payment_status', 10);
}
update_post_meta($the_post, 'mem_status', 'Inactive' );