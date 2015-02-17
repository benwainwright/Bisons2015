<?php



$data = &$the_query->query['bisons_data'];

$form_user = ( isset ( $_GET['player_id'] ) && current_user_can ('committee_perms') ) 
                ? $_GET['player_id'] : get_current_user_id();


// Check whether a membership form has been filled out.
$current_form = new WP_Query ( array (
    'post_type' => 'membership_form',
    'posts_per_page' => 1,
    'orderby'   => 'date',
    'order'     => 'ASC',
    'author'    => $form_user
));

// If a membership form exists, load it from Wordpress
if ( $current_form->have_posts() ) 
{
    // Get the data from the current membership form
    while ( $current_form->have_posts() ) 
    {
        $current_form->the_post();
        $form_id = get_the_id();

        
        // Insert form id and date into query for template use
        $form = array( 
            'date' => get_the_date(),
            'form_id'  => $form_id 
        );
        
        /***************************************************************
        *********************Cancel Subscription ***********************
        ***************************************************************/
        
        if ( wp_verify_nonce($_POST['nonce'], 'cancel_resource_' . get_post_meta ($form_id, 'gcl_sub_id', true) ) && $_POST['cancel_membership'] == 'confirmed')
        {
            
            if ( $_POST['resource_type'] == 'sub')
            {
                GoCardless_Subscription::find( get_post_meta ($form_id, 'gcl_sub_id', true)  )->cancel();
                $GLOBALS['bisons_flash_message'] = "We are sorry to see you go! Don't forget that you can return to this page at any time to reinstate your payments.";
            }
            else 
            {
                $bill = GoCardless_Bill::find( get_post_meta ($form_id, 'gcl_sub_id', true) )->cancel();
                $GLOBALS['bisons_flash_message'] = "Membership payment has been cancelled.";
            }
        }
        
        /***************************************************************
         ************* Redirect to GoCardless from POST  ***************
         ***************************************************************/     
         
        // If a POST was submitted, check the nonce
        if ( wp_verify_nonce( $_POST['nonce'], 'bisons_submit_new_dd_form_'.$form_id) )
        {
            
            // The return address is the current page
            $return_addy = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
            
            // Populate the user array with data from the membership form
            $user = array(
                'first_name'            => get_post_meta( $form_id, 'firstname', true ),
                'last_name'             => get_post_meta( $form_id, 'surname', true ),
                'email'                 => get_post_meta( $form_id, 'email_addy', true ),
                'billing_address1'      => get_post_meta( $form_id, 'streetaddyl1', true ),
                'billing_address2'      => get_post_meta( $form_id, 'streetaddyl2', true ),
                'billing_town'          => get_post_meta( $form_id, 'streetaddytown', true ),
                'billing_postcode'      => get_post_meta( $form_id, 'postcode', true ),
            );
            
            // Get the correct URL from GCL depending on the payment method
            if ( $_POST['paymethod'] === "Monthly Direct Debit")
            {
                // Determine feeid depending on if we are looking at a player or supporter
                $feeid = ( $_POST['playermembershiptypemonthly'] != '' ) 
                     ? $_POST['playermembershiptypemonthly'] 
                     : $_POST['supportermembershiptypemonthly'];
                
                
                // Get fees from database
                $amount = get_post_meta( $feeid, 'fee-amount', true );
                
                $amount_in_pounds = pence_to_pounds ( $amount, false );     

                $setup_fee = pence_to_pounds ( get_post_meta( $feeid, 'initial-payment', true ), false );
                

                // Populate subscription_details array
                $subscription_details = array(
                    'amount'           => $amount_in_pounds,
                    'name'             => get_post_meta( $feeid, 'fee-name', true ),
                    'interval_length'  => 1,
                    'interval_unit'    => 'month',
                    'currency'         => 'GBP',
                    'user'             => $user,
                    'state'            => "DD+" . wp_create_nonce( 'GCL-submit' . $form_id ),
                    'redirect_uri'     => $return_addy
                );
                
                // If there is a description, add it
                if ( $description = get_post_meta( $feeid, 'fee-description', true ) ) 
                    $subscription_details['description'] = $description;
                        
                // If there is a setup fee add it and add a note about it to the description                        
                if ( $setup_fee > 0 ) 
                {
                    $subscription_details['setup_fee'] = $setup_fee;
                    $subscription_details['description'] .= 'Note that your first payment will be debited as a separate payment on the same date as the one off fee' ; 
                }
                
                // Submit to GCL and get the url
                $gocardless_url = GoCardless::new_subscription_url($subscription_details);
            }
            else
            {
                
                // Determine feeid depending on if we are looking at a player or supporter
                $feeid = ( $_POST['playermembershiptypesingle'] != '' ) 
                            ? $_POST['playermembershiptypesingle'] 
                            : $_POST['supportermembershiptypesingle'];
                  
                // Populate subscription_details array
                $subscription_details = array(
                    'amount'           => pence_to_pounds ( get_post_meta( $feeid, 'initial-payment', true ), false ),
                    'name'             => get_post_meta( $feeid, 'fee-name', true ),
                    'currency'         => 'GBP',
                    'user'             => $user, 
                    'state'            => "SP+" . wp_create_nonce( 'GCL-submit' . $form_id ),
                    'redirect_uri'     => $return_addy
                );                
                
                // Add a description if there is one
                if ( $description = get_post_meta( $feeid, 'fee-description', true ) ) 
                    $subscription_details['description'] = $description;
                  
               // Submit to GCL and get the URL
               $gocardless_url = GoCardless::new_bill_url($subscription_details);
            }
            
            // Redirect straight to GoCardless URL
            wp_redirect($gocardless_url);
            exit();
        }
        
        
        /***************************************************************
         *******************Returning from GoCardless ******************
         ***************************************************************/        
         
        // If a 'state' has been passed into the querystring, split it into an array
        $state = isset ( $_GET['state'] ) ? explode ('+', $_GET['state']) : null;
        
        // Check the correct nonce has been passed back for GCL confirmation
        if ( wp_verify_nonce( $state[1], 'GCL-submit' . $form_id ) ) 
        {
            // Populate the 'confirmed_params' array from the querystring
            $confirm_params = array(
              'resource_id'    => $_GET['resource_id'],
              'resource_type'  => $_GET['resource_type'],
              'resource_uri'   => $_GET['resource_uri'],
              'signature'      => $_GET['signature'],
              'state'          => $_GET['state']
            );
            
            // Try to confirm resource
            if ( $confirmed_resource = GoCardless::confirm_resource($confirm_params) )
            {
                
                // If resource confirms, update the form with the payment information depending on the subscription type
                $post_author = get_post_field ( 'post_author', $the_post );
                $type = $state[0];
                
                if ( $type == 'DD' )
                {
                    update_post_meta($form_id, 'payment_type', "Direct Debit" ); 
                    try 
                    {
                        $resource = GoCardless_Subscription::find($_GET['resource_id']);
                    }
                    catch (Exception $e)
                    {
                        new dBug ($e);
                    }
                    update_post_meta($form_id, 'payment_status', 7 );  // DD created, not yet taken payments
                }
                else
                {   
                    update_post_meta($form_id, 'payment_type', "Single Payment" );
                    try
                    {
                    $resource = GoCardless_Bill::find($_GET['resource_id']);
                    }
                    catch (Exception $e)
                    {                           
                        new dBug ($e);
                    }
                    update_post_meta($form_id, 'payment_status', 2 );  // Single payment pending         
                }
                
                // Update remaining payment information
                update_post_meta($form_id, 'gcl_sub_id', $_GET['resource_id'] );
                update_post_meta($form_id, 'gcl_sub_uri', $_GET['resource_uri'] );
                update_post_meta($form_id, 'gcl_res_type', $_GET['resource_type'] );
                update_post_meta($form_id, 'mem_name', $resource->name );
                update_post_meta($form_id, 'mem_status', 'Active' );
                
                // If the user is still a guest_player, upgrade them
                if ( check_user_role( 'guest_player' ) )
                {
                    $user = new WP_User($post_author);
                    $user->remove_role( 'guest_player');
                    $user->add_role( 'player');
                }
            }

            // Reload the page without the querystring variables and with a flash message
            $flash_message = 'Your GoCardless subscription has been created! You should revieve an email from them shortly.';                   
            wp_redirect ( remove_query_arg( array ('resource_id', 'resource_type', 'resource_uri', 'signature', 'state' ) ) . '?nonce=' . wp_create_nonce('bisons_flashmessage_nonce') . '&flash=' . urlencode ( $flash_message ) ); 
            exit();
        }

        /***************************************************************
         ********************Fetch GCL resource ************************
         ***************************************************************/  
        if ( get_post_meta($form_id, 'gcl_sub_id', true )  )
        {
            
            // Looks like we do have a GCL resource attached, so lets get it and send it to the template
            $data['has_gcl_subscription'] = true;
            
            if ( get_post_meta ($form_id, 'gcl_res_type', true) == 'bill')
            {
                $bill = GoCardless_Bill::find( get_post_meta ($form_id, 'gcl_sub_id', true) );   
                $data['gcl_resource'] = $bill; 
                $data['gcl_resource_type'] = 'bill';
            }
            else 
            {
                $subscription = GoCardless_Subscription::find( get_post_meta ($form_id, 'gcl_sub_id', true), true );
                $data['gcl_resource'] = $subscription; 
                $data['gcl_resource_type'] = 'subscription';
            }
        }
        else $data['has_gcl_subscription'] = false;

        
        /***************************************************************
         ********No GoCardless Subscription or User Cancelled **********
         ***************************************************************/        
    
        if ( ! get_post_meta($form_id, 'gcl_sub_id', true ) || $data['gcl_resource']->status == 'cancelled' )
        {
            // Looks like we don't have a GCL subscription or it's been cancelled. Give the template the fees list so the user can create a new DD

            
            // Load a list of membership fees from Wordpress
            $fees = new WP_Query ( array( 'post_type' => 'membership_fee', 'nopaging' => true ) );
            // Loop through each one
            while ( $fees->have_posts() ) 
            {
                
                $fees->the_post();
                
                $the_fee = array (
                    'id'    => get_the_id(),
                    'name' => get_post_meta( get_the_id(), 'fee-name', true),
                    'initial-payment' => get_post_meta( get_the_id(), 'initial-payment', true),
                    'amount' => get_post_meta( get_the_id(), 'fee-amount', true),
                    'description' => get_post_meta( get_the_id(), 'fee-description', true)
                );
                
                
                // Split fees into separate arrays depending on whether they are for Supporters or players and the payment type
                if ( get_post_meta( get_the_id(), 'supporter-player', true) == 'Supporter' && get_post_meta( get_the_id(), 'fee-type', true) == "Monthly Direct Debit" )
                {
                    $supporterfees[ 'direct_debits' ] [ ] = $the_fee;
                }
                else if ( get_post_meta( get_the_id(), 'supporter-player', true) == 'Supporter' && get_post_meta( get_the_id(), 'fee-type', true) != "Monthly Direct Debit")
                {
                    $supporterfees[ 'single_payments' ] [ ] = $the_fee;
                }
                else if ( get_post_meta( get_the_id(), 'supporter-player', true) == 'Player' && get_post_meta( get_the_id(), 'fee-type', true) == "Monthly Direct Debit")
                {
                    $playerfees[ 'direct_debits' ] [ ] = $the_fee;
                }
                else if ( get_post_meta( get_the_id(), 'supporter-player', true) == 'Player' && get_post_meta( get_the_id(), 'fee-type', true) != "Monthly Direct Debit")
                {
                    $playerfees[ 'single_payments' ] [ ] = $the_fee;
                }   
            }
            $data['supporterfees'] = $supporterfees;
            $data['playerfees'] = $playerfees;  

            
        }
       
        
        // Insert form details into query for the template
        $data['form_details'] = $form;
        
    }
}
else
{
    // If nomembership form found, redirect to the membership form with a flash message
    $flashmessage = 'No payment information is available just yet as you haven\'t joined the club. To put that right, fill in the form below!';
    wp_redirect ( home_url( 'players-area/membership-form/?nonce=' . wp_create_nonce('bisons_flashmessage_nonce') . '&flash=' . urlencode ( $flashmessage ) ) );
    exit();
}