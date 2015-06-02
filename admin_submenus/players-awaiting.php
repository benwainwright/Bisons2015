<?php
/************************* Insert Menu *******************/
function addAwaiting() {
	$awaitingHook = add_submenu_page( 'players', 'Awaiting Membership', 'Awaiting Membership',
		'committee_perms', 'awaiting-membership', 'includeAwaitingTemplate' );

	add_action( "load-$awaitingHook", 'Awaiting_Membership_Forms_Table_Add_Options' );
}
add_action('admin_menu', 'addAwaiting');

/****************** Add Screen Options *******************/
function Awaiting_Membership_Forms_Table_Add_Options() {
	$option = 'per_page';
	$args = array(
		'label' => 'Users',
		'default' => 10,
		'option' => 'awaiting_users_per_page'
	);
	add_screen_option( $option, $args );
}

/****************** Set Screen Options *******************/
function Awaiting_Membership_Forms_Table_Set_Options($status, $option, $value)
{
	return $value;
}

/************************* Include Template *******************/
function includeAwaitingTemplate() {
	include_once( 'players-awaiting.php' );
}
