<?php


function user_registration_email( $id )
{
    add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
    $userdata = get_userdata ( $id );
    $emailopt = get_option('email-settings-page');
    $to = $userdata->user_email;
    $subject = $emailopt['new-user-email-subject'];
    $content = $emailopt['new-user-email'];
    $username = $userdata->user_login;
    $password = $_POST['pass1'];
    $content = preg_replace("/(.*)@@username@@(.*)/", "$1$username$2", $content);
    $content = preg_replace("/(.*)@@password@@(.*)/", "$1$password$2", $content);
    if ( $emailopt['email-css-ext'] ) { $content = '<link rel="stylesheet" href="'.$emailopt['email-css-ext'].'" type="text/css" />'.$content; }
    if ( $emailopt['email-css'] ) { $content = '<style type="text/css">'.$emailopt['email-css'].'</style>'.$content; }
    $headers = 'From: '.$emailopt['new-user-email-replyto']."\r\n";
    wp_mail( $to, $subject, $content, $headers );
    
}
add_action ( 'user_register', 'user_registration_email' );
