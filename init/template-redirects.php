<?php function page_redirect() {

	$file = explode('?', __DIR__ . '/../template_redirects' . $_SERVER['REQUEST_URI'])[0];

	if ( file_exists($file) && ! is_dir($file) ) {
		require( $file );
		exit;
	}
}
add_action('template_redirect', 'page_redirect');