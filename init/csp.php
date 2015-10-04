<?php

function getExternalScriptDomains( $queue ) {/*

	$externalDomains = array();

	foreach ( $queue->registered as $registeredFile ) {

		$parts             = explode( '/', $registeredFile->src );
		$domainOrDirectory = '';

		foreach ( $parts as $part ) {

			if ( '' !== $part && 'http:' !== $part && 'https:' !== $part ) {
				$domainOrDirectory = $part;
				break;
			}
		}

		if ( false !== strpos( $domainOrDirectory, '.' ) && $domainOrDirectory !== site_url() ) {
			$externalDomains[] = $domainOrDirectory;
		}
	}

	$externalDomains = array_unique( $externalDomains );

	return $externalDomains;*/
}

function contentSecurityPolicy() {
/*
	global $wp_scripts;
	global $wp_styles;

	// Emoji scripts and styles are printed inline which violates CSP; I have moved them into a separate file so remove them from wp_head
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	$scriptDomains = implode( ' ', getExternalScriptDomains( $wp_scripts ) );
	$styleDomains  = implode( ' ', getExternalScriptDomains( $wp_styles ) );
	header( "Content-Security-Policy: default-src 'self'; script-src 'self' $scriptDomains; style-src 'self' $styleDomains" );
	*/
}

add_action( 'wp_enqueue_scripts', 'contentSecurityPolicy', 10000 );