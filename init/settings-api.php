<?php


/**
 * ***************** Menus **********************
 */



/**
 * ************** Club Settings *****************
 */ 
// Create 'Club Settings' main menu item 
function create_bisons_main_settings_menu ()
{ add_menu_page( 'Club Settings', 'Club Settings', 'manage_options', 'bisons-options', 'bisons_settings_menu_callback'); }
add_action ( 'admin_menu', 'create_bisons_main_settings_menu' );

 // Content for 'Club Settings' page
 function bisons_settings_menu_callback ( )
{
    echo '<div class="wrap">'.
         '<h2>Bisons RFC Settings</h2>'.
         '</div>'; 
}



/**
 * ************** Club Info *****************
 */ 

// Create 'Club Info' submenu 
 function create_club_info_settings_menu()
 {
    add_submenu_page(
        'bisons-options',
        'Club Information',
        'Club Info',
        'manage_options',
        'club-info-settings',
        'club_info_settings_menu_callback'   
    );
 }
add_action ( 'admin_menu', 'create_club_info_settings_menu' );

function club_info_settings_menu_callback ( )
{ 
    echo '<div class="wrap">'.
         '<h2>Club Information</h2>'.
         '<form method="post" action="options.php">';
         
    settings_fields( 'club-info-settings-page' );
    do_settings_sections( 'club-info-settings-page' );
    submit_button();  
         
    echo '</div>';      
}

// Content for 'Club Info' submenu
function club_info_settings_page_callback ( )
{
    echo "<p>Use the fields below to fill out the content that will appear on the 'Club Information' page.</p>";
}

function initialise_club_info_settings()
{
    if( false == get_option( 'club-info-settings-page' ) )
    {
        $options = array ( 
            'welcome-text' => '',
            'welcome-title' => '',
            'home-address' => ''
        );  
        add_option( 'club-info-settings-page', $options);
    }

    add_settings_section(
        'club-information-content',
        'Content',
        'club_info_settings_page_callback',
        'club-info-settings-page'
    );
    add_settings_section(
        'club-information-content',
        'Content',
        'club_info_settings_page_callback',
        'club-info-settings-page'
    );

   add_settings_field(
        'welcome-title', 
        'Welcome Title',
        'singleline_input_field',
        'club-info-settings-page',
        'club-information-content',
        array ( 'welcome-title', 'club-info-settings-page' ) );  

   add_settings_field(
        'welcome-text', 
        'Welcome Text',
        'tinymce',
        'club-info-settings-page',
        'club-information-content',
        array ( 'welcome-text', 'club-info-settings-page' ) );
          

        
   add_settings_field(
        'home-address', 
        'Home venue address?',
        'textarea_field_small',
        'club-info-settings-page',
        'club-information-content',
        array ( 'home-address', 'club-info-settings-page' ) );  


    register_setting (
        'club-info-settings-page',
        'club-info-settings-page'
    );
    
}
add_action ( 'admin_init', 'initialise_club_info_settings' );
 





/**
 * ************** Social Media *****************
 */ 
 
function create_social_media_settings_menu( )
{
    add_submenu_page(
        'bisons-options',
        'Social Media',
        'Social Media',
        'manage_options',
        'social-media-settings',
        'social_media_settings_menu_callback'   
    );
    
}

function social_media_settings_page_callback ()
{
    
}
add_action ( 'admin_menu', 'create_social_media_settings_menu' );

