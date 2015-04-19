<?php
if (!INCLUDED) exit;

global $admin_notice_errors;

if ($_POST['email'] == '')
    $admin_notice_errors[] = "Please enter an email address...";
if ($_POST['surname'] == '')
    $admin_notice_errors[] = "Please enter a surname...";
if ($_POST['firstname'] == '')
    $admin_notice_errors[] = "Please enter a first name...";

if (email_exists($_POST['email']))
    $admin_notice_errors[] = "That email address already exists on our database. Try another one...";
if ($_POST['username'] != '' && username_exists($_POST['username']))
    $admin_notice_errors[] = "That username already exists on our database. Try another one...";

if ( ! sizeof ( $admin_notice_errors ) ) {
    
	add_player( $_POST['email'], $_POST['firstname'], $_POST['surname'], $_POST['username'], $_POST['password']);

    function add_player_update_notice() 
    {
        echo '<div class="updated">';
        echo "<p>Player added! They will receive an email shortly.</p>";
        echo '</div>';
    }

    add_action('admin_notices', 'add_player_update_notice');

} else {
    function add_player_error_notice() 
    {
        global $admin_notice_errors;
        echo '<div class="error">';
        foreach ($admin_notice_errors as $error)
        {
            echo "<p>$error</p>";
        
        }
        echo '</div>';
    }

    add_action('admin_notices', 'add_player_error_notice');
}
