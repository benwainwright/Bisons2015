<?php
$formsTable = new Membership_Forms_Table(); 
$formsTable->prepare_items();

?>
<div class="wrap">
      <h2>Players <a class='add-new-h2' href='<?php echo admin_url( 'admin.php?page=add-player' ) ?>'>Add Player</a></h2>
      <p>The table below contains all the membership forms that have been submitted via the website this season. If it is hard to read because of the number of columns, you can turn some of them off - just click on 'screen options' (look at the top right hand corner) and choose the columns you want to see.</p>
      <?php       
      if ( $_POST ) 
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
    $formsTable->display(); 
      ?>
      </form>
</div>