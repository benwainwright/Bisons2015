<?php


// Enqueue form Javascript
wp_enqueue_script( 'dynamicforms' );
wp_enqueue_script( 'formvalidation' );

$d = $wp_query->query['bisons_data'];

?>
<?php global $bisonsMembership; if ( $bisonsMembership->goCardlessURL ) : ?>
	<script type='text/javascript'> setTimeout(function(){ document.location = '<?php echo $bisonsMembership->goCardlessURL ?>'; }, 3000); </script>
<?php endif ?>

<header>
	<h2>Subscription Information</h2>
	<?php get_template_part( 'snippets/playerPage', 'menu' ) ?>
</header>
<?php get_template_part( 'snippets/playerPage', 'flashMessages' ) ?>
<p>Please find below details of all payments made via the online membership system. If you have any questions about any
	of this information, please do not hesitate to contact a member of the committee.</p>

<form method="POST">
	<table class='verticalTable selectBoxes'>
		<tbody>
		<tr>
			<th>Payment Breakdown</th>
			<td>
				<select class="required" name="payMethod" id="payMethod">
					<option value="">Choose...</option>
					<?php selectOptionFromMeta( $d['user'], 'payMethod', 'dd', 'Monthly Direct Debit' ) ?>
					<?php selectOptionFromMeta( $d['user'], 'payMethod', 'sp', 'Single Payment' ) ?>
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
			<td><?php echo money_format( '%n', (int) $d['currentMonthlyFee'] ) ?></td>
		</tr>
		<?php if ( $d['paymentInfo']['Total Paid'] > 0 ) : ?>
			<tr>
				<th>Amount Paid</th>
				<td><?php echo money_format( '%n', (int) $d['paymentInfo']['Total Paid'] ) ?></td>
			</tr>
		<?php endif ?>
		<tr>
			<th>Paid On</th>
			<td>
				<select id='payWhen' name='payWhen'>
					<?php selectOptionFromMeta( $d['user'], 'payWhen', 'first', 'First day of Month' ) ?>
					<?php selectOptionFromMeta( $d['user'], 'payWhen', 'last', 'Last day of Month' ) ?>
					<?php selectOptionFromMeta( $d['user'], 'payWhen', 'specificDay', 'Specific day of Month' ) ?>
					<?php selectOptionFromMeta( $d['user'], 'payWhen', 'specificWeekday',
						'Specific weekday of Month' ) ?>
				</select>
			</td>
		</tr>

		<tr id='payDateDiv' <?php if ( get_user_meta($d['user'], 'payWhen', true) != 'specificDay' ) echo ' style="display:none"' ?>>
			<th>Day of Month</th>
			<td>
				<select name='dayOfMonth'>
					<?php for ( $i = 1; $i <= 31; $i ++ ) : ?>
						<?php selectOptionFromMeta( $d['user'], 'dayOfMonth', $i ) ?>
					<?php endfor ?>
				</select>
			</td>
		</tr>
		<tr id='payWeekDayDiv' <?php if ( get_user_meta($d['user'], 'payWhen', true) != 'specificWeekday' ) echo ' style="display:none"' ?>>
			<th>Which Weekday</th>
			<td>
				<select name='whichWeekDay'>
					<?php selectOptionFromMeta( $d['user'], 'whichWeekDay', 'first', '1st' ) ?>
					<?php selectOptionFromMeta( $d['user'], 'whichWeekDay', 'second', '2nd' ) ?>
					<?php selectOptionFromMeta( $d['user'], 'whichWeekDay', 'third', '3rd' ) ?>
					<?php selectOptionFromMeta( $d['user'], 'whichWeekDay', 'fourth', '4th' ) ?>
					<?php selectOptionFromMeta( $d['user'], 'whichWeekDay', 'fifth', '5th' ) ?>
				</select>
				<select name='weekDay'>
					<?php for ( $i = 0; $i <= 6; $i ++ ) : ?>
						<?php selectOptionFromMeta( $d['user'], 'weekDay', jddayofweek( $i, 1 ) ) ?>
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
	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('wordpress_form_submit') ?>" />
	<input type="hidden" name="wp_form_id" value="changeSubscriptionDetails" />
</form>

<p>Although you have filled in a membership form, it appears that you don't have an active payment subscription. Use the
	form below to set one up.</p>

<?php $q = $d['query'];
if ( $q->have_posts() ) : ?>

	<h3>Bills</h3>
	<table class='center'>
		<thead>
		<tr>
			<th>Date</th>
			<th>Amount Paid</th>
			<th>Status</th>
		</tr>
		</thead>
		<?php while ( $q->have_posts() ) : $q->the_post(); ?>
			<tbody>
			<tr>
				<td><?php echo get_the_date() ?></td>
				<td><?php echo money_format( '%n', (int) get_post_meta( get_the_id(), 'amount', true ) ) ?></td>
				<td><?php echo ucwords( get_post_meta( get_the_id(), 'status', true ) ) ?></td>
			</tr>
			</tbody>
		<?php endwhile; ?>
	</table>
<?php endif ?>

