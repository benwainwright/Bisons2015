

<?php $d = $wp_query->query['bisons_data']; ?>

<header>
	<h2>Subscription Information</h2>
	<ul class="pageMenu">
		<li><a class="fa fa-arrow-circle-left fa-lg" href="<?php echo site_url('players-area') ?>">Player's Area</a></li>
	</ul>
</header>

<p>Please find below details of all payments made via the online membership system. If you have any questions about any of this information, please do not hesitate to contact a member of the committee.</p>
<?php get_template_part( 'snippets/playerPage', 'flashMessages' ) ?>

<form method="POST">
<table class='verticalTable'>
	<tbody>
		<tr>
			<th>Payment Breakdown</th>
			<td>
				<select class="required" name="payMethod" id="payMethod">
					<option value="">Choose...</option>
					<?php selectOptionFromMeta($d['user'],'payMethod', 'dd', 'Monthly Direct Debit') ?>
					<?php selectOptionFromMeta($d['user'],'payMethod', 'sp', 'Single Payment') ?>
				</select>
			</td>
		</tr>
		<tr>
			<th>Type</th>
			<td><?php echo $d['subName'] ?></td>
		</tr>
		<tr>
			<th>Status</th>
			<td><?php echo $d['paymentInfo']['Subscription Status'] ?></td>
		</tr>
		<tr>
			<th>Monthly Fee</th>
			<td><?php echo money_format( '%n', (int) $d['currentMonthlyFee']) ?></td>
		</tr>
		<?php if ($d['paymentInfo']['Total Paid'] > 0 ) : ?>
		<tr>
			<th>Amount Paid</th>
			<td><?php echo money_format( '%n', (int) $d['paymentInfo']['Total Paid']) ?></td>
		</tr>
		<?php endif ?>
		<tr>
			<th>Type</th>
			<td>
				<select name='payWhen'>
					<?php selectOptionFromMeta($d['user'], 'payWhen' , 'first', 'First day of Month') ?>
					<?php selectOptionFromMeta($d['user'], 'payWhen' , 'last', 'Last day of Month') ?>
					<?php selectOptionFromMeta($d['user'], 'payWhen' , 'specificDay', 'Specific day of Month') ?>
					<?php selectOptionFromMeta($d['user'], 'payWhen' , 'specificWeekDay', 'Specific weekday of Month') ?>
				</select>
			</td>
		</tr>

		<tr>
			<th>Day of Month</th>
			<td>
				<select name='dayOfMonth'>
				<?php for ($i = 1; $i <= 31; $i++) : ?>
					<?php selectOptionFromMeta($d['user'], 'dayOfMonth' , $i) ?>
				<?php endfor ?>
				</select>
			</td>
		</tr>

		<tr>
			<th>Which Weekday</th>
			<td>
				<select name='whichWeekDay'>
					<?php selectOptionFromMeta($d['user'], 'whichWeekDay' , 'first', '1st') ?>
					<?php selectOptionFromMeta($d['user'], 'whichWeekDay' , 'second', '2nd') ?>
					<?php selectOptionFromMeta($d['user'], 'whichWeekDay' , 'third', '3rd') ?>
					<?php selectOptionFromMeta($d['user'], 'whichWeekDay' , 'fourth', '4th') ?>
					<?php selectOptionFromMeta($d['user'], 'whichWeekDay' , 'fifth', '5th') ?>
				</select>
				<select name='weekDay'>
					<?php for ($i = 0; $i <= 6; $i++) : ?>
						<?php selectOptionFromMeta($d['user'], 'weekDay' , jddayofweek($i, 1)) ?>
					<?php endfor ?>
				</select>
			</td>
		</tr>



		<tr>
			<th>Next Payment Date</th>
			<td><?php echo $d['nextPaymentDate'] ?></td>
		</tr>
	</tbody>
</table>
<button type="submit">Update</button>
</form>

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

