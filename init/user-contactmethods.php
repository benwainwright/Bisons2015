<?php
function modifyContactMethods( $fields ) {

	if ( current_user_can( 'editGCLMeta' ) ) {
		$fields['GCLUserID']       = 'GCLUserID';
		$fields['GCLSubID']        = 'GCLSubID';
		$fields['GCLSubName']      = 'GCLSubName';
		$fields['GCLSubStatus']    = 'GCLSubStatus';
		$fields['currentFee']      = 'currentFee';
		$fields['gcl_sub_id']      = 'gcl_sub_id';
		$fields['joined']          = 'joined';
		$fields['payMethod']       = 'payMethod';
		$fields['payWhen']         = 'payWhen';
		$fields['dayOfMonth']      = 'dayOfMonth';
		$fields['whichWeekDay']    = 'whichWeekDay';
		$fields['weekDay']         = 'weekDay';
		$fields['nextPaymentDate'] = 'nextPaymentDate';
		$fields['joiningas'] = 'joiningas';
		$fields['playermembershiptypemonthly'] = 'playermembershiptypemonthly';
		$fields['playermembershiptypesingle'] = 'playermembershiptypesingle';
		$fields['supportermembershiptypemonthly'] = 'supportermembershiptypemonthly';
		$fields['supportermembershiptypesingle'] = 'supportermembershiptypesingle';
	}

	return $fields;
}

add_filter( 'user_contactmethods', 'modifyContactMethods' );