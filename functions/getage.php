<?php
 
 function getage( $dob )
 {
  //explode the date to get month, day and year
  $dob = explode("/", $dob);
  //get age from date or birthdate
  $age = (date("md", date("U", mktime(0, 0, 0, $dob[1], $dob[0], $dob[2]))) > date("md")
    ? ((date("Y") - $dob[2]) - 1)
    : (date("Y") - $dob[2]));
    
    return $age; 

 }