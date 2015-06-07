<?php
function scheduleEveryTen( $schedules )
{
	$schedules['scheduleEveryTen'] = array(
		'interval' => 10 * 60, //7 days * 24 hours * 60 minutes * 60 seconds
		'display' => __( 'Every Ten Minutes', 'bisonsrfc' )
	);

	return $schedules;
}