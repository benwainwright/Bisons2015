<?php
/**
 * Helper function for debugging - echoes BOOM - $debugtext surrounded by <h1> tags.
 * @param $debugtext code you want to be echoed in the BOOM
 */
function boom ( $debugtext ) {
    echo "<h1>BOOM - $debugtext</h1>";
}