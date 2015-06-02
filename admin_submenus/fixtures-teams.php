<?php
/************************* Insert Menu ************************/
function addEditTeamsMenu() {
	add_submenu_page ( 'fixturelist', 'Teams', 'Teams', 'committee_perms', 'edit.php?post_type=teams' );
}
add_action('admin_menu','addEditTeamsMenu');




