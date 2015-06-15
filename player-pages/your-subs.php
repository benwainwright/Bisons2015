<?php $d = $wp_query->query['bisons_data']; ?>

<header>
	<h2>Subscription Information</h2>
	<ul class="pageMenu">
		<li><a class="fa fa-arrow-circle-left fa-lg" href="<?php echo site_url('players-area') ?>">Player's Area</a></li>
	</ul>
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

		<tr>
			<th>Amount</th>
			<td><?php echo money_format( '%n', (int) $d['paymentInfo']['Total Paid']) ?></td>
		</tr>
	</tbody>
</table>



<?php $q = $d['query']; if ($q->have_posts()) : ?>

	<h3>Bills</h3>
	<table class='center'>
	<thead>
		<tr>
			<th>Date</th>
			<th>Amount</th>
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

