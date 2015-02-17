<?php

include_once('api-wrapper.php');

/**
 * Class Flikr
 *
 * This class extends the API wrapper and allows you to use Flikr API methods
 * @package API_Wrapper
 * @subpackage Flikr
 * @see API_Wrapper
 */

class Flikr extends API_Wrapper {
    
    function __construct( $settings, $response_format = 'json', $cachedir = false, $default_cache_timeout = 1800 ) {
        $this->keys['key'] = $settings['flikr']['key'];
        $this->keys['secret'] = $settings['flikr']['secret'];;
        $this->urls = $settings['flikr']['urls'];
        $this->endpoint = $this->urls['endpoint'];
        parent::__construct ( $response_format, $cachedir, $default_cache_timeout );
        return true;
    }
    
    public function peopleFindByUsername ( $username )
    {
        $parameters['username'] = $username;
        return $this->request ( 'flickr.people.findByUsername', $parameters );
    }
    
    public function peopleGetInfo ( $user_id )
    {
        $parameters = array ( 'user_id' => $user_id );
        return $this->request ( 'flickr.people.getInfo', $parameters );
    }
    
    public function photosetsGetPhotos ( $photoset_id, $extras = false, $privacy_filter = false, $per_page = false, $page = false, $media = false )
    {
        $parameters = array ();
        $parameters['photoset_id'] = $photoset_id;
        if ( $extras ) $parameters[ 'extras' ] = $extras;
        if ( $privacy_filter ) $parameters[ 'privacy_filter' ] = $privacy_filter;
        if ( $per_page ) $parameters[ 'per_page' ] = $per_page;
        if ( $page ) $parameters[ 'page' ] = $page;
        if ( $media ) $parameters[ 'media' ] = $media;
        
        return $this->request ( 'flickr.photosets.getPhotos', $parameters );

    }
    
    public function photosetsGetInfo ( $photoset_id )
    {
        $parameters = array ( 'photoset_id' => $photoset_id );
        return $this->request ( 'flickr.photosets.getInfo', $parameters );
    }
    
    public function photosGetSizes ( $photo_id )
    {
        $parameters = array ( 'photo_id' => $photo_id );
        return $this->request ( 'flickr.photos.getSizes', $parameters );
    }
    
    public function photosetsGetList ( $user_id = false, $page = false, $per_page = false, $primary_photo_extras = false )
    {
        $parameters = array();
        
        if ( $user_id ) $parameters[ 'user_id' ] = $user_id;
        if ( $page ) $parameters[ 'page' ] = $page;
        if ( $per_page ) $parameters[ 'per_page' ] = $per_page;
        if ( $primary_photo_extras ) $parameters[ 'primary_photo_extras' ] = $primary_photo_extras;
        return $this->request ( 'flickr.photosets.getList', $parameters );
    }
    
    private function request ( $method, $parameters )
    {
        $parameters['method'] = $method;
        $parameters['api_key'] = $this->keys['key'];
        $parameters['format'] = 'json';
        $this->raw_response  = $this->send_curl_request ( "GET", $this->endpoint, $parameters,  false, 0, false, true, 'text' );
        $this->response = json_decode ( preg_replace("/^jsonFlickrApi\((.*)\)$/", "$1", $this->raw_response ) );
        return $this->response;
    }
}

