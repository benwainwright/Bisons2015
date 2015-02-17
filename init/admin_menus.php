<?php

function Membership_Forms_Table_Add_Options() {
    
    global $membershipFormsTable;

    $option = 'per_page';
    $args = array(
             'label' => 'Membership Forms',
             'default' => 10,
             'option' => 'forms_per_page'
    );
    add_screen_option( $option, $args );
    $membershipFormsTable = new Membership_Forms_Table;
}
function Membership_Forms_Table_Set_Options($status, $option, $value) 
{
  return $value;
}
add_filter('set-screen-option', 'Membership_Forms_Table_Set_Options', 10, 3);


function Awaiting_Membership_Forms_Table_Add_Options() {
  $option = 'per_page';
  $args = array(
         'label' => 'Users',
         'default' => 10,
         'option' => 'awaiting_users_per_page'
         );
  add_screen_option( $option, $args );
}
function Awaiting_Membership_Forms_Table_Set_Options($status, $option, $value) 
{
  return $value;
}
add_filter('set-screen-option', 'Awaiting_Membership_Forms_Table_Set_Options', 10, 3);



function Emails_Table_Add_Options() {
  $option = 'per_page';
  $args = array(
         'label' => 'Emails',
         'default' => 10,
         'option' => 'emails_per_page'
         );
  add_screen_option( $option, $args );
}
function Emails_Table_Set_Options($status, $option, $value) 
{
  return $value;
}
add_filter('set-screen-option', 'Emails_Table_Set_Options', 10, 3);







function add_admin_menus()
{
    
    // Create 'players' submenu
    $membership_form_hook = add_menu_page ( 'Manage Membership Form', 'Players', 'committee_perms', 'players', 'membership_forms_callback', 'dashicons-groups', 8);
    add_action( "load-$membership_form_hook", 'Membership_Forms_Table_Add_Options' );
    add_submenu_page ( 'players', 'Manage Players', 'Manage Players', 'committee_perms', 'players' );

    $awaitingmembershiphook = add_submenu_page ( 'players', 'Awaiting Membership', 'Awaiting Membership', 'committee_perms', 'awaiting-membership', 'awaiting_membership_callback');
    add_action( "load-$awaitingmembershiphook", 'Awaiting_Membership_Forms_Table_Add_Options' );

    add_submenu_page ( 'players', 'Add Player', 'Add Player', 'committee_perms', 'add-player', 'add_player_callback');
    add_submenu_page ( 'players', 'Profiles', 'Profiles', 'committee_perms', 'edit.php?post_type=playerprofile' );
    add_submenu_page ( 'players', 'Pages', 'Pages', 'committee_perms', 'edit.php?post_type=player-page' );
    add_submenu_page ( 'players', 'Page Groups', 'Page Groups', 'committee_perms', 'edit-tags.php?taxonomy=player-page-groups&post_type=player-page' );
    
    


    
    // Move 'results' page into the Fixtures submenu
    add_submenu_page ( 'edit.php?post_type=fixture', 'Results', 'Results', 'committee_perms', 'edit.php?post_type=result' );
    add_submenu_page ( 'edit.php?post_type=fixture', 'New Fixtures', 'New Fixtures', 'committee_perms', 'new-fixtures', 'fixtures_listTable_callback' );
      
    // Create 'committee' submenu
    add_menu_page ( 'Committee', 'Committee', 'committee_perms', 'committee', 'edit.php?post_type=committee-profile', 'dashicons-businessman', 9);
    add_submenu_page ( 'committee', 'Committee Profiles', 'Committee Profiles', 'committee_perms', 'edit.php?post_type=committee-profile');
    add_submenu_page ( 'committee', 'Commmittee Pages', 'Commmittee Pages', 'committee_perms', 'edit.php?post_type=committee-page' );
    
    // Create 'payment' submenu
    add_menu_page ( 'Player Subscriptions', 'Payment', 'committee_perms', 'payment', 'payment_callback', 'dashicons-cart', 11);
    add_submenu_page ( 'payment', 'Player Subscriptions', 'Player Subscriptions', 'committee_perms', 'payment');
    add_submenu_page ( 'payment', 'Payment Event Log', 'Payment Event Log', 'committee_perms', 'webhooklog', 'webhook_log_callback');
    add_submenu_page ( 'payment', 'Edit Fees', 'Edit Fees', 'committee_perms', 'fees', 'fees_callback');

    
    // Create 'email' submenu
    add_menu_page ( 'Email', 'Email', 'committee_perms', 'email', 'email_menu_callback', 'dashicons-email');
    $emailpagehook =  add_submenu_page ( 'email', 'Log', 'Log', 'committee_perms', 'email' );
    add_action( "load-$emailpagehook", 'Emails_Table_Add_Options' );


    
}

function fixtures_listTable_callback()
{ include_once( dirname(__FILE__) . '/../dashboardpages/fixtures.php');  }
                      
function add_player_callback()
{ include_once( dirname(__FILE__) . '/../dashboardpages/add_player.php');  }


function payment_callback()
{ include_once( dirname(__FILE__) . '/../dashboardpages/payment_info.php');  }
   

function fees_callback()
{ include_once( dirname(__FILE__) . '/../dashboardpages/membership_fees.php');  }

function webhook_log_callback()
{ include_once( dirname(__FILE__) . '/../dashboardpages/webhook_log.php');  }

function awaiting_membership_callback()
{ include_once( dirname(__FILE__) . '/../dashboardpages/awaiting_membership_form.php');  }

function membership_forms_callback()
{ include_once( dirname(__FILE__) . '/../dashboardpages/membership_forms.php');  }


function players_menu_callback()
{ include_once( dirname(__FILE__) . '/../dashboardpages/manage_players.php');  }

function committee_menu_callback()
{}

function email_menu_callback()
{ include_once( dirname(__FILE__) . '/../dashboardpages/email_log.php');  }

add_action ('admin_menu', 'add_admin_menus');
