<?php

foreach( glob( __DIR__ . '/schedules/*.php') as $hook ) {

	$name = pathinfo( $hook )['filename'];
	add_filter( 'cron_schedules', $name );

}

// Active schedule on theme load
function activateSchedules()
{
	foreach( glob( __DIR__ . '/actions/*.php') as $hook ) {

		$hook = explode('-', pathinfo( $hook )['filename']);
		$name = $hook[0];
		$schedule = $hook[1];
		$timestamp = wp_next_scheduled( $name );

		if ( ! $timestamp || $timestamp < time() ) {
			wp_schedule_event( time(), 'schedule' . $schedule, $name );
		}
	}
}

foreach( glob( __DIR__ . '/actions/*.php') as $hook ) {

	$name = explode('-', pathinfo( $hook )['filename'])[0];

	add_action( $name , $name );
}

add_action( 'after_switch_theme', 'activateSchedules' );

function deActivateSchedules()
{
	foreach( glob( __DIR__ . '/actions/*.php') as $hook ) {
		$name = explode('-', pathinfo( $hook )['filename'])[0];
		wp_clear_scheduled_hook($name);
	}
}
add_action( 'switch_theme', 'deActivateSchedules');
