<div class="wrap">
      <h2>Players (Awaiting Club Membership) <a class='add-new-h2' href='<?php echo admin_url( 'admin.php?page=add-player' ) ?>'>Add Player</a></h2>
      <p>The following players have been registered to the site, but have not yet filled in a membership form. You can use the 'bulk actions' dropdown box below to perform a number of tasks for groups of users. Note that resending the welcome email will also reset that user's password.</p>
     <?php       
      if ( $_POST  ) 
      {
          switch ( $_POST['action'] )
          {
              case -1: break;
              case 'bulk_email':        
                include_once( __DIR__ . '/../snippets/bulk_email_form.php');
              break;
                                     
              default:
                if (! isset ( $_POST['confirm_action'] ) )
                    include_once( __DIR__ . '/../snippets/action_are_you_sure.php');
              break; 
          }
      }
      ?>
    <form method="post">
    <?php 
    $playersTable = new Players_No_Mem_form(); 
    $playersTable->prepare_items();
    $playersTable->display(); 
      ?>
   </form>
</div>