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
	global $billsTable;

	$option = 'per_page';

	if ( isset( $_GET['user_id'] ) ) {
		$args = array(
			'label' => 'Bills per Page',
			'default' => 20,
			'option' => 'bills_per_page'
		);

		$billsTable = new GCLBillsTable;

	}

	else {
		$args = array(
			'label' => 'Users per Page',
			'default' => 10,
			'option' => 'forms_per_page'
		);
		$membershipFormsTable = new Membership_Forms_Table;
	}


	add_screen_option( $option, $args );
}

/****************** Set Screen Options *******************/

function Membership_Forms_Table_Set_Options($status, $option, $value)
{
	if ( 'bills_per_page' === $option && isset ( $_GET['user_id'] ) ) return $value;
	else if ( 'forms_per_page' === $option  ) return $value;
	return $status;
}
add_filter('set-screen-option', 'Membership_Forms_Table_Set_Options', 10, 3);


/****************** Include Template *******************/
function includeMembershipFormsTemplate() {
	include_once( 'templates/players.php' );
}


