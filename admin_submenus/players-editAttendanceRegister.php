<?php
/************************* Insert Menu ************************/
function addEditAttendanceRegisterMenu() {
	add_submenu_page ( 'players', 'Registers', 'Registers', 'committee_perms', 'registers', 'includeRegisterTemplate' );
}
add_action('admin_menu','addEditAttendanceRegisterMenu');

/************************* Include Template *******************/
function includeRegisterTemplate() {
	include( 'templates/players-register.php');
}