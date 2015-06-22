<?php

function createOptions() {

	add_option('bisonsTwoFactorAuth', 1, '', 'yes');
	add_option('bisons_user_payment_dates', array(), '', 'yes');

}

function deleteOptions() {

	delete_option('bisonsTwoFactorAuth');
	delete_option('bisons_user_payment_dates');
}

add_action('after_switch_theme', 'createOptions');
add_action('switch_theme', 'deleteOptions');