<?php 
define('INCLUDED', TRUE);
include_once('dBug.php');
add_theme_support( 'post-thumbnails' ); 
set_post_thumbnail_size( 400, 400 );
add_filter('show_admin_bar', '__return_false');
add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));


function style_bisons_editor() { add_editor_style( 'stylesheets/style-editor.css' ); }
add_action( 'after_setup_theme', 'style_bisons_editor' );


// Load official GoCardless library
include_once('GoCardless/init.php');

foreach ( glob( __DIR__ . '/wp-cron/*.php')  as $filename )
{ include_once($filename); }

foreach ( glob( __DIR__ . '/init/*.php')  as $filename )
{ include_once($filename); }

foreach ( glob( __DIR__ . '/helper_functions/*.php')  as $filename )
{ include_once($filename); }

// Get flash message from querystring if there is one
if ( wp_verify_nonce ( $_GET['nonce'], 'bisons_flashmessage_nonce') )
    $GLOBALS['bisons_flash_message'] = stripslashes ( $_GET['flash'] );

// Dependencies
include_once('Mandrill/Mandrill.php');
$mandrill = new Mandrill('ZzbBwttWRHJ41GL4BZmmsQ');


// Classes
include_once('classes/Wordpress_Form.php');
include_once('classes/WP_List_table_copy.php');

// List tables
include_once('listTables/fixtures.php');
include_once('listTables/emails.php');

// Feeds
include_once('feeds/ical-all.php');

// CRON scripts
include_once('cron/cron_init.php');
include_once('init/excerpt.php');

// Email handling
include_once('PHPMailer/PHPMailerAutoload.php');
include_once('email/send_bison_mail.php');


// Custom shortcodes
include_once('shortcodes/feestable.php');
include_once('shortcodes/contactform.php');
include_once('shortcodes/supporterjoin.php');



// My blog settings
include_once('init/settings.php');

// API wrappers which provide the Facebook and Twitter widget feeds
include_once('API_Wrapper/twitter.php');
include_once('API_Wrapper/facebook.php');
include_once('API_Wrapper/flikr.php');

// Custom widgets built into this theme
include_once('widgets/twitter.php');
include_once('widgets/facebook.php');
include_once('widgets/welcometext.php');



// Form handlers
if ( wp_verify_nonce( $_POST['nonce'], 'wordpress_form_submit' ) && file_exists ( __DIR__ . '/form_handlers/' . $_POST['wp_form_id'] . '.php' ) )
    include_once('form_handlers/' . $_POST['wp_form_id']. '.php');

include_once('listTables/players_no_mem_form.php');
if ( wp_verify_nonce ( $_POST['nonce'],  'bulk-'.Players_No_Mem_form::$plural )  && $_POST['action'] != '-1' )
    include_once ('list_table_bulk_actions/' . $_POST['action'] . '.php' );


include_once('listTables/membership_forms.php');
if ( file_exists ( __DIR__ . '/list_table_bulk_actions/' . $_POST['action'] . '.php' ) && wp_verify_nonce ( $_POST['_wpnonce'], 'bulk-'.Membership_Forms_Table::$plural ) && $_POST['action'] != '-1')
    include_once ('list_table_bulk_actions/' . $_POST['action'] . '.php' );
    

// Fix 'insert to post' button not visible bug.
add_filter( 'get_media_item_args', 'force_send' );
function force_send($args){
    $args['send'] = true;
    return $args;
}

