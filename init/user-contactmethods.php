<?php
function modifyContactMethods( $fields ) {

	if (current_user_can('modify_gcl_user_id')) {
		$fields['GCLUserID'] = 'GoCardless User ID';
		$fields['currentFee'] = 'Monthly DD Payment';

	}

	return $fields;
}
add_filter('user_contactmethods','modifyContactMethods');