function initialize_social_media_settings( )
{
 if( false == get_option( 'social-media-settings-page' ) )
    {
        $options = array ( 
            'twitter-screenname' => '',
            'facebook-page' => '',
            'flickr-username' => ''
        );  
        add_option( 'social-media-settings-page', $options);
    }  

    function social_media_settings_menu_callback ( )
    { 
        echo '<div class="wrap">'.
             '<h2>Social Media</h2>'.
             '<form method="post" action="options.php">';
             
        settings_fields( 'social-media-settings-page' );
        do_settings_sections( 'social-media-settings-page' );
        submit_button();  
             
        echo '</div>';      
    }


  add_settings_section(
        'social-media-settings-accounts',
        'Accounts',
        'social_media_settings_page_callback',
        'social-media-settings-page'
    );


   add_settings_field(
        'twitter-screenname', 
        'Twitter Screenname',
        'singleline_input_field',
        'social-media-settings-page',
        'social-media-settings-accounts',
        array ( 'twitter-screenname', 'social-media-settings-page', 'This setting determines what Twitter feed is displayed on the built in theme Twitter widget' ) );  
     

   add_settings_field(
        'facebook-page', 
        'Facebook Page',
        'singleline_input_field',
        'social-media-settings-page',
        'social-media-settings-accounts',
        array ( 'facebook-page', 'social-media-settings-page', 'This setting determines what Facebook page is displayed on the built in theme Facebook widget') );  
        
   add_settings_field(
        'flickr-username', 
        'Flickr Username',
        'singleline_input_field',
        'social-media-settings-page',
        'social-media-settings-accounts',
        array ( 'flickr-username', 'social-media-settings-page', 'Any public photosets belonging to this Flickr user will be avaialable on the "photos" page of this website.') );  


    register_setting (
        'social-media-settings-page',
        'social-media-settings-page'
    );

}
add_action ( 'admin_init', 'initialize_social_media_settings' );





/**
 * ********** Email Settings *****************
 */ 

 function create_email_settings_submenu_page( )
 {
    add_submenu_page(
        'email',
        'Email Settings',
        'Settings',
        'manage_options',
        'email-settings',
        'email_settings_menu_callback'   
    );
 }
add_action ( 'admin_menu', 'create_email_settings_submenu_page' );

function email_settings_menu_callback ( )
{ 
    echo '<div class="wrap">'.
         '<h2>Email</h2>'.
         '<form method="post" action="options.php">';
         
    settings_fields( 'email-settings-page' );
    do_settings_sections( 'email-settings-page' );
    submit_button();  
         
    echo '</div>';      
}



function email_settings_contact_us_callback ( )
{ echo "<p>Use this section to customise the emails sent out by the 'contact us' form on the 'about us' page.</p>"; }

function email_settings_new_user_callback () 
{ echo "<p>When a new user is registered, an email is automatically sent out. Use this section to customise it.</p>"; }

function email_settings_general_callback () 
{ echo "<p>These settings apply to all HTML emails sent out by this website.</p>"; }

function email_settings_member_info_callback()
{ echo "<p>These emails go out whenever membership information is added or updated. Use this form to decide who receives these emails and what format they take."; }

function email_settings_guest_nag_callback()
{ echo "<p>Users with the 'guest player' role are sent a an automatic reminder email when their membership is due, and then a followup email at regular emails. Use this section to modify the emails which are sent and the intervals at which they are sent. Note that these emails go out to the relevent players at 7pm every day.</p>"; }


