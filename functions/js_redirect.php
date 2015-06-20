<?php
/**
 * includes the Javascript redirect js/php file using WP enqueue functions
 * @param string url Url to be used in redirect
 * @param int time Time in milliseconds before performing redirect
 */
function wp_js_redirect( $url, $time = 3000)
{
    $time = (int) $time;
    wp_register_script('js_redirect', get_template_directory_uri() . '/scripts/javascript_redirect.php?url=' . urlencode( $url ) .'&time=' . $time, null, '1.0.0', true); 
    wp_enqueue_script('js_redirect');
}