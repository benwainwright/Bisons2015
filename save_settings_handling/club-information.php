<?php
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}

update_option('mission_statement', $_POST['mission_statement']);
update_option('who_are_we', $_POST['who_are_we']);
update_option('home_venue_address', $_POST['home_venue_address']);