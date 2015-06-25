<?php
function pence_to_pounds ( $pence, $poundsign = true )
{
    $newpence = substr ($pence, -2);
    $pounds = substr($pence, 0, -2) ? substr($pence, 0, -2) : "0";
    $pounds = $pounds ? $pounds : "0";
    $newpence = $newpence ? $newpence : "00";
    return $poundsign ? "£$pounds.$newpence" : "$pounds.$newpence" ;
}