<?php
function add_query_vars_filter( $vars ){
  $vars[] = "webhook";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );
   
   
$hook = filter_input(INPUT_GET, 'webhook', FILTER_SANITIZE_ENCODED, array( 'flags' => FILTER_FLAG_STRIP_HIGH) );   


if ( file_exists ( __DIR__ . "/../webhooks/$hook.php" ) )
{
    include_once(  __DIR__ . "/../webhooks/$hook.php" );
}
