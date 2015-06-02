<?php
/************************* Insert Menu ************************/
function addPlayerSubmenu() {
	add_submenu_page( 'players', 'Add Player', 'Add Player', 'committee_perms', 'add-player', 'includeAddPlayerTemplate' );
}
add_action('admin_menu','addPlayerSubmenu');

/************************* Include Template *******************/
function includeAddPlayerTemplate() {
	include( 'templates/players-addPlayer.php');
}