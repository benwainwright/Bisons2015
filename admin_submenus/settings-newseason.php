<?php

/************************* Insert Menu ************************/
function addNewSeasonPage() {
	add_management_page (  'New Season', 'New Season', 'committee_perms', 'new-season', 'includeNewSeasonTemplate' );
}
add_action('admin_menu','addNewSeasonPage');

/************************* Include Template *******************/
function includeNewSeasonTemplate() {
	include_once( 'templates/settings-newseason.php' );
}

