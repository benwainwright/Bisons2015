<?php

$webhook = file_get_contents('php://input');
$webhook_array = json_decode($webhook, true);
$webhook_valid = GoCardless::validate_webhook($webhook_array['payload']);

if ($webhook_valid == TRUE)
{
    
    $data = $webhook_array['payload'];
    

    // Create webhook log
    $hook_log = array(
        'post_status' => 'publish',
        'post_date' => date('Y-m-d H:i:s'),
        'post_author' => 1,
        'post_type' => 'webhook'
    );
    
    switch ( $data['resource_type'] )
    {
        
/************* Bill webhooks ***************/        
        case "bill":
            
            foreach ( $data['bills'] as $bill )
            {
                
                // Look for membership forms that match the source id. If not look for forms that match the id
                $mem_form = get_posts ( array ( 'post_type' => 'membership_form',  'meta_key' => 'gcl_sub_id', 'meta_value' => $bill['source_id'] ) ) ;
                $mem_form = $mem_form ? $mem_form : get_posts ( array ( 'post_type' => 'membership_form',  'meta_key' => 'gcl_sub_id', 'meta_value' => $bill['id'] ) );
                $mem_form = $mem_form[0]->ID;
                
                // Log webhook
                $id = wp_insert_post( $hook_log );
                $resource = array ( 'resource_type' => 'bill', 'resource_content' =>  $bill );
                update_post_meta($id, 'resource', $resource);
                update_post_meta($id, 'source_id', $bill['source_id']);
            }
            
            
            switch ( $data['action'] )
            {
/******************************************/                
                case "created": 
                
                break;
/******************************************/
                case "paid":

                    update_post_meta($mem_form, 'last_payment', date('Y-m-d H:i:s') );
                    update_post_meta($the_post, 'mem_status', 'Active' );

                    switch ( get_post_meta($mem_form, 'payment_status', true) )
                    {
                        // Single payments pending or failed? Update to single payment paid status
                        case 2: case 3: 
                            update_post_meta($mem_form, 'payment_status', 4);  
                        break;
                        
                        // Sub created or failed? Update to payments successful status
                        case 7: case 10: update_post_meta($mem_form, 'payment_status', 8);
                    }
                        
                break;
/******************************************/
                case "withdrawn": 
                
                break;
/******************************************/
                case "failed":
                    switch ( get_post_meta($mem_form, 'payment_status', true) )
                    {
                        case 2: update_post_meta($mem_form, 'payment_status', 3);
                        case 7: case 8: update_post_meta($mem_form, 'payment_status', 10);
                    }
                    update_post_meta($the_post, 'mem_status', 'Inactive' );
                break;
                
                case "cancelled": 
                    switch ( get_post_meta($mem_form, 'payment_status', true) )
                    {
                        case 2: update_post_meta($mem_form, 'payment_status', 5);
                    }
                break;
                
/******************************************/
                case "refunded": 
                
                break;
/******************************************/
                case "chargedback": 
                
                break;
/******************************************/
                case "retried":
                    
/******************************************/
            }
            break;
            
/************* Preauth webhooks ************/        
        case "pre_authorization":
            
            foreach ( $data['pre_authorizations'] as $pre_authorization )
            {
                $id = wp_insert_post( $hook_log );
                $mem_form = get_posts ( array ( 'post_type' => 'membership_form',  'meta_key' => 'gcl_sub_id', 'meta_value' => $pre_authorization['id'] ) ) ;
                $mem_form = $mem_form[0]->ID;
                $resource = array ( 'resource_type' => 'pre_authorization', 'resource_content' =>  $pre_authorization );
                update_post_meta($id, 'resource', $resource);   
            }
            
            $id = wp_insert_post( $hook_log );
            
            switch ( $data['action'] )
            {
/******************************************/
                case "cancelled": 
                
                break;
/******************************************/
                case "expired":
                     
/******************************************/                    
            }
            break;

 
/******* Subscription webhooks *************/        
        case "subscription":
            
            foreach ( $data['subscriptions'] as $subscription )
            {
                $id = wp_insert_post( $hook_log );
                $mem_form = get_posts ( array ( 'post_type' => 'membership_form',  'meta_key' => 'gcl_sub_id', 'meta_value' => $subscription['id'] ) ) ;
                $mem_form = $mem_form[0]->ID;
                $resource = array ( 'resource_type' => 'subscription', 'resource_content' => $subscription );
                update_post_meta($id, 'resource', $resource);
            }
               
          switch ( $data['action'] )
            {
/******************************************/
                case "cancelled": 
                    
                switch ( get_post_meta($mem_form, 'payment_status', true) )
                {
                    
                    // Subscription created or payments successful? Update to Sub cancelled status
                    case 7: case 8: case 10: update_post_meta($mem_form, 'payment_status', 9);
                }
                    update_post_meta($mem_form, 'mem_status', 'Inactive' );
                break;
/******************************************/
                case "expired":
                switch ( get_post_meta($mem_form, 'payment_status', true) )
                {
                    
                    // Subscription created or payments successful? Update to Sub ended status
                    case 7: case 8: case 9: case 10: update_post_meta($mem_form, 'payment_status', 11);
                }
                update_post_meta($mem_form, 'mem_status', 'Inactive' );
                     
/******************************************/
            }
            break;
    }
    
    // Success header
    header('HTTP/1.1 200 OK');   
} 
else 
{
    header('HTTP/1.1 403 Invalid signature');
}
