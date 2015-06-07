<?php

/**
 * Class API_Wrapper
 *
 * This class is used to interface with various REST APIs
 * @author Ben Wainwright
 * @package API_Wrapper
 * @version 0.2
 * @see Flikr
 * @see Twitter
 * @see Facebook
 */
 
 
class API_Wrapper {

    /**#@+
     * @access private
     * @var string $keys API Keys/secrets/tokens needed for current API
     * <code>
     * $array = array(
     *  'key'                   => 'YOUR_API_KEY'            // API Key for your application - required
     *  'secret'                => 'YOUR_API_SECRET'         // API Secret for your application - required
     *  'access_token'          => 'YOUR_ACCESS_TOKEN'       // API Access token for your application - specific to Facebook
     *  'access_token_secret'   => 'YOUR_ACCESS_TOKEN_SECRET // API Access token secret for your application - specific to Facebook
     * </code>
     * @var array|string $urls[string] URLs needed to make API call
     */

    protected $keys;
    protected $format;
    protected $urls;
    protected $response_format;
    protected $response_var;
    protected $endpoint;
    protected $response;
    protected $errors;
    protected $cachedir;
    protected $default_cache_timeout;
    public $raw_response;

    /**
     * Constructor function: Populate global class attributes
     * @param $key string API Key
     * @param $secret string API Secret
     * @param $url string API Endpoint URL
     * @param $format string Response format
     * @return bool
     * @todo need to handle URLS so that they are input in the correct format (ie trailing slashes etc)
     */
    
    function __construct( $response_format, $cachedir, $default_cache_timeout) {

        $this->cachedir = $cachedir ? $cachedir : dirname ( __FILE__ ) . '/cache';
        $this->default_cache_timeout = $default_cache_timeout ? $default_cache_timeout : 600 ;
        $this->response_format = $response_format ? $response_format : "json";
        
        return true;
    }
    
    protected function init_cache ( )
    {
        if ( ! file_exists ( $this->cachedir ) )
            mkdir ( $this->cachedir );
    } 
    

    /**
     * Returns the result from the last API request
     * @return mixed
     */
    public function get_last_response() {
        return $this->response;
    }


    public function raw_response() {
        return $this->raw_response;
    }
    /**
     * Returns cURL errors
     */
    public function get_errors() {
        return $this->errors;
    }


    private function build_query_string ( $parameters ) {
        $parameterstring = '';
        if( is_array( $parameters ) ) 
        {
            $i = 0;
            foreach( $parameters as $key => $parameter ) {
                $parameterstring .= urlencode( $key ).'='.urlencode( $parameter );
                if( $i < count( $parameters ) - 1 ) $parameterstring .= '&';
                $i++;
            }
            return $parameterstring;
        } else return false;
    }

    /**
     * This function checks for the existence of the following dir/file structure      
     * <requestType>/<url-SHA1-Hash>/<parameters-query-string-SHA1-Hash>/<headers-query-string-SHA1-Hash>/<responseFormat>
     * 
     * If the dir & file already exist and is older than the specified timeout, the $output will be set to the contents of the file. 
     * 
     * If not, dir/file structure is created, $this->curl() is called and the response is stored in the cache file
     */

    protected function sendHTTPRequest ( $requestType, $url = false, $parameters = false,  $headers = false, $timeout = false, $debug = FALSE, $cache = TRUE, $response_format = 'json'  )
    {
        
        $timeout = $timeout ? $timeout : $this->default_cache_timeout; // Pull default cache timeout from class attributes if none specified

	    $url = $url ? $url : $this->endpoint;                           // Pull URL from class attributes if not passed
	    $parameterString = $this->build_query_string ( $parameters );   // Build parameter and header strings
	    $headerString = $this->build_query_string ( $headers );
		$hash = md5($requestType . ':' . $parameterString . ':' . $headerString);

	    if ( ! ( $output = get_transient("bb_http_$hash") ) ) {

		    $args = array(
			    'method'    =>  $requestType
		    );

		    switch ( $requestType ) {
			    case "GET":
					$url .= "?$parameterString";
				    break;

			    case "POST":
					$args['body'] = $parameters;
				    break;
		    }

		    if ( $headers ) {
			    $args['headers'] = $headers;
		    }


		    $this->wpRemoteRequestResponse = wp_remote_request($url, $args);

		    if ( is_wp_error($this->wpRemoteRequestResponse)) {

			    $error_message = $this->wpRemoteRequestResponse->get_error_message();
			    echo "Something went wrong: $error_message";
		    }

		    else {
			    $output = $this->wpRemoteRequestResponse['body'];
			    set_transient("bb_http_$hash",$output,$timeout);
		    }

	    }

        switch ( $response_format ) // Decode and return $output depending on $response_format
        {
            case "json": return json_decode( $output );
            case "text": return $output; 
        }
    }

    /**
     * Internal function to make a HTTP request via cURL
     * @access private
     * @param string $requesttype type of HTTP request
     * @param bool|string $url URL to send the HTTP request to. If set to FALSE, will use the 'endpoint' class variable
     * @param bool|string|array $parameters parameters to pass into HTTP request
     * @param string|array $headers any headers to be added to HTTP request
     * @param string $response_format How do you want your results? Defaults to JSON
     * @return string
     * @todo add support for PUT
     * @todo add support for alternative response formats
     *
     */
    protected function curl ($requesttype, $url = false, $parameters = false,  $headers = false, $response_format = 'json', $debug = FALSE) {


        $url = $url ? $url : $this->endpoint;                           // If the endpoint hasn't been set, use the class base_url
        $curl = curl_init ( );                                           // Initialize curl
        $headers = is_array ($headers) ? $headers : array ( $headers );   // Make sure headers are sent as an array
        $parameterstring = $this->build_query_string ( $parameters );    // Build paramstring

        // Set options
        $options = array
        (
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => 1,
            CURLOPT_RETURNTRANSFER => 1
        );


        // Set options for different request types
        switch ( $requesttype )
        {

            case ( "POST" ):
                $options[CURLOPT_URL] = $url;
                $options[CURLOPT_POSTFIELDS] = $parameterstring;
                $options[CURLOPT_POST] = 1;
                break;

            case ( "GET" ):

                $options[CURLOPT_URL] = $url.'?'.$parameterstring;
                break;
        }

        curl_setopt_array( $curl, $options );

        if ( $debug ) {
            $fp = fopen( dirname ( __FILE__ ) .'/curllog.txt', 'a+' );
            curl_setopt($curl, CURLOPT_VERBOSE, 1);
            curl_setopt($curl, CURLOPT_STDERR, $fp);
        }

        // Send request and store raw response
        $this->raw_response = curl_exec( $curl );

        // If curl threw an error, save it into the class variable and return false;
        if( curl_errno($curl) ) {
            $this->errors[] = curl_error( $curl );
            return false;
        }

        // Close curl
        curl_close($curl);
        return $this->raw_response;
    }
}