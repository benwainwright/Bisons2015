<?php
if (!INCLUDED) exit;



else {
	foreach($_POST['fixture_id'] as $fixture) {
		if ( 'current' === $season ) {
			new dBug(wp_set_object_terms($fixture, null, 'seasons'));
		}

		else {
			new dBug(wp_set_object_terms($fixture, $season, 'seasons'));
		}
	exit;
	}
}

