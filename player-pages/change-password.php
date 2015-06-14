<h2>Change Your Password</h2>
<p>You can use the form below to change your password. Once this is done, you will be logged out and will need to log back in with your new password.</p>
<?php
wp_enqueue_script('dynamicforms');
wp_enqueue_script('formvalidation');


$cpform = new Wordpress_Form ( 'change_password', false, 'post', 'Save', 'change_password' );
$cpform->add_fieldset( 'change_password', false);
$cpform->add_password_input ( 'change_password', 'oldpass', 'Old password', 'notempty');
$cpform->add_password_input ( 'change_password', 'newpass', 'New password', 'notempty');
$cpform->add_password_input ( 'change_password', 'newpassconfirm', 'Confirm new password', 'notempty');
$cpform->form_output();