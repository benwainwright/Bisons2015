<?php 
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}



// save metainformation from custom form, then create a title from the name of the opposing team
$fixturedate = strtotime( $_POST['fixture-date'] );
update_post_meta($post, 'fixture-date', $fixturedate);

$fixturedate = strtotime( $_POST['text-date'] );
update_post_meta($post, 'text-date', $fixturedate);


$fixturekickoff = esc_attr( $_POST['fixture-kickoff-time'] );    
update_post_meta($post, 'fixture-kickoff-time', $fixturekickoff);

$fixtureoppteam = esc_attr( $_POST['fixture-opposing-team'] );
update_post_meta($post, 'fixture-opposing-team', $fixtureoppteam);

$fixturepat = esc_attr( $_POST['fixture-player-arrival-time'] );
update_post_meta($post, 'fixture-player-arrival-time', $fixturepat);

$fixtureoppteamurl = esc_attr( $_POST['fixture-opposing-team-website-url'] );       
update_post_meta($post, 'fixture-opposing-team-website-url', $fixtureoppteamurl);

$fixtureaddy = esc_attr( $_POST['fixture-address'] );
update_post_meta($post, 'fixture-address', $fixtureaddy);

$fixface = esc_attr( $_POST['fixture-facebook-event'] );
update_post_meta($post, 'fixture-facebook-event', $fixface);

update_post_meta($post, 'is_post_revision', wp_is_post_revision( $post ));
update_post_meta($post, '$post', $post);

if ($_POST['hide_from_blog'] == 'true')
{
    update_post_meta($post, 'hide_from_blog', 'true');
} else 
{
    delete_post_meta($post, 'hide_from_blog');
}

if ($_POST['email_players'] == 'true')
{
    update_post_meta($post, 'email_players', 'yes');
} else 
{
    update_post_meta($post, 'email_players', 'no');
}


update_post_meta ( $post, 'fixture-home-away', esc_attr( $_POST['fixture-home-away']) );

// If this is a revision, get real post ID
if ( $parent_id = wp_is_post_revision( $post ) ) 
        $post = $parent_id;
    
$postdetails = array (
    'ID' => $post,
    'post_title' => $fixtureoppteam,
    'post_name' => $fixtureoppteam,
    'post_content' => 'No content'
);

// unhook this function so it doesn't loop infinitely
remove_action('save_post', 'save_custom_post_form');
    
// update the post, which calls save_post again
wp_update_post( $postdetails );

// re-hook this function
add_action('save_post', 'save_custom_post_form');
