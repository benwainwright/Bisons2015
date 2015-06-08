<?php
function custom_menu_order($menu_ord) {
	if (!$menu_ord) return true;
	return array(
		'index.php',
		'separator1',
		'club-info-settings', // Club Information
		'edit.php', // Posts
		'fixturelist',
		'edit.php?post_type=events',
		'edit.php?post_type=photos',
		'players',
		'edit.php?post_type=committee-profile',
		'payment',
		'email',

		'edit.php?post_type=page', // Pages
        'upload.php', // Media
        'link-manager.php', // Links
        'edit-comments.php', // Comments
        'separator2', // Second separator
        'themes.php', // Appearance
        'plugins.php', // Plugins
        'users.php', // Users
        'tools.php', // Tools
        'options-general.php', // Settings
        'separator-last', // Last separator

		);
}

add_filter('custom_menu_order', 'custom_menu_order');
add_filter('menu_order', 'custom_menu_order');