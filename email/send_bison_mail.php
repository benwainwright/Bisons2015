<?php
function send_bison_mail($user, $subject, $content, $tags = false, $emailaddy = false) {

    // Get email to send to from Wordpress
    $info = get_userdata($user);
    $email = $emailaddy ? $emailaddy : $info -> user_email;
    $firstname = $info -> user_firstname;
    $lastname = $info -> user_lastname;

    $emailopt = get_option('email-settings-page');

    $mandrill = new Mandrill('ZzbBwttWRHJ41GL4BZmmsQ');

    // Add CSS to body then attach to email
    if ($emailopt['email-css']) { $content = '<style type="text/css">' . $emailopt['email-css'] . '</style>' . $content;
    }
    if ($emailopt['email-css-ext']) { $content = '<link rel="stylesheet" href="' . $emailopt['email-css-ext'] . '" type="text/css" />' . $content;
    }

    $to = array('email' => $email, 'type' => 'to');

    if ($user)
        $to['name'] = "$firstname $lastname";

    $message = array('html' => $content, 'subject' => $subject, 'from_email' => $emailopt['new-user-email-replyto-address'], 'from_name' => $emailopt['new-user-email-replyto-name'], 'to' => array($to), 'headers' => array('Reply-To' => $emailopt['new-user-email-replyto-name']), 'important' => false, 'track_opens' => true, 'track_clicks' => true, 'auto_text' => false, 'auto_html' => false, 'inline_css' => true, 'url_strip_qs' => false, 'preserve_recipients' => false, 'view_content_link' => null, 'bcc_address' => 'message.bcc_address@example.com', 'tracking_domain' => 'bisonsrfc.co.uk', );

    if ($tags && is_array($tags)) {
        $message['tags'] = $tags;
    } else if ($tags) {
        $message['tags'] = array($tags);
    }

    $async = false;
    $result = $mandrill -> messages -> send($message, $async);
}

function send_mandrill_template($user, $template, $data, $tags = false, $subject = false, $replyemail = false, $replyname = false) {

    // Get user information from Wordpress
    $to = array();

    // If a string has been sent, turn it into an array
    if (!is_array($user)) {
        $user = array($user);
    }

    // If the first item in the $user array is an array itself, assume a direct address is being sent
    if ( is_array ($user[0])) {
        foreach ( $user as $address)
        {
            $to[] = array( 'name' => $address['name'], 'email' => $address['email'], 'type' => $address['type']);
        }
    } else {
        foreach ($user as $id) {
            $info = get_userdata($id);
            $email = $info -> user_email;
            $firstname = $info -> user_firstname;
            $lastname = $info -> user_lastname;
            $to[] = array('name' => "$firstname $lastname", 'email' => $email, 'type' => 'to');
        }
    }

    // Get email options
    $emailopt = get_option('email-settings-page');

    // Initialise Mandrill
    $mandrill = new Mandrill('ZzbBwttWRHJ41GL4BZmmsQ');

    // Prepare merge variables
    $merge_vars = array();
    foreach ($data as $key => $value) {
        $merge_vars[] = array('name' => $key, 'content' => $value);
    }

    // Prepare message settings
    $message = array('from_email' => $replyemail ? $replyemail : $emailopt['new-user-email-replyto-address'], 'from_name' => $emailopt['new-user-email-replyto-name'], 'to' => $to, 'headers' => array('Reply-To' => $emailopt['new-user-email-replyto-name']), 'important' => false, 'track_opens' => true, 'track_clicks' => true, 'inline_css' => true, 'url_strip_qs' => false, 'preserve_recipients' => false, 'view_content_link' => true, 'tracking_domain' => 'bisonsrfc.co.uk', 'global_merge_vars' => $merge_vars, 'tags' => $tags);

    
    // Convert strings to arrays
    $user = is_array($user) ? $user : array($user);
    $tags = is_array($tags) ? $tags : array($tags);

    // Add optional settings
    if ($subject)
        $message['subject'] = $subject;

    if ($tags)
        $message['tags'] = $tags;

    $async = false;

    try {
        // Submit request
        $results = $mandrill -> messages -> sendTemplate($template, $template_content, $message, $async);
    } catch ( Mandrill_Error $e ) {
        echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e -> getMessage();
        throw $e;
    }

    // Count result types, add them to the results array.
    // Also log the email and status
    $sent = 0;
    $rejected = 0;
    $invalid = 0;

    foreach ($results as $result) {
        switch ( $result['status'] ) {
            case 'sent' :
                $sent++;
                break;
            case 'rejected' :
                $rejected++;
                break;
            case 'invalid' :
                $invalid++;
                break;
        }

        $user = get_user_by('email', $result['email']);
        $id = $user -> ID;

        $post_id = wp_insert_post(array('post_status' => 'publish', 'post_type' => 'email_log'));

        update_post_meta($post_id, 'user_id', $id);
        update_post_meta($post_id, 'user_name',  get_userdata($id) -> first_name . ' ' . get_userdata($id) -> last_name);
        update_post_meta($post_id, 'email', $result['email']);
        update_post_meta($post_id, 'email_id', $result['_id']);
        update_post_meta($post_id, 'status', $result['status']);
        update_post_meta($post_id, 'reject_reason', $result['reject_reason']);
        update_post_meta($post_id, 'template', $template);
        update_post_meta($post_id, 'merge_data', $data);

    }

    $result = array('count' => array('sent' => $sent, 'rejected' => $rejected, 'invalid' => $invalid), 'results' => $results);

    return $result;
}
