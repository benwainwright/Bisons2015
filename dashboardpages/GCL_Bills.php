<?php
$billsTable = new GCLBillsTable();
$billsTable->prepare_items();

?>
<div class="wrap">
	<h2>Bills</h2>
	<?php $billsTable->display() ?>
</div>