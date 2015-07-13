<?php
function getTopAttenders($count = 10) {
	$attendance = getAttendance()['players'];

	$attendancePercentages = array();

	foreach ( $attendance as $playerID => $details ) {

		$t                                  = $details['stats']['training'];
		$w                                  = $details['stats']['watching'];
		$c                                  = $details['stats']['coaching'];
		$a                                  = $details['stats']['absent'];
		$p                                  = $t + $w + $c;
		$sum                                = $p + $a;
		$presentPercentage                  = ( 100 / $sum ) * $p;
		$attendancePercentages[ $playerID ] = $presentPercentage;
	}

	arsort( $attendancePercentages );

	$i      = 1;
	$topAttenders = array();

	foreach ( $attendancePercentages as $userID => $percentage ) {

		$topAttenders[ $i ] = array(
			'name'       => get_user_by( 'id', $userID )->display_name,
			'ID'         => $userID,
			'percentage' => $percentage
		);

		if ( $i > $count - 1 ) {
			break;
		} else {
			$i ++;
		}
	}

	return $topAttenders;
}