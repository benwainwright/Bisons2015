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

    
    function __construct( $settings, $responseFormat = 'json', $cacheDirectory = false, $defaultCacheTimeout = 600 ) {

        $this->keys['key'] = $settings['twitter']['key'];
        $this->keys['secret'] = $settings['twitter']['secret'];;
        $this->urls = $settings['twitter']['urls'];
        $this->endpoint = $this->urls['endpoint'];
        parent::__construct ( $responseFormat, $cacheDirectory, $defaultCacheTimeout );
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

        // If a bearer token hasn't been requested, get one
        if( ! $this->bearer_token ) {
            $this->request_bearer_token();
	        $headers = array( 'Authorization' => 'Bearer '.$this->bearer_token );
        }
        
        // Set url using the method passed into the function
        $url = $this->endpoint.$method.".".$this->response_format;

        // Send cURL request and save it
        $this->response = $this->sendHTTPRequest($http_request_type, $url, $parameters, $headers );

        // Send cURL request and return it
        return $this->response;
    }

    private function sign_request ()
    {
        $encodedKey = urlencode($this->keys['key']);
        $encodedSecret = urlencode($this->keys['secret']);
        $credentials = base64_encode($encodedKey.':'.$encodedSecret);

        // Step 2: Get token
        $headers = array(
            'Authorization' => 'Basic '.$credentials,
            'Content-type' => 'application/x-www-form-urlencoded;charset=UTF-8');

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

	    if ( ! $this->bearer_token = get_transient('bb_twitter_bearer_token') ) {
		    $this->bearer_token = true;
		    $this->bearer_token = $this->sendHTTPRequest( "POST", $this->urls['oath2-token-endpoint'], $signed['parameters'], $signed['headers'], 900, false, 'json' )->access_token;
	    }

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
            'Authorization' => 'Basic '.$credentials,
            'Content-type' => 'application/x-www-form-urlencoded;charset=UTF-8');

        $parameters = array('access_token' => $this->bearer_token);
        $response = $this->sendHTTPRequest(   "POST", $this->urls['oath2-token-endpoint'], $paramters, $headers, 0, false, false, 'json' );
        $this->bearer_token = false;
        return true;

    }
}