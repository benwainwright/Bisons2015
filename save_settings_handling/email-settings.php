<?php
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}


$committee_members = get_users( array('role' => 'committee_member') );
$committee_admin = get_users( array('role' => 'committee_admin' ) );
$committee = array_merge($committee_members, $committee_admin);
$count = count($committee);

for($i = 0; $committee[$i]; $i++) {
    if( $_POST['contact-us-selection'] ==  $committee[$i]->ID ) {
        update_user_meta($committee[$i]->ID, 'get_contact_us_emails', 'true');
    } else {
        update_user_meta($committee[$i]->ID, 'get_contact_us_emails', 'false');
    }
}

