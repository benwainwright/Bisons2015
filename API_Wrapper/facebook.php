<?php

include_once ('api-wrapper.php');

class Facebook extends API_Wrapper
{
    
    
    private $access_type;
    private $access_token;
    
    function __construct( $settings, $response_format = 'json', $cachedir = false, $default_cache_timeout = false ) {

        $this->keys['key'] = $settings['facebook']['key'];
        $this->keys['secret'] = $settings['facebook']['secret'];;
        $this->urls = $settings['facebook']['urls'];
        $this->endpoint = $this->urls['endpoint'];
        parent::__construct ( $response_format, $cachedir, $default_cache_timeout );
        return true;
    }
    
    
    function request ( $rootnode, $edge = false, $parameters = false )
    {
        // If there is no access token, request one
        if ( ! $this->access_token ) $this->get_app_only_access_token( );
        
        // Add the access token to the parameter list
        $parameters['access_token'] = $this->access_token;
        
        // Build the URL string
        $url = $this-> endpoint . '/' . $rootnode;
        $url .= $edge ? '/' . $edge : '';
        
        // Send curl request and return response
        $this->response = $this->send_curl_request ( "GET", $url, $parameters );
        //new dBug ($this->response);
        return $this->response;
        
    }
    
    
    function get_app_only_access_token( )
    {
        
        // Get credentials from keyfile
        $parameters = array (
            'client_id' => $this->keys['key'],
            'client_secret' => $this->keys['secret'],
            'grant_type' => 'client_credentials'
        );
        
        // Send curl request and store response in class attributes
        $this->response = $this->send_curl_request ( "GET", $this->endpoint . 'oauth/access_token' , $parameters, false, 900, false, true, 'text' );
        
   
        // Store and return the actual access token
        $returnval = explode ( '=', $this->response );
        $this->access_token = $returnval[1];
        $this->access_type = 'app';
        return $this->access_token;
    }
    
}