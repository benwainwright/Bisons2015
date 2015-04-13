<?php
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}

// Iterate through register entries and save them
for ( $i = 0; isset ( $_POST['register_entry_player_' . $i] ); $i++)
{
	if ( $_POST['register_entry_status_' . $i] != '' ) 
	{
		update_post_meta( $post, 'register_entry_status_' . $i, $_POST['register_entry_status_' . $i]);
	}
	else 
	{
		delete_post_meta($post, 'register_entry_status_' . $i);
	}
	if ( $_POST['register_entry_player_' . $i] != '' ) 
	{
		update_post_meta( $post, 'register_entry_player_' . $i, $_POST['register_entry_player_' . $i]);
	}
	else 
	{
		delete_post_meta($post, 'register_entry_player_' . $i);
	}
}

$date = strtotime( $_POST['reg-date'] );
update_post_meta($post, 'reg-date', esc_attr($date));
