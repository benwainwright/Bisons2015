<?php
if (get_the_title($post->post_parent) == "Fixtures") include_once ( 'fixtures-archive.php');
else if (get_the_title($post->post_parent) == "Events") include_once ( 'events-archive.php' );
