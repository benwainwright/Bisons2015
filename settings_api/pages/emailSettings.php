<?php

function create_email_settings_submenu_page( )
{
	add_submenu_page(
		'options-general.php',
		'Email Settings',
		'Email',
		'manage_options',
		'email-settings',
		'email_settings_menu_callback'
	);
}
add_action ( 'admin_menu', 'create_email_settings_submenu_page' );

function email_settings_menu_callback ( )
{
	echo '<div class="wrap">'.
	     '<h2>Email</h2>'.
	     '<form method="post" action="options.php">';

	settings_fields( 'email-settings-page' );
	do_settings_sections( 'email-settings-page' );
	submit_button();

	echo '</div>';
}



function email_settings_contact_us_callback ( )
{ echo "<p>Use this section to customise the emails sent out by the 'contact us' form on the 'about us' page.</p>"; }

function email_settings_new_user_callback ()
{ echo "<p>When a new user is registered, an email is automatically sent out. Use this section to customise it.</p>"; }

function email_settings_general_callback ()
{ echo "<p>These settings apply to all HTML emails sent out by this website.</p>"; }

function email_settings_member_info_callback()
{ echo "<p>These emails go out whenever membership information is added or updated. Use this form to decide who receives these emails and what format they take."; }

function email_settings_guest_nag_callback()
{ echo "<p>Users with the 'guest player' role are sent a an automatic reminder email when their membership is due, and then a followup email at regular emails. Use this section to modify the emails which are sent and the intervals at which they are sent. Note that these emails go out to the relevent players at 7pm every day.</p>"; }


function initialize_email_settings( )
{
	if( false == get_option( 'email-settings-page' ) )
	{
		$options = array (
			'contact-us-email' => '',
			'contact-us-template' => '',
			'contact-us-copy-template' => '',
			'new-user-email' => '',
			'new-user-email-replyto' => '',
			'new-user-email-subject' => '',
		);
		add_option( 'email-settings-page', $options);
	}

	add_settings_section( 'email-settings-general', 'General settings', 'email_settings_general_callback', 'email-settings-page' );

	$args = array( 'email-memsec', 'email-settings-page', 'This name can be used in any of the email templates on this page by using the tag <strong>@@membershipsecretary@@</strong>' );
	add_settings_field( 'email-memsec', 'Membership Secretary Name', 'singleline_input_field', 'email-settings-page', 'email-settings-general', $args);

	$args = array( 'new-user-email-replyto-name', 'email-settings-page', 'Automatic emails sent by this site will appear to have this name in the "from" field.' );
	add_settings_field( 'new-user-email-replyto-name', '"Reply to" name', 'singleline_input_field', 'email-settings-page', 'email-settings-general', $args);

	$args = array( 'new-user-email-replyto-address', 'email-settings-page', 'Automatic emails sent by this site will appear to have come from this address.' );
	add_settings_field( 'new-user-email-replyto-address', '"Reply to" address', 'singleline_input_field', 'email-settings-page', 'email-settings-general', $args);



	add_settings_section( 'email-settings-contactus', '"Contact us" emails', 'email_settings_contact_us_callback', 'email-settings-page' );

	$args = array( 'contact-us-email-query-type-1', 'email-settings-page' );
	add_settings_field( 'contact-us-email-query-type-1', 'Query Type 1', 'singleline_input_field', 'email-settings-page', 'email-settings-contactus', $args );

	$args = array( 'contact-us-email-address-1', 'email-settings-page' );
	add_settings_field( 'contact-us-email-address-1', 'Query Type 1 Address', 'singleline_input_field', 'email-settings-page', 'email-settings-contactus', $args);

	$args = array( 'contact-us-email-query-type-2', 'email-settings-page' );
	add_settings_field( 'contact-us-email-query-type-2', 'Query Type 2', 'singleline_input_field', 'email-settings-page', 'email-settings-contactus', $args);

	$args = array( 'contact-us-email-address-2', 'email-settings-page' );
	add_settings_field( 'contact-us-email-address-2', 'Query Type 2 Address', 'singleline_input_field', 'email-settings-page', 'email-settings-contactus', $args);

	$args = array( 'contact-us-email-query-type-3', 'email-settings-page' );
	add_settings_field( 'contact-us-email-query-type-3', 'Query Type 3', 'singleline_input_field', 'email-settings-page', 'email-settings-contactus', $args);

	$args = array( 'contact-us-email-address-3', 'email-settings-page' );
	add_settings_field( 'contact-us-email-address-3', 'Query Type 3 Address', 'singleline_input_field', 'email-settings-page', 'email-settings-contactus', $args);

	$args = array( 'contact-us-email-address-cc', 'email-settings-page' );
	add_settings_field( 'contact-us-email-address-cc', 'CC All To', 'singleline_input_field', 'email-settings-page', 'email-settings-contactus', $args);


	add_settings_section( 'email-settings-newuser', 'New user emails', 'email_settings_new_user_callback','email-settings-page' );

	add_settings_section( 'member-email-settings-section', 'Member information emails', 'email_settings_member_info_callback', 'email-settings-page' );

	$args = array( 'member-email-send-to-email', 'email-settings-page' );
	add_settings_field( 'member-email-send-to-email', 'Send Member Updates to', 'singleline_input_field', 'email-settings-page', 'member-email-settings-section', $args );

	register_setting( 'email-settings-page', 'email-settings-page' );
}
add_action ( 'admin_init', 'initialize_email_settings' );