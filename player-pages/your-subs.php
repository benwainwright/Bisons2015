<?php $d = $wp_query->query['bisons_data']; ?>

<header>
<h2>Subscription Information</h2>
</header>

<p>Please find below details of all payments made via the online membership system. If you have any questions about any of this information, please do not hesitate to contact a member of the committee.</p>
<table class='verticalTable'>
	<tbody>
		<tr>
			<th>Type</th>
			<td><?php echo $d['subName'] ?></td>
		</tr>
		<tr>
			<th>Status</th>
			<td><?php echo $d['paymentInfo']['Subscription Status'] ?></td>
		</tr>
		<?php if ($d['paymentInfo']['Total Paid'] > 0 ) : ?>
		<tr>
			<th>Amount</th>
			<td><?php echo money_format( '%n', (int) $d['paymentInfo']['Total Paid']) ?></td>
		</tr>
		<?php endif ?>
	</tbody>
</table>

<p>Although you have filled in a membership form, it appears that you don't have an active payment subscription. Use the form below to set one up.</p>

<?php $q = $d['query']; if ($q->have_posts()) : ?>

	<h3>Bills</h3>
	<table class='center'>
	<thead>
		<tr>
			<th>Date</th>
			<th>Amount Paid</th>
			<th>Status</th>
		</tr>
	</thead>
	<?php while($q->have_posts()) : $q->the_post(); ?>
	<tbody>
		<tr>
			<td><?php echo get_the_date() ?></td>
			<td><?php echo money_format( '%n', (int) get_post_meta(get_the_id(), 'amount', true) ) ?></td>
			<td><?php echo ucwords( get_post_meta(get_the_id(), 'status', true)) ?></td>
		</tr>
	</tbody>
	<?php endwhile; ?>
	</table>
<?php endif ?>

