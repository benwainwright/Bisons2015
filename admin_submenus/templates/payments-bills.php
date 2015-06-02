<?php
$billsTable = new GCLBillsTable();
$billsTable->prepare_items();
?>

<div class="wrap">
	<h2>Bills</h2>
	<?php if ( isset ( $_GET['user_id'] ) ) : ?>

		<?php

		$args = array('post_type' => 'GCLBillLog', 'author' => $_GET['user_id'], 'posts_per_page' => -1, 'meta_key' => 'status', 'meta_value' => 'paid');
		$query = new WP_Query($args);

		$numberPayments = 0;
		$totalPaid = 0;
		$lastPayment = '';

		while ($query->have_posts())
		{
			$query->the_post();
			$numberPayments++;
			$totalPaid += get_post_meta(get_the_id(), 'amount', true);
			$lastPayment = get_the_date();
		}
		?>
		<table>
			<body>
			<tr>
				<th>Number of Payments</th>
				<td><?php echo $numberPayments ?></td>
			</tr>
			<tr>
				<th>Total Paid</th>
				<td><?php echo $totalPaid ?></td>
			</tr>
			<tr>
				<th>Last Payment</th>
				<td><?php echo $lastPayment ?></td>
			</tr>
			</body>
		</table>
	<?php endif ?>
	<?php $billsTable->display() ?>
</div>