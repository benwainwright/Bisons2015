<?php
function addCommitteePage() {
	add_menu_page ( 'Committee', 'Committee', 'committee_perms', 'edit.php?post_type=committee-profile', false, 'dashicons-businessman', 9);
}
add_action('admin_menu','addCommitteePage');

