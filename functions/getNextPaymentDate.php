<?php

function getNextPaymentDate($userId) {

	$currentYear = date('Y');
	$currentMonth = date('n');
	$daysInThisMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
	$specDay = (int) get_user_meta($userId, 'dayOfMonth', true);
	$specDay = $specDay > $daysInThisMonth ? $daysInThisMonth : $specDay;
	$whichWeekdayPos = get_user_meta($userId, 'whichWeekDay', true);
	$whichWeekday = get_user_meta($userId, 'weekDay', true);

	$payWhen = get_user_meta($userId, 'payWhen', true);

	if ($currentMonth == 12) {
		$firstDayOfNextMonth = mktime( 0, 0, 0, 0, 0, $currentYear + 1 );
	}

	else {
		$firstDayOfNextMonth = mktime( 0, 0, 0, $currentMonth + 1, 1 );
	}
	
	switch( $payWhen ){

		case "first":
				$nextPaymentDate = $firstDayOfNextMonth;
			break;

		case "last":
			$nextPaymentDate = mktime( 0, 0, 0, $currentMonth, $daysInThisMonth );
			break;

		case "specificDay":
			$nextPaymentDate = mktime( 0, 0, 0, $currentMonth, $specDay ) ;
			if($nextPaymentDate < time()) {
				$nextPaymentDate = mktime( 0, 0, 0, $currentMonth +1, $specDay ) ;
			}
			break;

		case "specificWeekday":
			$dateString = $whichWeekdayPos . ' ' . $whichWeekday . ' of  ' . date('F') . ' ' . date('Y');
			$nextPaymentDate = strtotime($dateString);
			if($nextPaymentDate < time()) {
				$dateString = $whichWeekdayPos . ' ' . $whichWeekday . ' of ' . date('F', $firstDayOfNextMonth) . ' ' . ($currentMonth == 12 ? date('Y') + 1 : date('Y'));
				$nextPaymentDate = strtotime($dateString);
			}



	}

	return $nextPaymentDate;

}
