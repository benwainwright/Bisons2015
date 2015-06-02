<?php
/************************* Insert Menu ************************/
function addEditPageGroupsMenu() {
	add_submenu_page ( 'players', 'Page Groups', 'Page Groups', 'committee_perms', 'edit-tags.php?taxonomy=player-page-groups&post_type=player-page' );
}
add_action('admin_menu','addEditPageGroupsMenu');



