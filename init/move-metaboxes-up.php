<?php
// Move all "advanced" metaboxes above the default editor
function move_metas_up() {
    global $post, $wp_meta_boxes;
    do_meta_boxes(get_current_screen(), 'advanced', $post);
    unset($wp_meta_boxes[get_post_type($post)]['advanced']);
}

add_action('edit_form_after_title', 'move_metas_up');