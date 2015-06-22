<?php
function selectOptionFromMeta($user, $field, $test, $label = false) {
	$label = $label ? $label : $test;
	?><option value="<?php echo $test ?>"<?php selected(get_user_meta($user, $field, true), $test) ?>><?php echo $label ?></option> <?php
}