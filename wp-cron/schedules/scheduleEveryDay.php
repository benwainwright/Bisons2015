<?php
function scheduleEveryDay( $schedules )
{
	$schedules['scheduleEveryDay'] = array(
		'interval' => 60 * 60 * 24, // 7 days * 24 hours * 60 minutes * 60 seconds
		'display' => __( 'Every day', 'bisonsrfc' )
	);

	return $schedules;
}