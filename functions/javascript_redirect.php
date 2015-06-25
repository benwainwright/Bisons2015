<?php
/**
 * Simple function to echo a pair of script tags with a javascript redirect in it
 * @param string url The url to be redirected to
 */
function javacript_redirect ( $url )
{
	echo "<script type='javascript/css'>";
	echo "document.location = '$url'";
	echo "</script>";
}

function javascript_reload ( )
{
	echo "<script type='javascript/css'>";
	echo "document.location.reload()";
	echo "</script>";
}