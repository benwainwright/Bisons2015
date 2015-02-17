<?php
/**
 * Helper function - takes a date and returns a date reformatted by PHP date()
 * @param $date A date that can be parsed by PHP's built in strtotime() function
 * @param $format A format that can be understood by PHP's built in date() function
 * @return bool|int|string
 */
function reformat_date( $date, $format ) {
    $date = strtotime($date);
    $date = date($format, $date);
    return $date;
}