<?php
if (!INCLUDED) exit;

if ( sizeof ( $_POST['user_id'] ) > 0 )
{
    echo "<h2>Send Email</h2><p>Emails sent via this form will be sent as HTML email with the Mandrill generic email template.</p>";
    $emails = array();
    $user_ids = array();
    
    
    foreach ($_POST['user_id'] as $id) {
    
        // Reset vars
        $firstName = null;
        $lastName = null;
        $email = null;
        
        $user_ids[] = $id;
        
        $membership_form = new WP_Query( array('post_type' => 'membership_form', 'posts_per_page' => 1, 'orderby' => 'date', 'order' => 'ASC', 'author' => $id ));
        $userInfo = get_userdata($id);
        
        while ($membership_form->have_posts()) 
        {
            $membership_form->the_post();
            $firstName = get_post_meta(get_the_id(), 'firstname', true);
            $lastName = get_post_meta(get_the_id(), 'surname', true);
            $email = get_post_meta(get_the_id(), 'email_addy', true);
        }
        $firstName = $firstName ? $firstName : $userInfo->user_firstname;
        $lastName = $lastName ? $lastName : $userInfo->user_lastname;
        $name = $firstName . ' ' . $lastName;
        $email = $email ? $email : $userInfo->data->user_email;
        $emailstring = "$name &lt;$email&gt;";
        $emails[] = $emailstring;
    }
    
    $emails = implode(', ', $emails);
    $emailform = new Wordpress_Form(null, null, 'post', 'Send', 'emailform');
    $emailform -> not_using_fieldsets();
    $emailform -> add_inner_tag('div');
    $emailform -> add_inner_tag('table', 'form-table');
    $emailform -> add_inner_tag('tbody');
    $emailform -> set_label_parent_tag('th');
    $emailform -> set_row_tag('tr');
    $emailform -> set_field_parent_tag('td');
    $emailform -> add_hidden_field(null, '_wpnonce', $_POST['_wpnonce']);
    foreach ( $_POST['user_id'] as $key => $value )
        $emailform -> add_hidden_field(null, "user_id[$key]", $value);
    
    $emailform -> set_submit_button_classes(array('button', 'button-primary', 'button-large'));
    $emailform -> add_static_text(null, 'recipients', 'Recipient(s)', false, false, $emails);
    $emailform -> add_hidden_field(null, 'email_to', implode(',', $user_ids));
    $emailform -> add_hidden_field(null, 'action', 'bulk_email_submit');
    $emailform -> add_text_input(null, 'message_subject', 'Subject', 'regular-text');
    
    $email_options = get_option('email-settings-page');
    $emailform -> add_text_input(null, 'message_from', 'From', 'regular-text', false, $email_options['new-user-email-replyto-address']);
    $emailform -> add_textarea(null, 'message_body', 'Body', 'large-text');
    $emailform -> form_output();
    }
else 
{
    function download_bulk_error_notice() {
        echo '<div class="error">';
        echo '<p>You didn\'t select anyone</p>';
        echo '</div>';
    }
    add_action('admin_notices', 'download_bulk_error_noticess');
}