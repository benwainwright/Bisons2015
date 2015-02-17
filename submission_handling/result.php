<?php
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}


// Save metainfo from custom form, then create a title pulled from the opposing team of the parent fixture


$fixtureoppteam = get_post_meta($_POST['parent-fixture'], 'fixture-opposing-team', true);

// If this is a revision, get real post ID
if ( $parent_id = wp_is_post_revision( $post ) ) 
        $post = $parent_id;
    
$postdetails = array (
    'ID' => $post,
    'post_title' => $fixtureoppteam,
    'post_name' => $fixtureoppteam,
    'post_content' => 'No content'
);

if ($_POST['hide_from_blog'] == 'true')
{
    update_post_meta($post, 'hide_from_blog', 'true');
} else 
{
    delete_post_meta($post, 'hide_from_blog');
}

// unhook this function so it doesn't loop infinitely
remove_action('save_post', 'save_custom_post_form');
    
// update the post, which calls save_post again
wp_update_post( $postdetails );

// re-hook this function
add_action('save_post', 'save_custom_post_form');


update_post_meta($post, 'parent-fixture', esc_attr($_POST['parent-fixture']));
update_post_meta($post, 'our-score', esc_attr( $_POST['our-score']));
update_post_meta($post, 'their-score', esc_attr($_POST['their-score']));