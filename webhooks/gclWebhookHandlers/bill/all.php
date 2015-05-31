<?php
foreach ( $data['bills'] as $bill )
{

	// Look for membership forms that match the source id. If not look for forms that match the id
	$mem_form = get_posts ( array ( 'post_type' => 'membership_form',  'meta_key' => 'gcl_sub_id', 'meta_value' => $bill['source_id'] ) ) ;
	$mem_form = $mem_form ? $mem_form : get_posts ( array ( 'post_type' => 'membership_form',  'meta_key' => 'gcl_sub_id', 'meta_value' => $bill['id'] ) );
	$mem_form = $mem_form[0]->ID;


	// Log webhook
	$id = wp_insert_post( $hook_log );
	$resource = array ( 'resource_type' => 'bill', 'resource_content' =>  $bill );
	update_post_meta($id, 'resource', $resource);
	update_post_meta($id, 'source_id', $bill['source_id']);
}
