<?php

/**
 * ********* Field callbacks  *************
 */

function singleline_input_field ( $args )
{
	$options = get_option($args[1]);

	echo "<input name='$args[1]".'['.$args[0].']'."' type='text' id='$args[0]' value='".
	     $options[$args[0]].
	     "' class='regular-text' />";
	echo $args[2] ? "<p class='description'>$args[2]</p>" : null ;
}

function number_input_field ( $args )
{
	$options = get_option($args[1]);

	echo "<input name='$args[1]".'['.$args[0].']'."' type='number' id='$args[0]' value='".
	     $options[$args[0]].
	     "' class='small-text' />";
	echo $args[2] ? "<p class='description'>$args[2]</p>" : null ;
}


function textarea_field ( $args )
{


	$options = get_option($args[1]);
	echo "<textarea name='$args[1]".'['.$args[0].']'."' id='$args[0] rows='10' cols='50' class='large-text'>".
	     $options[$args[0]].
	     "</textarea>";
	echo $args[2] ? "<p class='description'>$args[2]</p>" : null ;
}

function textarea_field_small ( $args )
{
	$options = get_option($args[1]);
	echo "<textarea name='$args[1]".'['.$args[0].']'."' id='$args[0] rows='10' cols='50' class='regular-text'>".
	     $options[$args[0]].
	     "</textarea>";
	echo $args[2] ? "<p class='description'>$args[2]</p>" : null ;
}






function dropdown ( $args )
{
	$options = get_option($args[1]);
	$list_options = $args[3];

	echo '<select name='.$args[1].'['.$args[0].']'.' id="'.$args[0].'">';
	foreach ( $list_options as $key => $label )
	{
		echo '<option value="'.$key.'"';
		if ( $options[$args[0]] == $key ) echo ' selected="true"';
		echo '>'.$label.'</option>';
	}
	echo '</select>';
	echo $args[2] ? "<p class='description'>$args[2]</p>" : null ;
}

function tinymce ( $args )
{
	$options = get_option($args[1]);
	wp_editor ( $options[$args[0]], $args[0], array ( 'textarea_name' => $args[1].'['.$args[0].']', 'textarea_rows' => 10, 'textarea_cols' => 50, 'teeny' => true, 'quicktags' => true, 'media_buttons' => true ) );
}


function email_settings_callback()
{
	echo "<p>Enter email settings</p>";
}
