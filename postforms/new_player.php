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

$form->add_text_input ( null, 'firstname', 'First Name', 'notempty', null, '' );
$form->add_text_input ( null, 'surname', 'Surname', 'notempty', null, '' );
$form->add_text_input ( null, 'email', 'Email Address', 'notempty', null, '' );
$form->add_text_input ( null, 'username', 'Username', null, '<strong>This field is optional</strong>. If you don\'t fill it in the username will be generated automatically.', '' );
$form->add_password_input ( null, 'password', 'Password', null, '<strong>This field is optional</strong>. If you don\'t fill it in the password will be generated automatically.', '' );
?>

<div id='custom-form'>
	<p>You can use this form to create new players in the Wordpress database and record them as having attended this training session.</p>
   <?php $form->form_output() ?>

</div>