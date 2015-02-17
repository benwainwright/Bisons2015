<?php
  $return_addy = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
  switch (  $_POST['paymethod']  ) 
  {
       case "I've already paid":
           break;
       
       case "Monthly Direct Debit":
            $subscription_details = array(
                'amount'           => pence_to_pounds ( get_post_meta( $_POST['membershiptypemonthly'], 'fee-amount', true ), false ),
                'name'             => get_post_meta( $_POST['membershiptypemonthly'], 'fee-name', true ),
                'interval_length'  => 1,
                'interval_unit'    => 'month',
                'currency'         => 'GBP',
                'user'             => $user,
                'state'            => $post . "+DD",
            );
           break;
       
       case "Single Payment":
            $subscription_details = array(
                'amount'           => pence_to_pounds ( get_post_meta( $_POST['membershiptypesingle'], 'fee-amount', true ), false ),
                'name'             => get_post_meta( $_POST['membershiptypemonthly'], 'fee-name', true ),
                'currency'         => 'GBP',
                'user'             => $user, 
                'state'            => $post . "+DD",                       
            );
           break;
    }

    $gocardless_url = GoCardless::new_subscription_url($subscription_details);