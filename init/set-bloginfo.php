<?php
// Load blog_info into global variables
$GLOBALS['blog_info']['name'] = get_bloginfo('name');
$GLOBALS['blog_info']['description'] = get_bloginfo('description');
$GLOBALS['blog_info']['template_url'] = get_bloginfo('template_url');
$GLOBALS['blog_info']['url'] = get_bloginfo('url');
$GLOBALS['blog_info']['pingback_url'] = get_bloginfo('pingback_url');
