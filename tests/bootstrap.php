<?php

require_once dirname ( dirname ( dirname( dirname( dirname( __FILE__ ) ) ) ) )  . '/includes/functions.php';

function _manually_load_environment() {

	switch_theme('Bisons2015');
	@session_start();

}

tests_add_filter( 'muplugins_loaded', '_manually_load_environment' );

require_once dirname ( dirname ( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/includes/bootstrap.php';
