<?php
update_post_meta($post, 'fixture_id', $_POST['fixture_link'] );
update_post_meta($post, 'event_id', $_POST['event_link'] );

if ( $_POST['attr_user'] != $_POST['current_user'] )
{
    
    wp_update_post( array ( 'ID' => $post, 'post_author' => $_POST['attr_user'] ) );
}
