<?php
function datetime_string($startdate, $enddate = false, $starttime = false, $endtime = false, $ul = true, $isodate = false) {

    $starttime = $starttime ? date("g:ia", strtotime($starttime)) : false;
    $endtime = $endtime ? date("g:ia", strtotime($endtime)) : false;

    if ($ul)
        $return = '<ul>';
  
    if ($startdate && $starttime && $endtime && (!$enddate || $enddate == $startdate))
    {
        if ( $isodate) 
            $return .= "<time itemProp=\"startDate\" datetime=$isodate\">";
        
        $return .= "<li><h5 class='timesmall'>Time</h5>$starttime until $endtime</li><li><h5 class='datesmall'>Date</h5>$startdate</li>";
        
        if ( $isodate) 
            $return .= "</time>";        
    }
        
    
    else if ($startdate && $enddate && !$endtime)
    {
        if ( $isodate) 
            $return .= "<time itemProp=\"startDate\" datetime=$isodate\">";

        $return .= "<li><h5 class='datesmall'>From</h5>$startdate</li><li><h5 class='datesmall'>Until</h5>$enddate</li>";
        
        if ( $isodate) 
            $return .= "</time>";        
  

    }
    else if ($startdate && $enddate && $starttime && $endtime)
    {
        
        if ( $isodate) 
            $return .= "<time itemProp=\"startDate\" datetime=$isodate\">";

        $return .= "<li><h5 class='datesmall'>From</h5>$starttime on $startdate</li><li><h5 class='datesmall'>Until</h5>$endtime on the $enddate</li>";
                   
        if ( $isodate) 
            $return .= "</time>";        
                   
    }
    
    else if ($startdate && !$enddate)
    {
        
        if ( $isodate) 
            $return .= "<time itemProp=\"startDate\" datetime=$isodate\">";

            $return .= "<li><h5 class='datesmall'>Date</h5>$startdate</li>";
            
        if ( $isodate) 
            $return .= "</time>";        

    }

    if ($ul)
        $return .= '</ul>';

    return $return;
}
