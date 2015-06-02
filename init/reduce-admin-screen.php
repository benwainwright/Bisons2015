<?php
function remove_menus(){
  
  /*
  if ( ! current_user_can ( 'modify_themes' ) )
  {
      remove_menu_page( 'themes.php' );                 
  }
  
  if ( ! current_user_can ( 'modify_plugins' ) )
  {
      remove_menu_page( 'plugins.php' );                
  }
  
  if ( ! current_user_can ( 'modify_users' ) )
  {
      remove_menu_page( 'users.php' );                  
  }
  
  if ( ! current_user_can ( 'modify_tools' ) )
  {
      remove_menu_page( 'tools.php' );                  
  }
  
  if ( ! current_user_can ( 'modify_options' ) )
  {
      remove_menu_page( 'options-general.php' );
  } */
}
add_action( 'admin_menu', 'remove_menus' );
