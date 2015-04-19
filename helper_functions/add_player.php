<?php
function add_player($email, $firstname, $surname, $username = false, $password = false)
{
    
    // Generate a password
    $password = $password ? $password : wp_generate_password(8, false, false);

    // Generate a unique username
    $baseuser = strtolower(preg_replace('/[^\w\d]/ui', '', $firstname) . preg_replace('/[^\w\d]/ui', '', $surname));
    $username = $username ? $username : $baseuser;
    for ($i = 1; username_exists($username); $i++)
        $username = $baseuser . $i;

    // Create the user
    $user_id = wp_insert_user(array('user_login' => $username, 'user_pass' => $password, 'user_email' => $email, 'nickname' => $firstname . " " . $surname, 'first_name' => $firstname, 'last_name' => $surname));

    // Assign roles
    $user = new WP_User($user_id);
    $user -> set_role('guest_player');

    // Prepare email data
    $emailopt = get_option('email-settings-page');
    $data = array('username' => $username, 'password' => $password, 'memsecretary' => $emailopt['email-memsec']);

    // Send out Mandrill template via API library
    send_mandrill_template($user_id, 'welcome-email', $data, 'registration');
    $formsubmitted = true;


} 