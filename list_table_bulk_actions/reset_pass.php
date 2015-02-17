<?php
if (!INCLUDED) exit;
if ( $_POST['confirm_action'] == 'true')
{
    $usercount = 0;
    if ( isset ( $_POST['user_id'] ) )
    {
        $user_ids = ( @unserialize( stripslashes( ( $_POST['user_id']) ) ) !== false )? unserialize( stripslashes( ( $_POST['user_id']) ) ) :  $_POST['user_id'];
        foreach ($user_ids as $id) 
        {
            $usercount++;
        
            // Generate a password if one isn't supplied
            $password = $password ? $password : wp_generate_password(8, false, false);
        
            // Update it
            wp_set_password($password, $id);
        
            // Get username and add to merge data along with password
            $data = get_userdata($id);
        
            $data = array('username' => $data -> user_login, 'password' => $password);
        
            // Email the user about the password reset
            send_mandrill_template($id, 'password-reset', $data, 'registration');
        }
        if ($usercount == 1) 
        {
            function reset_pass_update_single_notice()
            {
                echo '<div class="updated">';
                echo "<p>Password reset successfully. The user will receive an email notification shortly.</p>";
                echo '</div>';
            }
            add_action('admin_notices', 'reset_pass_update_single_notice');
        }
        else if ( $usercount > 1)
        {
            function reset_pass_update_plural_notice()
            {
                echo '<div class="updated">';
                echo "<p>Passwords reset successfully. Users will receive email notifications shortly.</p>";
                echo '</div>';
            }
            add_action('admin_notices', 'reset_pass_update_plural_notice');
        }
    }
    else
    {
        function reset_pass_error_notice() {
            echo '<div class="error">';
            echo '<p>You didn\'t select anyone</p>';
            echo '</div>';
        }
        add_action('admin_notices', 'reset_pass_error_notice');
    }
}

