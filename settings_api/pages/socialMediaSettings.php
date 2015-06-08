<?php
function createSocialMediaSettingsMenu( )
{
	add_submenu_page(
		'options-general.php',
		'Social Media',
		'Social Media',
		'manage_options',
		'social-media-settings',
		'printSocialMediaSettingsPage'
	);
}
add_action ( 'admin_menu', 'createSocialMediaSettingsMenu' );

function printSocialMediaSettingsPage ( )
{
	echo '<div class="wrap">'.
	     '<h2>Social Media</h2>'.
	     '<form method="post" action="options.php">';

	settings_fields( 'social-media-settings-page' );
	do_settings_sections( 'social-media-settings-page' );
	submit_button();

	echo '</div>';
}


function printSocialMediaSettingsSection ()
{
}

function initializeSocialMediaSettings() {
	if ( false === get_option( 'social-media-settings-page' ) ) {
		$options = array(
			'twitter-screenname' => '',
			'facebook-page'      => '',
			'flickr-username'    => ''
		);
		add_option( 'social-media-settings-page', $options );
	}

	add_settings_section( 'social-media-settings-accounts', 'Accounts', 'printSocialMediaSettingsSection',
		'social-media-settings-page' );

	$args = array ( 'twitter-screenname', 'social-media-settings-page', 'This setting determines what Twitter feed is displayed on the built in theme Twitter widget' );
	add_settings_field( 'twitter-screenname', 'Twitter Screenname', 'singleline_input_field', 'social-media-settings-page', 'social-media-settings-accounts', $args );

	$args = array ( 'facebook-page', 'social-media-settings-page', 'This setting determines what Facebook page is displayed on the built in theme Facebook widget' );
	add_settings_field( 'facebook-page', 'Facebook Page', 'singleline_input_field', 'social-media-settings-page', 'social-media-settings-accounts', $args);

	array ( 'flickr-username', 'social-media-settings-page', 'Any public photosets belonging to this Flickr user will be avaialable on the "photos" page of this website.' );
	add_settings_field( 'flickr-username', 'Flickr Username', 'singleline_input_field', 'social-media-settings-page', 'social-media-settings-accounts', $args);

	register_setting ( 'social-media-settings-page', 'social-media-settings-page' );

}
add_action ( 'admin_init', 'initializeSocialMediaSettings' );
