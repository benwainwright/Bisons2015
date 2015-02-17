<?php

function add_custom_rewrites ( )
{
    add_rewrite_tag( '%gallery%', '([^&]+)');
    add_rewrite_rule ( '^photos\/(\d+)\/?$', 'index.php?pagename=photoalbums&gallery=$matches[1]', 'top' );
	add_rewrite_rule ( '^calendar\.ics$', 'index.php?feed=ical', 'top' );
}
add_action( 'init', 'add_custom_rewrites' );

function flush_rewrites ( )
{
	flush_rewrite_rules();
}
add_action ('after_switch_theme', 'flush_rewrites');