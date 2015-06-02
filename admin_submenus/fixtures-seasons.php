<?php
/************************* Insert Menu ************************/
function addEditSeasonsMenu() {
	add_submenu_page ( 'fixturelist', 'Seasons', 'Seasons', 'committee_perms', 'edit-tags.php?taxonomy=seasons&post_type=fixtures' );
}
add_action('admin_menu','addEditSeasonsMenu');





