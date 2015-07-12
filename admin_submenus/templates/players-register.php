<?php
$register = new RegisterListTable(false, array ( 'screen' => 'registerList', 'singular' => 'register', 'plural' => 'registers' ));
$register->prepare_items();

?>
<div class="wrap">
	<h2>Register  <a class='add-new-h2' href='<?php echo admin_url( 'post-new.php?post_type=attendance_registers' ) ?>'>Record Attendance</a></h2>

	<?php $register->display() ?>
</div>