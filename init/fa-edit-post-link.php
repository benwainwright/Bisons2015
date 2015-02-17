<?php
function custom_edit_post_link($output) {
 $output = str_replace('class="post-edit-link"', 'class="post-edit-link fa fa-pencil-square fa-lg"', $output);
 return $output;
}
add_filter('edit_post_link', 'custom_edit_post_link');