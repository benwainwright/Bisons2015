<?php
/**
 * Settings for the Bisons Online theme
 */

$GLOBALS['api_settings'] = array

(

    'twitter' => array
    (
    
            'key'        =>     'FQ7EgKDLQ8C8SRN5Rjy9aQ',
            'secret'     =>     'PLN35HxzSvOhebJYqOkYoAoeToIr2PsTLZ6l0sy4nbA',
            'urls'       =>      array(
                                'endpoint' => 'https://api.twitter.com/1.1/',
                                'oath2-token-endpoint'    => 'https://api.twitter.com/oauth2/token',
                                'oath2-invalidate-token'  => 'https://api.twitter.com/oauth2/invalidate_token.')
    ),
    
    'facebook' => array
    (
            'key'        =>      '250459058445643',
            'secret'     =>      'a9649390300e86824481fb08cf6f02a9',
            'urls'       =>      array( 'endpoint' => 'https://graph.facebook.com/' )      
    ),
    
    'flikr' => array
    (
            'key'        =>      'f23fefb66bd6da001c9d1546dd765689',
            'secret'     =>      'd0d4ae6bcc95c0c0',
            'urls'       =>      array( 'endpoint' => 'https://api.flickr.com/services/rest/' )   
    ),
    
    'interface' => array
    (
        
    )

);

$GLOBALS['under_construction_whitelist'] = array
(
    '84.92.163.247',
    '192.168.1.12'
);

$GLOBALS['under_construction'] = false;