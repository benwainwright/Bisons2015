<?php
if (!INCLUDED) exit;

if (current_user_can('reset_2fa')) {

	$_POST['confirm_action'] = 'true';

	foreach ( $_POST['user_id'] as $user ) {

		delete_user_meta( $user, '2FA_secret' );
		delete_user_meta( $user, '2FA_setup' );
		
	}
}
