<?php
/************************* Insert Menu ************************/
function addEditPlayerProfilesMenu() {
	add_submenu_page ( 'players', 'Profiles', 'Profiles', 'committee_perms', 'edit.php?post_type=playerprofiles' );
}
add_action('admin_menu','addEditPlayerProfilesMenu');