<?php
/************************* Insert Menu *******************/
function addFixturesMenu() {
	$fixtures_menu_hook = add_menu_page ( 'Fixtures', 'Fixtures', 'committee_perms', 'fixturelist', 'includeFixturesTemplate', 'dashicons-flag');
	add_action( "load-$fixtures_menu_hook", 'Fixtures_Table_Add_Options' );
}
add_action('admin_menu', 'addFixturesMenu');

/****************** Add Screen Options *******************/
function Fixtures_Table_Add_Options() {
	$option = 'per_page';
	$args = array(
		'label' => 'Fixtures',
		'default' => 10,
		'option' => 'fixtures_per_page'
	);
	add_screen_option( $option, $args );
}
add_filter('set-screen-option', 'Fixtures_Table_Add_Options', 10, 3);


/****************** Set Screen Options *******************/
function Fixtures_Table_Set_Options($status, $option, $value)
{
	if ( 'fixtures_per_page' == $option ) return $value;
	return $status;
}

/************************* Include Template *******************/
function includeFixturesTemplate() {
	include_once( 'templates/fixtures.php' );
}

