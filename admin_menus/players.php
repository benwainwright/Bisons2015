<?php

/************************* Insert Menu *******************/
function addPlayersMenu() {
	$membership_form_hook = add_menu_page(
								'Players',
								'Players',
								'committee_perms',
								'players',
								'includeMembershipFormsTemplate',
								'dashicons-groups',
								8 );
	add_action( "load-$membership_form_hook", 'Membership_Forms_Table_Add_Options' );
}
add_action('admin_menu', 'addPlayersMenu');

/****************** Add Screen Options *******************/
function Membership_Forms_Table_Add_Options() {

	global $membershipFormsTable;

	$option = 'per_page';

	$args = array(
		'label' => 'Membership Forms',
		'default' => 10,
		'option' => 'forms_per_page'
	);
	add_screen_option( $option, $args );

	$membershipFormsTable = new Membership_Forms_Table;
}
add_filter('set-screen-option', 'Membership_Forms_Table_Set_Options', 1, 3);

/****************** Set Screen Options *******************/
function Membership_Forms_Table_Set_Options($status, $option, $value)
{
	if ( 'forms_per_page' == $option ) return $value;

	return $status;
}


/****************** Include Template *******************/
function includeMembershipFormsTemplate() {
	include_once( 'templates/players.php' );
}


