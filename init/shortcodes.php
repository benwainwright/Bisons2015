<?php
function replace_shortcodes($content) {
    $replacement = '<iframe width="560" height="315" src="$1" frameborder="0" allowfullscreen></iframe>';
    $content = preg_replace('/\[youtube\](.+?)\[\/youtube\]/s', $replacement, $content);
    return $content;
}
add_filter( 'the_content', 'replace_shortcodes');
