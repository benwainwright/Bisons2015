<?php

/************************* Insert Menu *******************/
function add_fees_menu() {
	add_menu_page(  'Payments',
					'Payments',
					'committee_perms',
					'payment',
					'includeFeesTemplate',
					'dashicons-cart');
}
add_action('admin_menu', 'add_fees_menu');


/************************* Include Template *******************/
function includeFeesTemplate() {
	include_once( 'templates/payments.php' );
}