<?php
/************************* Insert Menu ************************/
function addEditAttendanceRegisterMenu() {
	add_submenu_page ( 'players', 'Registers', 'Registers', 'committee_perms', 'edit.php?post_type=attendance_registers' );
}
add_action('admin_menu','addEditAttendanceRegisterMenu');