function initialize_email_settings( )
{
    if( false == get_option( 'email-settings-page' ) )
    {
        $options = array ( 
            'contact-us-email' => '',
        'contact-us-template' => '',
        'contact-us-copy-template' => '',
        'new-user-email' => '',
        'new-user-email-replyto' => '',
        'new-user-email-subject' => '',
        );  
        add_option( 'email-settings-page', $options);
    }
    
    add_settings_section(
        'email-settings-general',
        'General settings',
        'email_settings_general_callback',
        'email-settings-page'
    );
    
  add_settings_field(
        'email-memsec',
        'Membership Secretary Name',
        'singleline_input_field',
        'email-settings-page',
        'email-settings-general',
        array( 'email-memsec', 'email-settings-page', 'This name can be used in any of the email templates on this page by using the tag <strong>@@membershipsecretary@@</strong>')
    );
     

    
    add_settings_field(
        'new-user-email-replyto-name',
        '"Reply to" name',
        'singleline_input_field',
        'email-settings-page',
        'email-settings-general',
        array( 'new-user-email-replyto-name', 'email-settings-page', 'Automatic emails sent by this site will appear to have this name in the "from" field.')
    );
    
    add_settings_field(
        'new-user-email-replyto-address',
        '"Reply to" address',
        'singleline_input_field',
        'email-settings-page',
        'email-settings-general',
        array( 'new-user-email-replyto-address', 'email-settings-page', 'Automatic emails sent by this site will appear to have come from this address.')
    );
        
    
    add_settings_section(
        'email-settings-contactus',
        '"Contact us" emails',
        'email_settings_contact_us_callback',
        'email-settings-page'
    );
    
      
    add_settings_field(
        'contact-us-email-query-type-1',
        'Query Type 1',
        'singleline_input_field',
        'email-settings-page',
        'email-settings-contactus',
        array( 'contact-us-email-query-type-1', 'email-settings-page' )
    );
      
    add_settings_field(
        'contact-us-email-address-1',
        'Query Type 1 Address',
        'singleline_input_field',
        'email-settings-page',
        'email-settings-contactus',
        array( 'contact-us-email-address-1', 'email-settings-page' )
    );
    add_settings_field(
        'contact-us-email-query-type-2',
        'Query Type 2',
        'singleline_input_field',
        'email-settings-page',
        'email-settings-contactus',
        array( 'contact-us-email-query-type-2', 'email-settings-page' )
    );
      
    add_settings_field(
        'contact-us-email-address-2',
        'Query Type 2 Address',
        'singleline_input_field',
        'email-settings-page',
        'email-settings-contactus',
        array( 'contact-us-email-address-2', 'email-settings-page' )
    );
    add_settings_field(
        'contact-us-email-query-type-3',
        'Query Type 3',
        'singleline_input_field',
        'email-settings-page',
        'email-settings-contactus',
        array( 'contact-us-email-query-type-3', 'email-settings-page' )
    );
      
    add_settings_field(
        'contact-us-email-address-3',
        'Query Type 3 Address',
        'singleline_input_field',
        'email-settings-page',
        'email-settings-contactus',
        array( 'contact-us-email-address-3', 'email-settings-page' )
    );
      
    add_settings_field(
        'contact-us-email-address-cc',
        'CC All To',
        'singleline_input_field',
        'email-settings-page',
        'email-settings-contactus',
        array( 'contact-us-email-address-cc', 'email-settings-page' )
    );
    

    add_settings_section(
        'email-settings-newuser',
        'New user emails',
        'email_settings_new_user_callback',
        'email-settings-page'
    );
    

    add_settings_section(
        'member-email-settings-section',
        'Member information emails',
        'email_settings_member_info_callback',
        'email-settings-page'
    );
    
    add_settings_field(
        'member-email-send-to-text',
        'Send Member Updates to',
        'singleline_input_field',
        'email-settings-page',
        'member-email-settings-section',
        array( 'member-email-send-to-text', 'email-settings-page' )
    );
    


    add_settings_section(
        'guest-nag-email-settings-section',
        'Guest Member Nag Email',
        'email_settings_guest_nag_callback',
        'email-settings-page'
    );
    
    add_settings_field(
        'guest-nag-email-initial-interval',
        'Initial email interval',
        'number_input_field',
        'email-settings-page',
        'guest-nag-email-settings-section',
        array( 'guest-nag-email-initial-interval', 'email-settings-page', 'The interval (in days) after which the first email will be sent.')
    );
    


    add_settings_field(
        'guest-nag-email-later-interval',
        'Reminder email frequency',
        'number_input_field',
        'email-settings-page',
        'guest-nag-email-settings-section', 
        array( 'guest-nag-email-later-interval', 'email-settings-page', 'The interval (in days) between which guest players will be sent the nag email (after the initial email has been sent).')
    );
    

    register_setting(
        'email-settings-page',
        'email-settings-page'
    ); 

}
add_action ( 'admin_init', 'initialize_email_settings' );





/**
 * ********** Other Settings *****************
 */ 


function create_bisons_other_settings_menu ( )
{ 

    add_submenu_page(
        'bisons-options',
        'Other Settings',
        'Other',
        'manage_options',
        'other-settings',
        'other_settings_menu_callback'   
    );
    
}

