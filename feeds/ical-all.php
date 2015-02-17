<?php

// Reformat unix timestamps correctly
function format_timestamp($timestamp) 
{
    return date('Ymd\THis', $timestamp);
}

function format_datestamp($timestamp) 
{
    return date('Ymd', $timestamp);
}

// Merge a time with a unix timestap representing a date to create a new unix timestamp
function timestamp_from_time_date($date, $time)
{
    $day = date('j', (int) $date);
    $month = date('n', (int) $date);
    $year = date('Y', (int) $date);
    $time = explode(':', $time);
    $hour = (int)$time[0];
    $minute = (int)$time[1];
    return mktime($hour, $minute, 0, $month, $day, $year);  
}
function ical_text_escape( $string )
{
    $string = str_replace('\\', '\\\\', $string);
    $string = str_replace(',', '\\,', $string);
    $string = str_replace(';', '\\;', $string);
    $string = str_replace("\n", '\\n', $string);
    $string = str_replace("\r", '', $string);
    return $string;
}

function ical_events_feed() 
{
    
    // Instantiate the events array
    $ical_events = array();
    
    // If the 'of' querystring doesn't specify events only
    if ( $_GET['of'] != 'events')
    {
        // Load fixtures from Wordpress
        $fixtures = new WP_Query( array('post_type' => 'fixture', 
                                        'nopaging'  => 'true', 
                                        'orderby'   => 'meta_value', 
                                        'meta_key'  => 'fixture-date', 
                                        'order'     => 'ASC') );
        
        // Loop through each one
        while ($fixtures -> have_posts()) 
        {
            $fixtures -> the_post();
            
            $kickOff = timestamp_from_time_date(get_post_meta(get_the_id(), 'fixture-date', true), get_post_meta(get_the_id(), 'fixture-kickoff-time', true));;
            $playerArrival = timestamp_from_time_date(get_post_meta(get_the_id(), 'fixture-date', true), get_post_meta(get_the_id(), 'fixture-player-arrival-time', true));
            
            // Does the user want fixtures to be set at arrival time or kickoff time?
            $dtstart = ( $_GET['fixtureTime'] == 'kickoff' ) ? $kickOff : $playerArrival;            
            
            // End time is 100 minutes later
            $dtend = $kickOff + 6000;
    
            // Store in array using the correct format
            $event = array('UID'                        => md5(get_the_id()) . "@bisonsrfc.co.uk",
                           'DTSTART;TZID=Europe/London' => format_timestamp($dtstart), 
                           'DTEND;TZID=Europe/London'   => format_timestamp($dtend),
                           'CREATED'                    => get_the_time('Ymd\THis'), 
                           'SUMMARY'                    => ical_text_escape( html_entity_decode ( get_the_title(), ENT_QUOTES, 'UTF-8' ) ),
                           'DESCRIPTION'                => 'This is a Bisons fixture. Follow the URL for more details, or ask a committee member...',
                           'URL'                        => get_the_permalink()
                           );
    
            // If modified date is different to post date, add to array
            if (get_the_modified_date() != get_the_time())
                $event['LAST-MODIFIED'] = get_the_modified_date('Ymd\THis');
    
            // Add in the location if there is one
            if ($location = get_post_meta(get_the_id(), 'fixture-address', true))
                $event['LOCATION'] = ical_text_escape ($location);
    
            // Add array to master array
            array_push($ical_events, $event);
        }
    }

    if ( $_GET['of'] != 'fixtures')
    {
        // Load all events from Wordpress database
        $events = new WP_Query( array('post_type' => 'event', 
                                      'nopaged'   => 'true', 
                                      'orderby'   => 'meta_value', 
                                      'meta_key'  => 'date', 
                                      'order'     => 'ASC', ));
    
        
        // Loop through each one
        while ($events -> have_posts()) 
        {
            $events -> the_post();
            
            // Get start and date from 
            $startDate = get_post_meta(get_the_id(), 'date', true);
            $endDate = get_post_meta(get_the_id(), 'enddate', true) ? $endDate = get_post_meta(get_the_id(), 'enddate', true) : $startDate;
            
            // All day events
            if ( get_post_meta(get_the_id(), 'allDay', true) )
            {
                $startDate = format_datestamp( $startDate );
                $endDate = format_datestamp( $endDate );
                $microsoft = array (
                    'X-MICROSOFT-CDO-ALLDAYEVENT'           => 'TRUE',
                    'X-MICROSOFT-MSNCALENDAR-ALLDAYEVENT'   => 'TRUE'
                );
            }
            
            // Timed events
            else
            {
                $startDate = format_timestamp ( timestamp_from_time_date($startDate, get_post_meta(get_the_id(), 'time', true) ) );
                $endDate = format_timestamp ( timestamp_from_time_date($endDate, get_post_meta(get_the_id(), 'endtime', true) ) );
            }
        
            // Store in array
            $event = array('UID'                            => md5(get_the_id()) . "@bisonsrfc.co.uk", 
                           'DTSTART;TZID=Europe/London'     => $startDate, 
                           'DTEND;TZID=Europe/London'       => $endDate, 
                           'CREATED'                        => get_the_time('Ymd\THis'), 
                           'SUMMARY'                        => ical_text_escape( html_entity_decode (get_the_title(), ENT_QUOTES, 'UTF-8' ) ), 
                           'DESCRIPTION'                    => ical_text_escape ( html_entity_decode ( wp_strip_all_tags( get_the_content(), ENT_QUOTES, 'UTF-8' ) ) ), 
                           'URL'                            => get_the_permalink()
                           );
                                   
            if ( isset ( $microsoft ) )
                array_merge( $event, $microsoft );
    
            // Add in the location if there is one
            if ($location = get_post_meta(get_the_id(), 'address', true))
                $event['LOCATION'] = ical_text_escape ( $location );
    
            // If modified date is different to post date, add to array
            if (get_the_modified_date() != get_the_time())
                $event['LAST-MODIFIED'] = get_the_modified_date('Ymd\THis');
    
            // Add array to master array
            array_push($ical_events, $event);
        }
    }

    // Start output
    $output = "BEGIN:VCALENDAR\r\n" 
            . "METHOD:PUBLISH\r\n" 
            . "VERSION:2.0\r\n" 
            . "PRODID:-//Bisons RFC.co.uk//Events Calendar//EN\r\n"
            
    // Set timezone
            . "BEGIN:VTIMEZONE\r\n"
            . "TZID:Europe/London\r\n"
            . "BEGIN:DAYLIGHT\r\n"        
            . "TZOFFSETFROM:+0000\r\n"
            . "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU\r\n"
            . "DTSTART:19810329T010000\r\n"
            . "TZNAME:GMT+01:00\r\n"
            . "TZOFFSETTO:+0100\r\n"
            . "END:DAYLIGHT\r\n"
            . "BEGIN:STANDARD\r\n"
            . "TZOFFSETFROM:+0100\r\n"
            . "RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU\r\n"
            . "DTSTART:19961027T020000\r\n"
            . "TZNAME:GMT\r\n"
            . "TZOFFSETTO:+0000\r\n"
            . "END:STANDARD\r\n"
            . "END:VTIMEZONE\r\n";
            
    // Loop through each event
    foreach ($ical_events as $event) 
    {
        // Start event
        $output .= "BEGIN:VEVENT\r\n";

        // Loop through each key
        foreach ($event as $key => $value) 
        {
            if (is_string($key))
                $output .= "$key:$value\r\n";
            else
                $output = "$value\r\n";
        }

        // Close event
        $output .= "END:VEVENT\r\n";
    }

    // Close calendar
    $output .= "END:VCALENDAR\r\n";

    // Set the correct headers
    header('Content-type: text/calendar; charset=utf-8');
    header('Content-Disposition: inline; filename=calendar.ics');

    echo $output;
}

function add_ical_feed() 
{
    add_feed('ical', 'ical_events_feed');
}

add_action('init', 'add_ical_feed');