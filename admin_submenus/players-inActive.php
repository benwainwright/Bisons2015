<?php
/************************* Insert Menu ************************/
function addInactivePlayersMenu() {
	add_submenu_page ( 'players', 'Inactive', 'Inactive Users', 'committee_perms', 'inactivePlayers', 'includeInactivePlayersTemplate' );

}
add_action('admin_menu','addInactivePlayersMenu');

/************************* Include Template *******************/
function includeInactivePlayersTemplate() {
	include( 'templates/players-inactive.php');
}