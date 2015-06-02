<?php

function mw_logout() {
    $cookiesSet = array_keys($_COOKIE);
    for ($x=0;$x<count($cookiesSet);$x++) setcookie($cookiesSet[$x],"",time()-1);
}

add_action('wp_logout', 'mw_logout');