<?php
/************************* Insert Menu ************************/
function addEditAttendanceRegisterMenu() {
	$attRegHook = add_submenu_page ( 'players', 'Registers', 'Attendance Register   ', 'committee_perms', 'registers', 'includeRegisterTemplate' );
	add_action( "load-$attRegHook", 'attRegisterAddOptions' );

}
add_action('admin_menu','addEditAttendanceRegisterMenu');

/****************** Add Screen Options *******************/
function attRegisterAddOptions() {

	global $attRegisterTable;
	$option = 'per_page';

	$args = array(
		'label' => 'Players',
		'default' => 10,
		'option' => 'players_per_page'
	);

	add_screen_option( $option, $args );
	$billsFormsTable = new RegisterListTable;
}
add_filter('set-screen-option', 'attRegisterSetOptions', 10, 3);

/****************** Set Screen Options *******************/
function attRegisterSetOptions($status, $option, $value) {
	if ( 'players_per_page' === $option ) return $value;
	return $status;
}

/************************* Include Template *******************/
function includeRegisterTemplate() {
	include( 'templates/players-register.php');
}