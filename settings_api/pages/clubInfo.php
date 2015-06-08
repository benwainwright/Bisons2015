<?php

function createClubInfoSubmenu()
{
	add_menu_page(
		'Club Information',
		'Club Information',
		'manage_options',
		'club-info-settings',
		'printClubInfoPage',
		'dashicons-info'
	);
}
add_action ( 'admin_menu', 'createClubInfoSubmenu' );


function printClubInfoPage ( )
{
	echo '<div class="wrap">'.
	     '<h2>Club Information</h2>'.
	     '<form method="post" action="options.php">';

	settings_fields( 'club-info-settings-page' );
	do_settings_sections( 'club-info-settings-page' );
	submit_button();

	echo '</div>';
}

function printClubInfoSection ( )
{
	echo "<p>Use the fields below to fill out the content that will appear on the 'Club Information' page.</p>";
}

function initClubInfoSettings()
{
	if( false == get_option( 'club-info-settings-page' ) )
	{
		$options = array (
			'welcome-text' => '',
			'welcome-title' => '',
			'home-address' => ''
		);
		add_option( 'club-info-settings-page', $options);
	}

	add_settings_section( 'club-information-content', 'Content', 'printClubInfoSection', 'club-info-settings-page' );

	$args = array( 'welcome-title', 'club-info-settings-page' );
	add_settings_field( 'welcome-title', 'Welcome Title', 'singleline_input_field', 'club-info-settings-page', 'club-information-content', $args );

	$args = array( 'welcome-text', 'club-info-settings-page' );
	add_settings_field( 'welcome-text', 'Welcome Text', 'tinymce', 'club-info-settings-page', 'club-information-content', $args );

	$args = array ( 'home-address', 'club-info-settings-page' );
	add_settings_field( 'home-address', 'Home venue address?', 'textarea_field_small', 'club-info-settings-page', 'club-information-content', $args);

	register_setting ( 'club-info-settings-page', 'club-info-settings-page' );

}
add_action ( 'admin_init', 'initClubInfoSettings' );