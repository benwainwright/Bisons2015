<?php
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}


$new = false;

$allPlayers = array();
// Clear players_present and replace with new values 
if ( isset ( $_POST['players_present'] ) )
{
	delete_post_meta ( $post, 'players_present');
	 
	foreach ( $_POST['players_present'] as $player)
	{
		if ( $player != 'new' ) add_post_meta ( $post, 'players_present', (int) $player);
		$allPlayers[] = $player;
	}
}


// Clear players_watching and replace with new values 
if ( isset ( $_POST['players_watching'] ) )
{
	delete_post_meta ( $post, 'players_watching');
	 
	foreach ( $_POST['players_watching'] as $player)
	{
		add_post_meta ( $post, 'players_watching', (int) $player);
		$allPlayers[] = $player;
	}
}

// Clear players_coaching and replace with new values 
if ( isset ( $_POST['players_coaching'] ) )
{
	delete_post_meta ( $post, 'players_coaching');
	 
	foreach ( $_POST['players_coaching'] as $player)
	{
		add_post_meta ( $post, 'players_coaching', (int) $player);
		$allPlayers[] = $player;
	}
}

$users = get_users();

foreach( $users as $user ) {
	if ( ! array_search($user->ID, $allPlayers) ) {
		add_post_meta ( $post, 'players_absent', (int) $user->ID);
	}
}

// Save date
$date = strtotime( $_POST['reg-date'] );
update_post_meta($post, 'reg-date', esc_attr($date));

// Instantiate register in order to save results to cache
new RegisterListTable(true);

if ( $_POST['newPlayerNumber'] > 0 )
{
	wp_redirect ( admin_url('post.php?post=' . $_POST['post_ID'] . '&action=edit&newPlayerNumber=' . $_POST['newPlayerNumber']));
	exit;
}

