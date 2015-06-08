<?php

function createAPISettingsMenu ( )
{

	add_submenu_page(
		'options-general.php',
		'API Settings',
		'APIs',
		'manage_options',
		'api-settings',
		'printAPISettingsPage'
	);
}

add_action ( 'admin_menu', 'createAPISettingsMenu' );

function printAPISettingsPage( )
{
	echo '<div class="wrap">'.
	     '<h2>API Settings</h2>'.
	     '<form method="post" action="options.php">';

	settings_fields( 'api-settings-page' );
	do_settings_sections( 'api-settings-page' );
	submit_button();

	echo '</div>';
}

function analytics_settings_callback ()
{
	echo "<p>This theme is already configured to work with Google Analytics. All you need to do is put the ID into the box below.</p>";
}

function gocardless_sandbox_settings_callback ()
{
	echo "<p>These API keys are for the GoCardless sandbox.</p>";
}

function gocardless_production_settings_callback ()
{
	echo "<p>These API keys are for the GoCardless PRODUCTION mode.</p>";
}


function gocardless_environment_settings_callback ()
{
	echo "<p>Use the dropdown box below to switch between GoCardless PRODUCTION and SANDBOX modes.</p>";
}

function mandrill_settings_callback()
{

}

function initialise_other_settings()
{

	if( false == get_option( 'api-settings-page' ) )
	{
		$options = array (
			'analytics-id' => '',
			'gcl-sandbox-app-id' => '',
			'gcl-sandbox-app-secret' => '',
			'gcl-sandbox-merchant-id' => '',
			'gcl-sandbox-access-token' => '',
			'gcl-prod-app-id' => '',
			'gcl-prod-app-secret' => '',
			'gcl-prod-merchant-id' => '',
			'gcl-prod-access-token' => '',
			'gcl-environment' => 'Sandbox',
			'mandrill-webhook-key'  => ''

		);
		add_option( 'api-settings-page', $options);
	}

	add_settings_section( 'other-settings-analytics', 'Analytics Settings', 'analytics_settings_callback', 'api-settings-page' );

	$args = array( 'analytics-id', 'api-settings-page' );
	add_settings_field( 'analytics-id', 'Google Analytics ID', 'singleline_input_field', 'api-settings-page', 'other-settings-analytics', $args);

	$args = array( 'gcl-environment', 'api-settings-page', false, array ('Production', 'Sandbox'));
	add_settings_section( 'other-settings-gocardless-environment', 'GoCardless Environment', 'gocardless_environment_settings_callback', 'api-settings-page' );add_settings_field( 'gcl-environment', 'Environment', 'dropdown', 'api-settings-page', 'other-settings-gocardless-environment', $args);



	add_settings_section( 'other-settings-gocardless-sandbox', 'GoCardless Settings (Sandbox)', 'gocardless_sandbox_settings_callback', 'api-settings-page' );

	$args = array( 'gcl-sandbox-app-id', 'api-settings-page');
	add_settings_field( 'gcl-sandbox-app-id', 'App ID', 'singleline_input_field', 'api-settings-page', 'other-settings-gocardless-sandbox', $args );

	$args = array( 'gcl-sandbox-app-secret', 'api-settings-page');
	add_settings_field( 'gcl-sandbox-app-secret', 'App Secret', 'singleline_input_field', 'api-settings-page', 'other-settings-gocardless-sandbox', $args );

	$args = array( 'gcl-sandbox-merchant-id', 'api-settings-page');
	add_settings_field( 'gcl-sandbox-merchant-id', 'Merchant ID', 'singleline_input_field', 'api-settings-page', 'other-settings-gocardless-sandbox', $args );

	$args = array( 'gcl-sandbox-access-token', 'api-settings-page');
	add_settings_field( 'gcl-sandbox-access-token', 'Access Token', 'singleline_input_field', 'api-settings-page', 'other-settings-gocardless-sandbox', $args );



	add_settings_section( 'other-settings-gocardless-prod', 'GoCardless Settings (Production)', 'gocardless_production_settings_callback', 'api-settings-page' );

	$args = array( 'gcl-prod-app-id', 'api-settings-page');
	add_settings_field( 'gcl-prod-app-id', 'App ID', 'singleline_input_field', 'api-settings-page', 'other-settings-gocardless-prod', $args);

	$args = array( 'gcl-prod-app-secret', 'api-settings-page');
	add_settings_field( 'gcl-prod-app-secret', 'App Secret', 'singleline_input_field', 'api-settings-page', 'other-settings-gocardless-prod', $args );

	$args = array( 'gcl-prod-merchant-id', 'api-settings-page');
	add_settings_field( 'gcl-prod-merchant-id', 'Merchant ID', 'singleline_input_field', 'api-settings-page', 'other-settings-gocardless-prod', $args );

	$args = array( 'gcl-prod-access-token', 'api-settings-page');
	add_settings_field( 'gcl-prod-access-token', 'Access Token', 'singleline_input_field', 'api-settings-page', 'other-settings-gocardless-prod', $args );



	add_settings_section( 'other-settings-mandrill', 'Mandrill Settings', 'mandrill_settings_callback', 'api-settings-page' );

	$args = array( 'mandrill-settings-api-key', 'api-settings-page');
	add_settings_field( 'mandrill-settings-api-key', 'API Key', 'singleline_input_field', 'api-settings-page', 'other-settings-mandrill', $args );

	$args = array( 'mandrill-settings-webhook-key', 'api-settings-page');
	add_settings_field( 'mandrill-settings-webhook-key', 'Webhook Key', 'singleline_input_field', 'api-settings-page', 'other-settings-mandrill', $args );

	register_setting( 'api-settings-page', 'api-settings-page' );
}
add_action ( 'admin_init', 'initialise_other_settings' );
