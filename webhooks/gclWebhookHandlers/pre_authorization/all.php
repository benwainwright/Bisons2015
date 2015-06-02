<?php
foreach ( $data['pre_authorizations'] as $pre_authorization )
{
	$id = wp_insert_post( $hook_log );
	$mem_form = $mem_form[0]->ID;
	$resource = array ( 'resource_type' => 'pre_authorization', 'resource_content' =>  $pre_authorization );
	update_post_meta($id, 'resource', $resource);
}

$id = wp_insert_post( $hook_log );
