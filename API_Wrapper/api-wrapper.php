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
     
    protected function send_curl_request ( $requesttype, $url = false, $parameters = false,  $headers = false, $timeout = false, $debug = FALSE, $cache = TRUE, $response_format = 'json'  )
    {
        
        $timeout = $timeout ? $timeout : $this->default_cache_timeout; // Pull default cache timeout from class attributes if none specified
        
        
        if ( $cache ) // If the timeout has not been set to zero
        {
            $this->init_cache( );                                           // Create cache directory if there isn't one
            $url = $url ? $url : $this->endpoint;                           // Pull URL from class attributes if not passed
            $parameterstring = $this->build_query_string ( $parameters );   // Build parameter and header strings
            $headerstring = $this->build_query_string ( $headers );
            
            // Create a directory tree based on MD5 hashes of url and parameter/header query strings
            if( ! file_exists ( $requesttypedir = $this->cachedir . '/' . $requesttype ) ) mkdir (  $requesttypedir );
            if( ! file_exists ( $urlsdir = $requesttypedir . '/' . sha1 ( $url ) ) ) mkdir ( $urlsdir );
            if( ! file_exists ( $parametersdir = $parameterstring ? $urlsdir . '/' . sha1 ( $parameterstring ) : $urlsdir . '/' . 'noparams' ) ) mkdir (  $parametersdir );        
            if( ! file_exists ( $cachedir =  $headerstring ? $parametersdir . '/' . sha1 ( $headerstring ) : $parametersdir . '/' . 'noheaders' ) ) mkdir (  $cachedir );
                   
            $cachefile = $cachedir . '/' . $response_format;                                                          // Build cachefile name
            if ( file_exists( $cachefile ) ) $fileage = time( ) - filemtime ( $cachefile );                           // If the cachefile exists, determine how old it is. If not set fileage to zero
            else $fileage = false;     
            if ( $fileage < $timeout && $fileage !== false && $_GET['flushcache'] != 'true') $output = file_get_contents ( $cachefile );              // If file age is less than timeout, read the file contents into output variable;
            
            // Otherwise send the request and store the output in the cachefile
            else
            {
                $fh = fopen ( $cachefile, 'w');
                $output = $this->curl ( $requesttype, $url, $parameters, $headers, 'json', $debug );
                fwrite ( $fh,  $output );
                fclose ( $fh );
            }
        }
        else $output = $this->curl ( $requesttype, $url, $parameters, $headers, 'json', $debug );  // If Timeout HAS been set to zero, skip all the caching code, send the request and store it in the $output variable      
        
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