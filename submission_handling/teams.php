<?php

update_post_meta($post, 'website', isset ( $_POST['website'] ) ? $_POST['website'] : '');
update_post_meta($post, 'homeaddress', isset ( $_POST['homeaddress'] ) ? $_POST['homeaddress'] : '');
