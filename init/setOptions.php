<?php

function createOptions() {

	add_option('bisonsTwoFactorAuth', 1, '', 'yes');

}

function deleteOptions() {

	delete_option('bisonsTwoFactorAuth');
}

add_action('after_switch_theme', 'createOptions');
add_action('switch_theme', 'deleteOptions');