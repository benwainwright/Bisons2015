<?php
if ( isset ( $_POST['fixture_link'] ) ) { update_post_meta($post, 'fixture_id', $_POST['fixture_link'] ); }
if ( isset ( $_POST['event_link'] ) ) { update_post_meta($post, 'event_id', $_POST['event_link'] ); }


if ( isset ( $_POST['attr_user'] ) && isset ( $_POST['current_author'] ) ) 
{
	if ( $_POST['attr_user'] != $_POST['current_author'] )
	{  
	    wp_update_post( array ( 'ID' => $post, 'post_author' => $_POST['attr_user'] ) );
	}
}
