<?php
function add_query_vars_filter_ajax( $vars ){
  $vars[] = "ajax";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter_ajax' );
   
   
$ajax = filter_input(INPUT_GET, 'ajax', FILTER_SANITIZE_ENCODED, array( 'flags' => FILTER_FLAG_STRIP_HIGH) );   


if ( file_exists ( __DIR__ . "/../ajax/$ajax.php" ) )
{
	header('Content-Type: application/json');
    include_once(  __DIR__ . "/../ajax/$ajax.php" );
	exit();
}
