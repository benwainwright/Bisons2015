#!/usr/bin/php
<?php
include_once( dirname( __FILE__ ) . '/../API_Wrapper/facebook.php' );
include_once( dirname( __FILE__ ) . '/../API_Wrapper/twitter.php' );
include_once( dirname( __FILE__ ) . '/../API_Wrapper/flikr.php' );
include_once( dirname( __FILE__ ) . '/../init/settings.php' );
include_once( dirname( __FILE__ ) . '/../../../../wp-load.php');

/*
 * This file will make the API calls needed to supply the Twitter, Facebook and Flickr feeds
 * It is designed to be run by Cron on a regular interval so that the cache never times out 
 * when the page is actually loaded
 */
 
// Load options from wordpress database
$options = get_option('social-media-settings-page');

// Set Twitter screen name and associated parameters
$twittername = $options['twitter-screenname'];
$parameters = array(
    'count' => $numtweets,
    'trim_user' => 'true',
    'screen_name' => $twittername
);
$numtweets = 3;

// Set Facebook page name
$facebookpage = $options['facebook-page'];

// Set Flickr username
$flickrusername = $options['flickr-username'];

// Call the Facebook and Twitter APIs to get widget contents
$facebook = new Facebook( $GLOBALS['api_settings'] );
$facebook->request ( $facebookpage, 'posts' )->data;
$twitter = new Twitter( $GLOBALS['api_settings'] );
$twitter->request ( 'statuses/user_timeline', $parameters );

// Call the Flickr API and get all the photosets
$flickr = new Flikr ( $GLOBALS['api_settings'] );
$userid = $flickr->peopleFindByUsername ( $flickrusername )->user->nsid;
$photosets = $flickr->photosetsGetList ( $userid, false, false, 'url_q' )->photosets->photoset;

// For each photoset get the photolist and photoset info
foreach ( $photosets as $set )
{
    $photos = $flickr->photosetsGetPhotos ( $set->id , 'url_q,url_z,' )->photoset->photo;
    $photoinfo = $flickr->photosetsGetInfo( $set->id )->photoset;
}