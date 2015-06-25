<?php

function get_profile_url ( $id )
{
	$args = array(
    'author'        =>  $id,
    'post_type'       =>  'playerprofiles',
    'posts_per_page' => 1
    );
	 $posts = get_posts ( $args );
	if ($posts && has_post_thumbnail( $posts[0]->ID )) 
	{
		return get_permalink( $posts[0]->ID );
	}
	else return false; 
	
}
