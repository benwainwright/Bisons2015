<?php
switch ( get_user_meta($user->ID, 'payment_status', true) )
{
	case 2: update_user_meta($user->ID, 'payment_status', 5);
}
