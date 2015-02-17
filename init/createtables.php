<?php
function create_photos_table ( )
{
	global $wpdb;
	$table_name = $wpdb->prefix . "flickr_galleries";
	
	$sql = "CREATE TABLE `$table_name` (".
  				"`id` int(11) unsigned NOT NULL AUTO_INCREMENT,".
  				"`gallery_id` int(20) unsigned NOT NULL,".
  				"`slug` varchar(255) NOT NULL DEFAULT '',".
  				"PRIMARY KEY (`id`)".
			") ENGINE=InnoDB DEFAULT CHARSET=latin1;";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	dbDelta ( $sql );
}

add_action ( 'after_switch_theme', 'create_photos_table' );

function drop_photos_table ( )
{
	global $wpdb;
	$table_name = $wpdb->prefix . "flickr_galleries";
	$wpdb->query ( "DROP TABLE IF EXISTS $table_name" );
}

add_action ( 'switch_theme', 'drop_photos_table' );
