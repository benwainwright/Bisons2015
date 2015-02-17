<?php
function fixture_usort_by_date( $a, $b ) {
        return $a['date'] - $b['date'];
}