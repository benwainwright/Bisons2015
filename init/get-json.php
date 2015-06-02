<?php
function add_json_query( $vars ){
  $vars[] = "json";
  return $vars;
}
add_filter( 'query_vars', 'add_json_query' );
   
   
$json = filter_input(INPUT_GET, 'json', FILTER_SANITIZE_ENCODED, array( 'flags' => FILTER_FLAG_STRIP_HIGH) );   


if ( file_exists ( __DIR__ . "/../json/$json.php" ) )
{
    include_once(  __DIR__ . "/../json/$json.php" );
}

