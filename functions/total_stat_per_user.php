<?php
/** 
 * Extracts player statistics from Wordpress database via SQL
 */
function total_stat_per_user( $user, $stat = false )
{
	global $wpdb;
	(int) $user; 
	$queryAppendum = array();
	$player_rows = $wpdb->get_results ( "SELECT `post_id`, `meta_key` FROM `wp_postmeta`   WHERE `meta_key` LIKE 'match_event_player%' AND `meta_value` = $user" );
	
	if ( $player_rows )
	{
		$query = "SELECT `meta_value` FROM `wp_postmeta` WHERE "; 
		foreach ( $player_rows as $row )
		{
			$rowId = $row->post_id;
			$eventCount = explode ( '_', $row->meta_key );
			$eventCount = $eventCount[ sizeOf ($eventCount) - 1];
			$queryAppendum[] = "(`post_id` = $rowId AND `meta_key` = 'match_event_type_$eventCount')";
		}
		$queryAppendum = implode(' OR ', $queryAppendum);
		$query .= $queryAppendum;
		$stat_results = $wpdb->get_results ( $query );
		
		$return = $stat ? '' : array();
		$statcount = 0;
		
		foreach ( $stat_results as $theRow )
		{
			if ( $stat && $stat == $theRow->meta_value ) $statcount++;
			else $return[] = $theRow->meta_value;
		}
		
		return $stat ? $statcount : $return;
	}
	else {
		return false;
	}
}