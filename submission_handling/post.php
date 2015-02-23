<?php
if ( isset ( $_POST['fixture-link'] ) ) { update_post_meta($post, 'fixture_id', $_POST['fixture_link'] ); }
if ( isset ( $_POST['fixture-link'] ) ) { update_post_meta($post, 'event_id', $_POST['event_link'] ); }


if ( isset ( $_POST['attr_user'] ) && isset ( $_POST['current_user'] ) ) 
{
	if ( $_POST['attr_user'] != $_POST['current_user'] )
	{
	    
	    wp_update_post( array ( 'ID' => $post, 'post_author' => $_POST['attr_user'] ) );
	}
}
