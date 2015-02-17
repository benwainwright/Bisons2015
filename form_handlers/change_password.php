<?php
if (!INCLUDED)
    exit ;


if ( ! $_POST['newpassconfirm'] )
{
    $GLOBALS['bisons_flash_message'] = "Please confirm your new password";
	$cancel = true;
}

if ( ! $_POST['newpass'] )
{
    $GLOBALS['bisons_flash_message'] = "You didn't enter a new password";
	$cancel = true;
}

if ( ! $_POST['oldpass'] )
{
    $GLOBALS['bisons_flash_message'] = "You didn't enter your old password";
	$cancel = true;
}

$user = wp_get_current_user();

if ( ! $cancel && wp_check_password ( $_POST['oldpass'], $user->data->user_pass, $user->ID ) )
{
	wp_set_password( $_POST['newpass'], $user->ID );
	wp_logout();
	wp_redirect( home_url() ); 
	exit;
}
else
{
    $GLOBALS['bisons_flash_message'] = "You didn't enter your current password correctly";
}