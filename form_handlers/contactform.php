<?php
if (!INCLUDED)
    exit ;

global $flashMessage;

$emailopt = get_option('email-settings-page');

$send_email = true;

if (!$_POST['prospective_player_email']) {
    $send_email = false;
    $GLOBALS['bisons_flash_message'] = "You didn't enter an email address...";

}

if (!$_POST['message_body']) {
    $send_email = false;
    $GLOBALS['bisons_flash_message'] = "You didn't enter a message...";
}

session_start();
if ($_SESSION['digit'] != $_POST['captcha']) {
    $send_email = false;
    $GLOBALS['bisons_flash_message'] = "CAPTCHA didn't match...";
}

if ($send_email) {

    // Send email to committee
    switch ( $_POST[ 'query_type' ] ) {
        case 1 :
            $toCommittee = $emailopt['contact-us-email-address-1'];
            break;
        case 2 :
            $toCommittee = $emailopt['contact-us-email-address-2'];
            break;
        case 3 :
            $toCommittee = $emailopt['contact-us-email-address-3'];
            break;
    }

    $subject = $_POST['message_subject'];
    $message = wpautop($_POST['message_body']);
    $email = $_POST['prospective_player_email'];
    $name = $_POST['prospective_player_name'];
    $tosender = array( array('email' => $email, 'name' => $name, 'type' => 'to'));
    $toCommittee = array( array('email' => $toCommittee, 'name' => 'Bristol Bisons RFC', 'type' => 'to'),
                 array('email' => $emailopt['contact-us-email-address-cc'], 'name' => 'Bristol Bisons RFC', 'type' => 'cc'));
                 

    $data = array('name' => stripslashes($name), 'subject' => stripslashes($subject), 'message' => stripslashes($message));
    $results1 = send_mandrill_template($tosender, 'contact-us-copy-to-sender', $data, 'contact');
    $results2 = send_mandrill_template($toCommittee, 'contact-us-copy-to-committee', $data, 'contact', stripslashes($subject), $email, $name);
    
    if ( $results2['count']['sent'] > 0 )
    {
        $GLOBALS['bisons_flash_message'] = "Thanks for your email! A member of the committee will be in touch shortly...";
    }
    else
    {
        $GLOBALS['bisons_flash_message'] = "There was an error! Please send your email to webmaster@bisonsrfc.co.uk letting him know that there was a problem so that he can look into it. He will also pass your message onto the committee cos he's nice like that!";
    }


}
