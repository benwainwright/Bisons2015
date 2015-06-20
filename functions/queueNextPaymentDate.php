<?php

function queueNextPaymentDate($userID, $date) {
	$paymentDates = get_option( 'bisons_user_payment_dates' );
	$paymentDates[$userID] = $date;
	update_option('bisons_user_payment_dates', $paymentDates);
}