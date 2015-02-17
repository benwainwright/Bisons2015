  <form method="post">
      <?php foreach ($_POST as $key => $value ) : ?>
        <input type='hidden' name='<?php echo $key ?>' value='<?php echo is_array ( $value ) ?  htmlentities( serialize( $value ) ) : $value  ?>' />
      <?php endforeach ?>
        <p>Are you sure you want to perform this action?</p>
        <button class='button' type='submit' name='confirm_action' value='true'>Yes</button>
        <button class='button' type='submit' name='confirm_action' value='false'>No</button>
  </form>   