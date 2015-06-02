<?php
function create_team_roles() {
    include('permissions.php');
    add_role(
        'committee_member',
        'Committee Member',
        $committee_perms
        );
     add_role(
        'committee_admin',
        'Committee Administrator',
        $committee_admin_perms
        );
        
    add_role(
        'player',
        'Player',
        $player_perms
        );
        
    add_role(
        'guest_player',
        'Guest Player',
        $guest_player_perms
        );
        
    $admin = get_role( 'administrator' );
    $admin->add_cap( 'committee_perms' );
    $admin->add_cap( 'advanced_posting_layout' );
    $admin->add_cap( 'manage_bisons_settings' );  
    $admin->add_cap( 'view_players_area' );  
    $admin->add_cap( 'view_committee_area' );  
    $admin->add_cap( 'use_wiki' );
    $admin->add_cap( 'see_dashboard' );  
    $admin->add_cap( 'attribute_post' );  
}

/*
 * Remove custom roles in the database on deactivation
 */
function remove_team_roles() {
    remove_role('committee_member');
    remove_role('committee_admin');
    remove_role('player');
    remove_role('guest_player');
    
    $admin = get_role( 'administrator' );
    $admin->remove_cap( 'committee_perms' );
    $admin->remove_cap( 'advanced_posting_layout' );
    $admin->remove_cap( 'manage_bisons_settings' ); 
    $admin->remove_cap( 'view_players_area' ); 
    $admin->remove_cap( 'view_committee_area' );
    $admin->remove_cap( 'use_wiki' );
    $admin->remove_cap( 'see_dashboard' );
    $admin->remove_cap( 'attribute_post' );


}
add_action('after_switch_theme', 'create_team_roles');
add_action('switch_theme', 'remove_team_roles');