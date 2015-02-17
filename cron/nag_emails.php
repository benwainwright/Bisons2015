#!/usr/bin/php
<?php

// This file is designed to be run once a day by CRON in order to send out automatic emails
include_once( dirname( __FILE__ ) . '/../init/settings.php' );
include_once( dirname( __FILE__ ) . '/../../../../wp-load.php');
include_once( dirname( __FILE__ ) . '/../dBug.php');

$guest_players = get_users (array( 'role'  =>  'guest_player' ) );

foreach ( $guest_players as $player ){
     
    // Calculate how many days ago the user was registered
    $date = explode(" ", $player->data->user_registered);
    $dmy = explode("-", $date[0]);
    $gap = explode ('.', ( time() - mktime( 0, 0, 1, $dmy[1], $dmy[2], $dmy[0]) ) / 86400 );
    $gap = $gap[0];
    
    $email_options = get_option('email-settings-page');
    $initial_interval = $email_options['guest-nag-email-initial-interval'];
    $later_interval = $email_options['guest-nag-email-later-interval'];
    
    $memsec = $email_options['email-memsec'];
     
    echo "Name: ".$player->data->display_name."\nGap: $gap\n\nI1: $initial_interval\n\nI2: $later_interval\n\n";
    
    // If the gap is equal to the first interval
    if ( $gap == $initial_interval || isset ( $_GET['debug_initial'] ) )
    
    {
       $subject = $email_options['guest-nag-email-subject'];
       $content = wpautop ( $email_options['guest-nag-email-content'] ) ;
       $content = preg_replace("/(.*)@@membershipsecretary@@(.*)/", "$1$memsec$2", $content);
       $content = preg_replace("/(.*)@@siteurl@@(.*)/", "$1".site_url()."$2", $content);
       $content = do_shortcodes ( $content );
       send_bison_mail( $player->data->ID, $subject, $content );
    } 
    
    // If it is greater than the first interval, not zero and a divisor of the later interval
    else if ( ( ! ( $gap <= $initial_interval ) && ! fmod ( ($gap - $initial_interval), $later_interval ) && $gap != 0) || isset ( $_GET['debug_initial'] ) ) 
    {
       $subject = $email_options['guest-nag-email-later-subject'];
       $content = wpautop ( $email_options['guest-nag-email-later-content'] ) ;
       $content = preg_replace("/(.*)@@membershipsecretary@@(.*)/", "$1$memsec$2", $content);       
       $content = preg_replace("/(.*)@@siteurl@@(.*)/", "$1".site_url()."$2", $content); 
       $content = do_shortcodes ( $content );
       send_bison_mail( $player->data->ID, $subject, $content );
    }
}