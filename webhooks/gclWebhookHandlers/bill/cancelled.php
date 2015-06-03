<?php
switch ( get_post_meta($mem_form->ID, 'payment_status', true) )
{
	case 2: update_post_meta($mem_form->ID, 'payment_status', 5);
}
