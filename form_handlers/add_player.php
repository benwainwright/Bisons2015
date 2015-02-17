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
    
    // Generate a password
    $password = $_POST['password'] ? $_POST['password'] : wp_generate_password(8, false, false);

    // Generate a unique username
    $baseuser = strtolower(preg_replace('/[^\w\d]/ui', '', $_POST['firstname']) . preg_replace('/[^\w\d]/ui', '', $_POST['surname']));
    $username = $baseuser;
    for ($i = 1; username_exists($username); $i++)
        $username = $baseuser . $i;

    // Create the user
    $user_id = wp_insert_user(array('user_login' => $username, 'user_pass' => $password, 'user_email' => $_POST['email'], 'nickname' => $_POST['firstname'] . " " . $_POST['surname'], 'first_name' => $_POST['firstname'], 'last_name' => $_POST['surname']));

    // Assign roles
    $user = new WP_User($user_id);
    $user -> set_role('guest_player');

    // Prepare email data
    $emailopt = get_option('email-settings-page');
    $data = array('username' => $username, 'password' => $password, 'memsecretary' => $emailopt['email-memsec']);

    // Send out Mandrill template via API library
    send_mandrill_template($user_id, 'welcome-email', $data, 'registration');
    $formsubmitted = true;

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
