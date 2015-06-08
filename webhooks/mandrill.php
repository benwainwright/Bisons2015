<?php
if (!INCLUDED) exit;


// Get current URL
$signed_data = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];



// Get post data
$post_data = $_POST;

// Sort it
ksort( $post_data );

//append to string
foreach ($post_data as $key => $value) 
{
    $signed_data .= $key;
    $signed_data .= $value;
}

// Get settings from Wordpress database
$options = get_option('api-settings-page');
$webhook_key = $options['mandrill-settings-webhook-key'];

// Generate signature
$generatedsig = base64_encode(hash_hmac('sha1', $signed_data, $webhook_key, true));



// Get header signature
$headers = getallheaders();

file_put_contents ( $log_file , "header=" . $headers['X-Mandrill-Signature'] . "\n", FILE_APPEND );


if ( $generatedsig === $headers['X-Mandrill-Signature'] )
{
    $events = json_decode( $_POST['mandrill_events'] );
    foreach ( $events as $event )
    {
        $email_log_item = new WP_Query ( array (
                'post_type' => 'email_log',
                'posts_per_page' => 1,
                'meta_key'   => 'email_id',
                'meta_value' => $event['_id']
        ));
    
        while ( $email_log_item->have_posts() )
        {
            $email_log_item->the_post();
    
            switch ( $event['event'] )
            {
    /*******************************************/            
                case "send":
                    break;
    
    /*******************************************/                
                case "deferral":
                    break;
    
    /*******************************************/                
                case "hard_bounce":
                    break;
    
    /*******************************************/
                case "soft_bounce":
                    break;
    
    /*******************************************/
                case "open":
                    
                    // Get the current 'opens' array for this email from Wordpress
                    $opens = array();
                    $opens = get_post_meta( get_the_id(), 'opens', true);
                    
                    // Pull the information for the new event from the webhook data
                    $new_open = array(
                        'time'        => $event['ts'],
                        'ip'          => $event['ip'],
                        'user_agent'  => $event['user_agent'],
                        'location'    => $event['location']
                    );
                    
                    // Push onto array and store in database
                    $opens[] = $new_open;
                    update_post_meta( get_the_id(), 'opens', $opens);
                    break;
    
    /*******************************************/
                case "click":
                    
                    // Get the current 'clicks' array for this email from Wordpress
                    $clicks = array();
                    $clicks = get_post_meta( get_the_id(), 'clicks', true);
                    
                    // Pull the information for the new event from the webhook data
                    $new_click = array(
                        'time'        => $event['ts'],
                        'url'         => $event['url'],
                        'ip'          => $event['ip'],
                        'user_agent'  => $event['user_agent'],
                        'location'    => $event['location']
                    );
                    
                    // Push onto array and store in database
                    $clicks[] = $new_click;
                    update_post_meta( get_the_id(), 'clicks', $clicks);
                    break;
    
    /*******************************************/
                case "spam":
                    break;
    
    /*******************************************/
                case "unsub":
                    break;
    
    /*******************************************/
                case "reject":
            }
        }
    }
    http_response_code(200);
}
else
{
    http_response_code(400);
}
