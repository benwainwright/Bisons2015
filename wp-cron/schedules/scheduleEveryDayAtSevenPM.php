<?php
function scheduleEveryDayAtSevenPM( $schedules )
{
	$schedules['scheduleEveryDayAtSevenPM'] = array(
		'startTime' => 420,
		'interval' => 10 * 60, //7 days * 24 hours * 60 minutes * 60 seconds
		'display' => __( 'Every day at 7pm', 'bisonsrfc' )
	);

	return $schedules;
}