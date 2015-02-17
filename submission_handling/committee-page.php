<?php
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}

update_post_meta($post, 'description', $_POST['description']);