add_action ( 'admin_menu', 'create_bisons_other_settings_menu' );

function other_settings_menu_callback ( )
{
    echo '<div class="wrap">'.
         '<h2>Other Settings</h2>'.
         '<form method="post" action="options.php">';
         
    settings_fields( 'other-settings-page' );
    do_settings_sections( 'other-settings-page' );
    submit_button();  
         
    echo '</div>';   
}

function analytics_settings_callback ()
{
    echo "<p>This theme is already configured to work with Google Analytics. All you need to do is put the ID into the box below.</p>";
}

function gocardless_sandbox_settings_callback ()
{
    echo "<p>These API keys are for the GoCardless sandbox.</p>";
}

function gocardless_production_settings_callback ()
{
    echo "<p>These API keys are for the GoCardless PRODUCTION mode.</p>";
}


function gocardless_environment_settings_callback ()
{
    echo "<p>Use the dropdown box below to switch between GoCardless PRODUCTION and SANDBOX modes.</p>";
}

function mandrill_settings_callback()
{
    
}

function initialise_other_settings()
{
   
    if( false == get_option( 'other-settings-page' ) )
    {
        $options = array ( 
            'analytics-id' => '',
            'gcl-sandbox-app-id' => '',
            'gcl-sandbox-app-secret' => '',
            'gcl-sandbox-merchant-id' => '',
            'gcl-sandbox-access-token' => '',
            'gcl-prod-app-id' => '',
            'gcl-prod-app-secret' => '',
            'gcl-prod-merchant-id' => '',
            'gcl-prod-access-token' => '',
            'gcl-environment' => 'Sandbox',
            'mandrill-webhook-key'  => ''

        );  
        add_option( 'other-settings-page', $options);
    }  

    add_settings_section(
        'other-settings-analytics',
        'Analytics Settings',
        'analytics_settings_callback',
        'other-settings-page'
    );

    add_settings_field(
        'analytics-id',
        'Google Analytics ID',
        'singleline_input_field',
        'other-settings-page',
        'other-settings-analytics',
        array( 'analytics-id', 'other-settings-page')
    );
    add_settings_section(
        'other-settings-gocardless-environment',
        'GoCardless Environment',
        'gocardless_environment_settings_callback',
        'other-settings-page'
    );
    add_settings_field(
        'gcl-environment',
        'Environment',
        'dropdown',
        'other-settings-page',
        'other-settings-gocardless-environment',
        array( 'gcl-environment', 'other-settings-page', false, array ('Production', 'Sandbox') )

    );

    add_settings_section(
        'other-settings-gocardless-sandbox',
        'GoCardless Settings (Sandbox)',
        'gocardless_sandbox_settings_callback',
        'other-settings-page'
    );
      
    add_settings_field(
        'gcl-sandbox-app-id',
        'App ID',
        'singleline_input_field',
        'other-settings-page',
        'other-settings-gocardless-sandbox',
        array( 'gcl-sandbox-app-id', 'other-settings-page')
    );
    add_settings_field(
        'gcl-sandbox-app-secret',
        'App Secret',
        'singleline_input_field',
        'other-settings-page',
        'other-settings-gocardless-sandbox',
        array( 'gcl-sandbox-app-secret', 'other-settings-page')
    );
      
    add_settings_field(
        'gcl-sandbox-merchant-id',
        'Merchant ID',
        'singleline_input_field',
        'other-settings-page',
        'other-settings-gocardless-sandbox',
        array( 'gcl-sandbox-merchant-id', 'other-settings-page')
    );
      
      
    add_settings_field(
        'gcl-sandbox-access-token',
        'Access Token',
        'singleline_input_field',
        'other-settings-page',
        'other-settings-gocardless-sandbox',
        array( 'gcl-sandbox-access-token', 'other-settings-page')
    );

      add_settings_section(
        'other-settings-gocardless-prod',
        'GoCardless Settings (Production)',
        'gocardless_production_settings_callback',
        'other-settings-page'
    );

    add_settings_field(
        'gcl-prod-app-id',
        'App ID',
        'singleline_input_field',
        'other-settings-page',
        'other-settings-gocardless-prod',
        array( 'gcl-prod-app-id', 'other-settings-page')
    );
    add_settings_field(
        'gcl-prod-app-secret',
        'App Secret',
        'singleline_input_field',
        'other-settings-page',
        'other-settings-gocardless-prod',
        array( 'gcl-prod-app-secret', 'other-settings-page')
    );
      
    add_settings_field(
        'gcl-prod-merchant-id',
        'Merchant ID',
        'singleline_input_field',
        'other-settings-page',
        'other-settings-gocardless-prod',
        array( 'gcl-prod-merchant-id', 'other-settings-page')
    );
      
      
    add_settings_field(
        'gcl-prod-access-token',
        'Access Token',
        'singleline_input_field',
        'other-settings-page',
        'other-settings-gocardless-prod',
        array( 'gcl-prod-access-token', 'other-settings-page')
    );

      add_settings_section(
        'other-settings-mandrill',
        'Mandrill Settings',
        'mandrill_settings_callback',
        'other-settings-page'
    );
    
    add_settings_field(
        'mandrill-settings-api-key',
        'API Key',
        'singleline_input_field',
        'other-settings-page',
        'other-settings-mandrill',
        array( 'mandrill-settings-api-key', 'other-settings-page')
    );

    add_settings_field(
        'mandrill-settings-webhook-key',
        'Webhook Key',
        'singleline_input_field',
        'other-settings-page',
        'other-settings-mandrill',
        array( 'mandrill-settings-webhook-key', 'other-settings-page')
    );
    
    register_setting(
        'other-settings-page',
        'other-settings-page'
    ); 
}
add_action ( 'admin_init', 'initialise_other_settings' );









