<?php
function reset_bisons_password( $id, $password = false )
{
    // Generate a password if one isn't supplied
    $password = $password ? $password : wp_generate_password ( 8, false, false );
    
    // Update it
    wp_set_password ( $password, $id );
    
    // Get username and add to merge data along with password
    $data = get_userdata ( $id );
    
    $data = array (
        'username' => $data->user_login,
        'password' => $password
    );
    
    // Email the user about the password reset
    send_mandrill_template ( $id, 'password-reset', $data, 'registration' );    
}
