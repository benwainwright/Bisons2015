<?php



$options = get_option('api-settings-page');

if (   $options['gcl-prod-app-id']
    && $options['gcl-prod-app-secret']
    && $options['gcl-prod-merchant-id']
    && $options['gcl-prod-access-token']
    && $options['gcl-sandbox-app-id']
    && $options['gcl-sandbox-app-secret']
    && $options['gcl-sandbox-merchant-id']
    && $options['gcl-sandbox-access-token'] ) {

    include_once ('GoCardless.php');

    if (  0 == $options['gcl-environment']) {

        GoCardless::$environment = 'production';
        
        // Set your live environment developer credentials
        $account_details = array(
          'app_id'        => $options['gcl-prod-app-id'],
          'app_secret'    => $options['gcl-prod-app-secret'],
          'merchant_id'   => $options['gcl-prod-merchant-id'],
          'access_token'  => $options['gcl-prod-access-token']
        );
    }
    else {

        // Set your sandbox environment developer credentials
        $account_details = array(
          'app_id'        => $options['gcl-sandbox-app-id'],
          'app_secret'    => $options['gcl-sandbox-app-secret'],
          'merchant_id'   => $options['gcl-sandbox-merchant-id'],
          'access_token'  => $options['gcl-sandbox-access-token']
        );  
    }
    
    GoCardless::set_account_details($account_details);
}
else
{
	errorMessage('Gocardless API settings are incomplete...');
}