/**
 * ********* Field callbacks  *************
 */

function singleline_input_field ( $args )
{
   $options = get_option($args[1]);

    echo "<input name='$args[1]".'['.$args[0].']'."' type='text' id='$args[0]' value='".
         $options[$args[0]].
         "' class='regular-text' />";
    echo $args[2] ? "<p class='description'>$args[2]</p>" : null ;
}

function number_input_field ( $args )
{
   $options = get_option($args[1]);

    echo "<input name='$args[1]".'['.$args[0].']'."' type='number' id='$args[0]' value='".
         $options[$args[0]].
         "' class='small-text' />";
    echo $args[2] ? "<p class='description'>$args[2]</p>" : null ;
}


function textarea_field ( $args )
{

    
    $options = get_option($args[1]);
    echo "<textarea name='$args[1]".'['.$args[0].']'."' id='$args[0] rows='10' cols='50' class='large-text'>".
         $options[$args[0]].
         "</textarea>";
    echo $args[2] ? "<p class='description'>$args[2]</p>" : null ;
}

function textarea_field_small ( $args )
{
    $options = get_option($args[1]);
    echo "<textarea name='$args[1]".'['.$args[0].']'."' id='$args[0] rows='10' cols='50' class='regular-text'>".
         $options[$args[0]].
         "</textarea>";
    echo $args[2] ? "<p class='description'>$args[2]</p>" : null ;
}






function dropdown ( $args )
{
    $options = get_option($args[1]);
    $list_options = $args[3];
    
    echo '<select name='.$args[1].'['.$args[0].']'.' id="'.$args[0].'">';
    foreach ( $list_options as $key => $label ) 
    {
        echo '<option value="'.$key.'"';
        if ( $options[$args[0]] == $key ) echo ' selected="true"'; 
        echo '>'.$label.'</option>';
    }    
    echo '</select>';
    echo $args[2] ? "<p class='description'>$args[2]</p>" : null ;
}

function tinymce ( $args )
{
    $options = get_option($args[1]);
    wp_editor ( $options[$args[0]], $args[0], array ( 'textarea_name' => $args[1].'['.$args[0].']', 'textarea_rows' => 10, 'textarea_cols' => 50, 'teeny' => true, 'quicktags' => true, 'media_buttons' => true ) );
}


function email_settings_callback()
{
    echo "<p>Enter email settings</p>"; 
} 


