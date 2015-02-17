<?php
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}

update_post_meta($post, 'posname', $_POST['posname']);
update_post_meta($post, 'askme', $_POST['askme']);
update_post_meta($post, 'summary', $_POST['summary']);
update_post_meta($post, 'posresp', $_POST['posresp']);
update_post_meta($post, 'skills', $_POST['skills']);
update_post_meta($post, 'posemail', $_POST['posemail']);
update_post_meta($post, 'posphone', $_POST['posphone']);
update_post_meta($post, 'incumbent', $_POST['incumbent'] );


// If this is a revision, get real post ID
if ( $parent_id = wp_is_post_revision( $post ) ) 
        $post = $parent_id;
    
$postdetails = array (
    'ID' => $post,
    'post_title' => $_POST['posname'],
    'post_name' => $_POST['posname'],
    'post_content' => 'No content'
);

// unhook this function so it doesn't loop infinitely
remove_action('save_post', 'save_custom_post_form');
    
// update the post, which calls save_post again
wp_update_post( $postdetails );

// re-hook this function
add_action('save_post', 'save_custom_post_form');