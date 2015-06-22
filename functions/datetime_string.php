<?php
function datetime_string($startdate, $enddate = false, $starttime = false, $endtime = false, $ul = true, $isodate = false) {

    $starttime = $starttime ? date("g:ia", strtotime($starttime)) : false;
    $endtime = $endtime ? date("g:ia", strtotime($endtime)) : false;
	$return = '';
    if ($ul)
        $return = '<ul>';
  
    if ($startdate && $starttime && $endtime && (!$enddate || $enddate == $startdate))
    {
        if ( $isodate) 
            $return .= "<time itemProp=\"startDate\" datetime=$isodate\">";
        
        $return .= "<li class='fa fa-calendar'>$startdate</li><li class='fa fa-clock-o'>$starttime until $endtime</li>";
        
        if ( $isodate) 
            $return .= "</time>";        
    }
        
    
    else if ($startdate && $enddate && !$endtime)
    {
        if ( $isodate) 
            $return .= "<time itemProp=\"startDate\" datetime=$isodate\">";

        $return .= "<li class='fa fa-calendar'>From $startdate until $enddate</li>";
        
        if ( $isodate) 
            $return .= "</time>";        
  

    }
    else if ($startdate && $enddate && $starttime && $endtime)
    {
        
        if ( $isodate) 
            $return .= "<time itemProp=\"startDate\" datetime=$isodate\">";

        $return .= "<li class='fa fa-calendar'>From $starttime on $startdate until $endtime on the $enddate</li>";
                   
        if ( $isodate) 
            $return .= "</time>";        
                   
    }
    
    else if ($startdate && !$enddate)
    {
        
        if ( $isodate) 
            $return .= "<time itemProp=\"startDate\" datetime=$isodate\">";

            $return .= "<li class='fa fa-calendar'>$startdate</li>";
            
        if ( $isodate) 
            $return .= "</time>";        

    }

    if ($ul)
        $return .= '</ul>';

    return $return;
}
