<?php
/************************* Insert Menu *******************/
function add_bills_menu() {
	$bills_menu_hook = add_submenu_page( 'payment', 'Bills', 'Bills', 'committee_perms', 'bills', 'GCL_Bills_Callback' );
	add_action( "load-$bills_menu_hook", 'Bills_Table_Add_Options' );
}
add_action ('admin_menu', 'add_bills_menu');


/****************** Add Screen Options *******************/
function Bills_Table_Add_Options() {

	global $billsFormsTable;
	$option = 'per_page';

	$args = array(
		'label' => 'Bills',
		'default' => 10,
		'option' => 'bills_per_page'
	);

	add_screen_option( $option, $args );
	$billsFormsTable = new GCLBillsTable;
}
add_filter('set-screen-option', 'Bills_Table_Set_Options', 10, 3);

/****************** Set Screen Options *******************/
function Bills_Table_Set_Options($status, $option, $value) {
	if ( 'bills_per_page' === $option ) return $value;
	return $status;
}

/************************* Include Template *******************/
function GCL_Bills_Callback() {
	include_once( 'templates/payments-bills.php' );
}
