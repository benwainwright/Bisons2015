<?php


/**
 * Class Twitter
 * This class extends the API wrapper and allows you to use Twitter API methods
 * @param string key
 * @package API_Wrapper
 * @subpackage Twitter
 * @see API_Wrapper
 */

 include_once ('api-wrapper.php');

class Twitter extends API_Wrapper {

    private $bearer_token;

    
    function __construct( $settings, $response_format = 'json', $cachedir = false, $default_cache_timeout = 600 ) {

        $this->keys['key'] = $settings['twitter']['key'];
        $this->keys['secret'] = $settings['twitter']['secret'];;
        $this->urls = $settings['twitter']['urls'];
        $this->endpoint = $this->urls['endpoint'];
        parent::__construct ( $response_format, $cachedir, $default_cache_timeout );
        return true;
    }

    
    /**
     * Send a request to the Twitter API. Currently only supports APP only authentication
     * @param string $method which API method
     * @param bool|array $parameters array containing parameters to be passed into the API request
     * @return bool
     * @todo add user authentication
     * @todo error handling
     */
         
    public function request ($method, $parameters = false, $http_request_type = "GET", $timeout = 0 ) {

        if ( ! $timeout )
            $timeout = $http_request_type == "POST" ? 0 : $this->default_cache_timeout;
        
        // If a bearer token hasn't been requested, get one
        if( ! $this->bearer_token ) {
            $this->request_bearer_token();
        }

        // Authenticate the request
        $headers = array(
            'Authorization: Bearer '.$this->bearer_token
        );
        
        // Set url using the method passed into the function
        $url = $this->endpoint.$method.".".$this->response_format;

        // Send cURL request and save it
        $this->response = $this->send_curl_request($http_request_type, $url, $parameters, $headers );

        // Send cURL request and return it
        return $this->response;
    }

    private function sign_request ()
    {
        $encodedkey = urlencode($this->keys['key']);
        $encodedsecret = urlencode($this->keys['secret']);
        $credentials = base64_encode($encodedkey.':'.$encodedsecret);

        // Step 2: Get token
        $headers = array(
            'Authorization: Basic '.$credentials,
            'Content-type: application/x-www-form-urlencoded;charset=UTF-8');

        $parameters = array('grant_type' => 'client_credentials');
        return array ( 'headers' => $headers, 'parameters' => $parameters );
    }

    public function get_request_token ()
    {
        
    }
    /**
     * Twitter application only authentication - invalidate bearer token
     * @link https://dev.twitter.com/docs/auth/application-only-auth
     * @access private
     * @todo error handling
     */
    private function request_bearer_token() {


        $signed = $this->sign_request();
        $response = $this->send_curl_request( "POST", $this->urls['oath2-token-endpoint'], $signed['parameters'], $signed['headers'], 900, false, 'json' );

        $this->bearer_token = $response->access_token;
        return true;
    }

    /**
     * Twitter application only authentication - invalidate bearer token
     * @link https://dev.twitter.com/docs/auth/application-only-auth
     * @return bool
     */
    private function invalidate_bearer_token() {
        // Step 1: Encode consumer key and secret
        $encodedkey = urlencode($this->keys['key']);
        $encodedsecret = urlencode($this->keys['secret']);
        $credentials = base64_encode($encodedkey.':'.$encodedsecret);

        // Step 2: Send token
        $headers = array(
            'Authorization: Basic '.$credentials,
            'Content-type: application/x-www-form-urlencoded;charset=UTF-8');

        $parameters = array('access_token' => $this->bearer_token);
        $response = $this->curl( $parameters, "POST", $headers, $this->urls['oath2-token-endpoint'], 'json' );
        $this->bearer_token = false;
        return true;

    }
}