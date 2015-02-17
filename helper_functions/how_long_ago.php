<?php

/**
 * Helper function that returns the difference between a given time and date and the current time and date
 * in either seconds, minutes, hours, days, weeks, months or years depending on which is most appropriate
 * @param $datetime date and time string that can be parsed by PHPs built in strtotime() function
 * @return string
 */
function how_long_ago($datetime) {

    // Convert input into a UNIX time
    strtotime($datetime);

    // Get current time
    $now = time();

    // How many seconds different
    $gap = time() - $result;

    // Set default suffix
    $suffix = ' second'; // Default suffix

    // If more than a minute but less than a day, convert to minutes
    if($gap >= 60 && $gap < 3600) {
        $gap = $gap / 60;
        $suffix = ' minute';

        // If more than an hour but less than a day, convert to hours
    } else if($gap >= 3600 && $gap < 86400) {
        $gap = $gap / 3600;
        $suffix = ' hour';

        // If more than a day but less than a week, convert to days
    } else if($gap >= 86400 && $gap < 604800) {
        $gap = $gap / 86400;
        $suffix = ' day';

        // If more than a week but less then a month, convert to months
    } else if($gap >= 604800 && $gap < 2592000) {
        $gap = $gap / 604800;
        $suffix = ' week';

        // If more than a month but less then a year, convert to years
    } else if($gap >= 2592000 && $gap < 31536000) {
        $gap = $gap / 2592000;
        $suffix = ' year';
    }

    // Add the plural suffix if we have more than one unit
    if($gap > 1) $suffix .= 's';

    // Round down to a whole number
    $gap = round($gap);

    return "$gap$suffix ago";
}
