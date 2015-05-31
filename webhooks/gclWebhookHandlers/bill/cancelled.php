<?php
switch ( get_post_meta($mem_form, 'payment_status', true) )
{
	case 2: update_post_meta($mem_form, 'payment_status', 5);
}
