<?php


define( 'INCLUDED', true );
include_once( 'dBug.php' );


// Load official GoCardless library
include_once( 'GoCardless/init.php' );

foreach ( glob( __DIR__ . '/functions/*.php' ) as $filename ) {
	include_once( $filename );
}

foreach ( glob( __DIR__ . '/classes/*.php' ) as $filename ) {
	include_once( $filename );
}

foreach ( glob( __DIR__ . '/listTables/*.php' ) as $filename ) {
	include_once( $filename );
}

add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 400, 400 );
add_filter( 'show_admin_bar', '__return_false' );
add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html"; ' ) );


function style_bisons_editor() {
	$font_url = str_replace( ',', '%2C', '//fonts.googleapis.com/css?family=Ubuntu:300,400,300italic,400italic:latin' );
	add_editor_style( $font_url );
	add_editor_style( 'stylesheets/style-editor.css' );
}

add_action( 'after_setup_theme', 'style_bisons_editor' );


include_once( __DIR__ . '/wp-cron/activateSchedules.php' );


foreach ( glob( __DIR__ . '/init/*.php' ) as $filename ) {
	include_once( $filename );
}

if ( is_admin() ) {

	include_once( __DIR__ . '/settings_api/fieldCallbacks.php' );

	foreach ( glob( __DIR__ . '/settings_api/pages/*.php' ) as $filename ) {
		include_once( $filename );
	}

	foreach ( glob( __DIR__ . '/admin_menus/*.php' ) as $filename ) {
		include_once( $filename );
	}

	foreach ( glob( __DIR__ . '/admin_submenus/*.php' ) as $filename ) {
		include_once( $filename );
	}
}

foreach ( glob( __DIR__ . '/wp-cron/schedules/*.php' ) as $filename ) {
	include_once( $filename );
}

foreach ( glob( __DIR__ . '/wp-cron/actions/*.php' ) as $filename ) {
	include_once( $filename );
}


// Get flash message from querystring if there is one
if ( isset ( $_GET['nonce'] ) ) {
	if ( wp_verify_nonce( $_GET['nonce'], 'bisons_flashmessage_nonce' ) ) {
		$GLOBALS['bisons_flash_message'] = stripslashes( $_GET['flash'] );
	}
}

// Dependencies
include_once( 'Mandrill/Mandrill.php' );
$mandrill = new Mandrill( 'ZzbBwttWRHJ41GL4BZmmsQ' );


// Feeds
include_once( 'feeds/ical-all.php' );

include_once( 'init/excerpt.php' );

// Email handling
include_once( 'PHPMailer/PHPMailerAutoload.php' );
include_once( 'email/send_bison_mail.php' );


// Custom shortcodes
include_once( 'shortcodes/feestable.php' );
include_once( 'shortcodes/contactform.php' );
include_once( 'shortcodes/supporterjoin.php' );


// My blog settings
include_once( 'init/settings.php' );

// API wrappers which provide the Facebook and Twitter widget feeds
include_once( 'API_Wrapper/twitter.php' );
include_once( 'API_Wrapper/facebook.php' );
include_once( 'API_Wrapper/flikr.php' );

// Custom widgets built into this theme
include_once( 'widgets/nextmatch.php' );
include_once( 'widgets/twitter.php' );
include_once( 'widgets/facebook.php' );
include_once( 'widgets/welcometext.php' );


// Form handlers

if ( isset ( $_POST['nonce'] ) ) {
	if ( wp_verify_nonce( $_POST['nonce'],
			'wordpress_form_submit' ) && file_exists( __DIR__ . '/form_handlers/' . $_POST['wp_form_id'] . '.php' )
	) {
		include_once( 'form_handlers/' . $_POST['wp_form_id'] . '.php' );
	}
}


include_once( 'listTables/membership_forms.php' );


if ( isset ( $_POST['action'] ) ) {

	if ( strpos( $_POST['action'], 'set_season_' ) !== false ) {

		$season = explode( 'set_season_', $_POST['action'] )[1];
		include_once( 'list_table_bulk_actions/set_season_as.php' );

	} elseif ( file_exists( __DIR__ . '/list_table_bulk_actions/' . $_POST['action'] . '.php' ) && $_POST['action'] != '-1' ) {
		include_once( 'list_table_bulk_actions/' . $_POST['action'] . '.php' );
	}

} else if ( isset ( $_POST['action2'] ) ) {
	if ( strpos( $_POST['action2'], 'set_season_' ) !== false ) {

		$season = explode( 'set_season_', $_POST['action2'] )[1];
		include_once( 'list_table_bulk_actions/set_season_as.php' );

	} elseif ( file_exists( __DIR__ . '/list_table_bulk_actions/' . $_POST['action2'] . '.php' ) && $_POST['action2'] != '-1' ) {
		include_once( 'list_table_bulk_actions/' . $_POST['action2'] . '.php' );
	}

}


// Fix 'insert to post' button not visible bug.
add_filter( 'get_media_item_args', 'force_send' );
function force_send( $args ) {
	$args['send'] = true;

	return $args;
}

if ( current_user_can( 'bisons_debug' ) && isset ( $_GET['debug'] ) ) {

	switch ( $_GET['debug'] ) {

		case "userMeta":
			new dBug( get_user_meta( get_current_user_id() ) );
			exit;
			break;
	}

}
