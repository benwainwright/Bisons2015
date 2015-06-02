<?php
foreach ( $data['subscriptions'] as $subscription )
{
	$id = wp_insert_post( $hook_log );
	$mem_form = $mem_form[0]->ID;
	$resource = array ( 'resource_type' => 'subscription', 'resource_content' => $subscription );
	update_post_meta($id, 'resource', $resource);
}