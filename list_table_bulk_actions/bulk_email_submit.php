<?php

if (!INCLUDED) exit;
$_POST['confirm_action'] = 'true';

$yesCount++;


$email_options = get_option('email-settings-page');

$data = array( 'body' => wpautop ( stripslashes ( $_POST['message_body'] ) ) );

// Send email template
$results = send_mandrill_template($_POST['user_id'], 'generic-email', $data, 'custom', $_POST['message_subject'], $_POST['message_from'] );

// Display update or error notices
if ( $results['count']['sent'] > 0 )
{
    if ( $results['count']['sent'] == 1 )
    {
       function mem_due_email_update_notice_single()
        {
            echo '<div class="updated">';
            echo "<p>Email sent successfully.</p>";
            echo '</div>';
        }
        add_action('admin_notices', 'mem_due_email_update_notice_single');
    }
    else
    {
       function mem_due_email_update_notice_single()
        {
            echo '<div class="updated">';
            echo "<p>Emails sent successfully.</p>";
            echo '</div>';
        }
        add_action('admin_notices', 'mem_due_email_update_notice_single');
    }
}

if ( $results['count']['rejected'] > 0 )
{
    function mem_due_email_error_notice()
    {
        echo '<div class="error">';
        echo '<p>One or more emails were rejected. Check the email log to find out why...</p>';
        echo '</div>';
    }
    add_action('admin_notices', 'mem_due_email_error_notice');
}

