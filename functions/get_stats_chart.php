<?php

function get_stats_chart ( $stat, $truncate = 10, $season = false )
{
	global $wpdb;
	$stat_rows = $wpdb->get_results ( "SELECT `post_id`, `meta_key` FROM `wp_postmeta`   WHERE `meta_key` LIKE 'match_event_type%' AND `meta_value` = '$stat'" );
	
	if ( $stat_rows )
	{
		$query = "SELECT `meta_value` FROM `wp_postmeta` WHERE "; 
		foreach ( $stat_rows as $row )
		{
			$rowId = $row->post_id;
			$eventCount = explode ( '_', $row->meta_key );
			$eventCount = $eventCount[ sizeOf ($eventCount) - 1];
			$queryAppendum[] = "(`post_id` = $rowId AND `meta_key` = 'match_event_player_$eventCount')";
		}
		$queryAppendum = implode(' OR ', $queryAppendum);
		$query .= $queryAppendum;
		$stat_results = $wpdb->get_results ( $query );
		
		
		$return = array();
		foreach ($stat_results as $result)
		{
			$return[] = $result->meta_value;
		}
		
		$return = array_count_values( $return );
		arsort ($return);
		return $return;

	} 
	else 
	{
		return false;
	}
}
