<?php
function getMembershipFormFromGCLID($id){
	$mem_form = get_posts (
		array ( 'post_type' => 'membership_form',
		        'meta_key' => 'gcl_sub_id',
		        'meta_value' => $id) );

	return is_a( $mem_form[0], 'WP_Post' ) ?  $mem_form[0] : false;
}