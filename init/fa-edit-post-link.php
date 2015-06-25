<?php
function custom_edit_post_link($output) {

 $output = str_replace('Edit', '<i class="fa fa-pencil-square fa-lg"></i>Edit', $output);

	return $output;
}
add_filter('edit_post_link', 'custom_edit_post_link');