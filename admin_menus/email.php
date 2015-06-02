<?php
/************************* Insert Menu *******************/
function addEmailPageMenu() {
	add_menu_page( 'Email', 'Email', 'committee_perms', 'email', 'email_menu_callback', 'dashicons-email' );
	$emailpagehook = add_submenu_page( 'email', 'Log', 'Log', 'committee_perms', 'email' );
	add_action( "load-$emailpagehook", 'Emails_Table_Add_Options' );
}
add_action('admin_menu','addEmailPageMenu');

/****************** Add Screen Options *******************/
function Emails_Table_Add_Options() {
	$option = 'per_page';
	$args = array(
		'label' => 'Emails',
		'default' => 10,
		'option' => 'emails_per_page'
	);
	add_screen_option( $option, $args );
}
add_filter('set-screen-option', 'Emails_Table_Add_Options', 10, 3);

/****************** Set Screen Options *******************/
function Emails_Table_Set_Options($status, $option, $value)
{
	if ( 'emails_per_page' == $option ) return $value;
	return $status;
}

/************************* Include Template *******************/
function includeEmailTemplate() {
	include_once( 'templates/email.php' );
}