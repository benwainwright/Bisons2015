<?php
if ( isset ( $_POST['fixture_link'] ) ) { update_post_meta($post, 'fixture_id', $_POST['fixture_link'] ); }
if ( isset ( $_POST['event_link'] ) ) { update_post_meta($post, 'event_id', $_POST['event_link'] ); }
if ( isset ( $_POST['setid'] ) ) { update_post_meta($post, 'setid', $_POST['setid'] ); }
