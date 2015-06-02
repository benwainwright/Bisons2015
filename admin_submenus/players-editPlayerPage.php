<?php
/************************* Insert Menu ************************/
function addEditPlayerPageMenu() {
	add_submenu_page ( 'players', 'Pages', 'Pages', 'committee_perms', 'edit.php?post_type=player-page' );
}
add_action('admin_menu','addEditPlayerPageMenu');

