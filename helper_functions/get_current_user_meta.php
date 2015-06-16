<?php

function current_user_meta($key, $single = true) {
	return get_user_meta(get_current_user(), $key, $single);
}