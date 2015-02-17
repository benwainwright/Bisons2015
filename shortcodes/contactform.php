<?php
function contact_form_shortcode() {
    wp_enqueue_script('formscripts');

    $emailopt = get_option('email-settings-page');

    $contactform = new Wordpress_Form(null, null, 'post', 'Submit', 'contactform');
    $contactform -> add_fieldset('contact', 'Contact Us');

    $types = array();

    if ($emailopt['contact-us-email-query-type-1'] && $emailopt['contact-us-email-address-1'])
        $types[1] = $emailopt['contact-us-email-query-type-1'];
    if ($emailopt['contact-us-email-query-type-2'] && $emailopt['contact-us-email-address-2'])
        $types[2] = $emailopt['contact-us-email-query-type-2'];
    if ($emailopt['contact-us-email-query-type-3'] && $emailopt['contact-us-email-address-3'])
        $types[3] = $emailopt['contact-us-email-query-type-3'];

    if (sizeof($types))
        $contactform -> add_list_box('contact', 'query_type', 'What is your question about?', $types);

    $contactform -> add_text_input('contact', 'prospective_player_name', 'Name');
    $contactform -> add_text_input('contact', 'prospective_player_email', 'Email');
    $contactform -> add_text_input('contact', 'message_subject', 'Subject');
    $contactform -> add_textarea('contact', 'message_body', 'Body');
    $contactform -> add_captcha('contact', 'captcha', 'CAPTCHA', get_bloginfo('template_url') . '/captcha.php', 'captcha', 'Enter the numbers from blue image into this box to prove you are a human.');
    return $contactform -> form_output(false);
}

add_shortcode('contactform', 'contact_form_shortcode');
