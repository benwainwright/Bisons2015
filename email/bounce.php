#!/usr/bin/php -q
<?php
 
// Includes
include_once( dirname( __FILE__ ) . '/../../../../wp-load.php');
include_once('../bouncehandler/bounce_driver.class.php');

// read from stdin
$fd = fopen("php://stdin", "r");
$email = "";
while (!feof($fd))
{
    $email .= fread($fd, 1024);
}
fclose($fd);

// Parse with bounce_driver
$bouncehandler = new Bouncehandler();
$email = $bouncehandler->parse_email($email);
 
// If email is a bounce, add to email log
if ( $email[0]['action'] == 'failed' || $email[0]['action'] == 'transient' )
{
    $email_log = array(
        'post_content' => '',
        'post_status' => 'publish',
        'post_date' => date('Y-m-d H:i:s'),
        'post_author' => 1,
        'post_type' => 'email_log'
    );
    
    $id = wp_insert_post( $email_log );
    
    update_post_meta($id, 'user_id', get_user_by('email', $email[0]['recipient']) );
    update_post_meta($id, 'type', 'bounce');
}


