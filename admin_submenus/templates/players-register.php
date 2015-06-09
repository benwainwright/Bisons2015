<?php
$register = new RegisterListTable(false, array ( 'screen' => 'registerList', 'singular' => 'register', 'plural' => 'registers' ));
$register->prepare_items();

?>
<div class="wrap">
	<h2>Register</h2>

	<?php $register->display() ?>
</div>