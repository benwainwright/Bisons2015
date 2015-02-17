<?php
/**
 * Helper function - If you pass this function $text string only, it will output it. If you pass it a URL, it will output it as a link
 * This reduces typing in templates because it means you don't have to keep testing to see if a link is available,
 * you can just pass the link variable
 * @param string $text A string of text to be returned
 * @param string $url A URL
 * @return string
 */
function link_if_avail($text, $url = false) {
    if($url) {
        return "<a href='$url' title='$text'>$text</a>";
    } else {
        return $text;
    }
}



// Pretty much the same as above. Will remove it at some point, but need to find all the references to it and change to the above function
function team_link($team_name, $url = false) {
    if($url) {
        return "<a href='$url' title='$team_name'>$team_name</a>";
    } else {
        return $team_name;
    }
}
