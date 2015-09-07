<?php

$options = get_option('api-settings-page');

$merchant = 0 == $options['gcl-environment'] ? $options['gcl-prod-merchant-id'] : $options['gcl-sandbox-merchant-id'];

$merchant = GoCardless_Merchant::find($merchant);

$subs = $merchant->subscriptions();

$preAuths = $merchant->pre_authorizations();

foreach ( $subs as $sub ) {

	$sub->cancel();

}

foreach ( $preAuths as $pa ) {

	$pa->cancel();

}