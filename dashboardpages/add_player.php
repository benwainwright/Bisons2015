<?php


$form = new Wordpress_Form ( null, null, 'post', 'Create', 'add_player' );

$form->not_using_fieldsets();
$form->add_inner_tag ( 'div', null, 'custom-form' );
$form->add_inner_tag ( 'table', 'form-table' );
$form->add_inner_tag ( 'tbody' );
$form->set_row_tag ( 'tr' );
$form->set_label_parent_tag ( 'th' );
$form->set_field_parent_tag ( 'td' );
$form->set_forminfo_tag ('span', 'description');
$form->set_submit_button_classes ( array ( 'button', 'button-primary', 'button-large') );;

$form->add_text_input ( null, 'firstname', 'First Name', 'notempty', null, $error ? $_POST['firstname'] : '' );
$form->add_text_input ( null, 'surname', 'Surname', 'notempty', null, $error ? $_POST['surname'] : '' );
$form->add_text_input ( null, 'email', 'Email Address', 'notempty', null, $error ? $_POST['email'] : '' );
$form->add_text_input ( null, 'username', 'Username', null, '<strong>This field is optional</strong>. If you don\'t fill it in the username will be generated automatically.', $error ? $_POST['username'] : '' );
$form->add_password_input ( null, 'password', 'Password', null, '<strong>This field is optional</strong>. If you don\'t fill it in the password will be generated automatically.', $error ? $_POST['password'] : '' );
?>

<div class="wrap"> 
<h2>Add Player</h1>
<p>Please fill in the form below to add a new user. Note that you MUST provide an email address because the player's username and password will be automatically generated and sent out by email.</p>
<?php $form->form_output() ?>
</div>
