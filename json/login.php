<?php

header("content-type: application/json");

$user = get_user_by( 'login', urldecode ( $_GET['u'] ) );

if ( wp_check_password( urldecode ( $_GET['p'] ) , $user->data->user_pass, $user->ID) )
{
    $return = array ( 'result' => 1, $user );    
}
{
    $return = array ( 'result' => 0, $user	);    
}

echo json_encode ( $return );

exit();