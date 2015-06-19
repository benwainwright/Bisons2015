<?php function BisonsSchedulePayments() {

	$paymentDates = get_option('bisons_user_payment_dates');

	foreach ($paymentDates as $userId => $date) {

		$nextPaymentDate = getNextPaymentDate($userId);

		if ($nextPaymentDate > $date) {
			$paymentDates[$userId] = $date;

			// Create bill with GCL API
		}
	}

	update_option('bisons_user_payment_dates', $paymentDates);
}