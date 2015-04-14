<?php
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}

$new = false;

// Clear players_present and replace with new values 
if ( isset ( $_POST['players_present'] ) )
{
	delete_post_meta ( $post, 'players_present');
	 
	foreach ( $_POST['players_present'] as $player)
	{
		if ( $player != 'new' ) add_post_meta ( $post, 'players_present', (int) $player);
		else $new = true;
	}
}


// Clear players_watching and replace with new values 
if ( isset ( $_POST['players_watching'] ) )
{
	delete_post_meta ( $post, 'players_watching');
	 
	foreach ( $_POST['players_watching'] as $player)
	{
		if ( $player != 'new' ) add_post_meta ( $post, 'players_watching', (int) $player);
		else $new = true;
	}
}

// Clear players_coaching and replace with new values 
if ( isset ( $_POST['players_coaching'] ) )
{
	delete_post_meta ( $post, 'players_coaching');
	 
	foreach ( $_POST['players_coaching'] as $player)
	{
		if ( $player != 'new' ) add_post_meta ( $post, 'players_coaching', (int) $player);
		else $new = true;
	}
}

// Save date
$date = strtotime( $_POST['reg-date'] );
update_post_meta($post, 'reg-date', esc_attr($date));


if ( $new ) 
{
	wp_redirect ( admin_url('post.php?post=' . $_POST['post_ID'] . '&action=edit&add_player=true'));
	exit;
}

