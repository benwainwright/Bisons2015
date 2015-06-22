<?php

global $bisonPlayersFlashMessage;
global $bisonsMembership;

$status = $bisonsMembership->getStatus(get_current_user_id());

if (get_user_meta(get_current_user_id(), 'joined', true) && ( $status == 'None' || $status == 'cancelled' || $status == 'cancelled') ) {

	$bisonPlayersFlashMessage[] = array(
		'priority' => 1,
		'message' => "Although you have submitted a membership form you still need to organise payment. Click the 'subs' link above to get it sorted!"
	);

}

elseif ( ! get_user_meta(get_current_user_id(), 'joined', true) ) {

	$bisonPlayersFlashMessage[] = array(
		'priority' => 1,
		'message'  => "Looks like you are not yet a club member. Click the <strong>join</strong> link above to sign up!"
	);

}
