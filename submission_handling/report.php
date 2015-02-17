<?php
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}

$parentfix = esc_attr($_POST['parent-fixture']);
update_post_meta($post, 'parent-fixture', $parentfix);