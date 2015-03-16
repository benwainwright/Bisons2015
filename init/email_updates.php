<?php


if ( ! WP_DEBUG )
{
function email_updates ( $post_id )
{
	if ( get_post_type( $post_id ) == 'fixtures' && get_post_status() == 'publish' && $_POST['email_players'] == 'true' && ! (wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) )
	{
    	// Get users from Wordpress database
    	$users = get_users();
    
    	foreach ( $users as $user )
    	{
    	   // Check user has an associated membership form
    	   $mem_form = new WP_Query ( array (
    	             'post_type' => 'membership_form',
    	             'posts_per_page' => 1,
    	             'orderby'   => 'date',
    	             'order'     => 'ASC',
    	             'author'   => $user->data->ID
    	             ) );
    	             
            while ( $mem_form->have_posts() ) 
            {
                $mem_form->the_post();
                $sendto[] = $user->data->ID;
            }
    	}
	
		switch ( get_post_type ( $post_id ) )
		{
			// If update is new fixture send new fixture email
			case "fixture":
			    
			    $data['date'] = date('jS \o\f F Y', strtotime($_POST['fixture-date']));
			    $data['permalink'] = get_permalink( $post_id );
			    $data['homeaway'] = $_POST['fixture-home-away'];
			    $data['teamlink'] = link_if_avail($_POST['fixture-opposing-team'], $_POST['fixture-opposing-team-website-url']);
			    send_mandrill_template($sendto, 'fixture-update', $data, 'updates' );
			
			break;
			
			// If update is new score, send new score email
			case "result":
			break;
			
			// If update is new event, send new event email
			case "event":
			break;
		}
		
		// Uncheck email updates for this post
	    update_post_meta($post, 'email_players', 'no');
	}
}

add_action ( 'save_post', 'email_updates' );
}