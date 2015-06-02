<div class="wrap">
	<?php if (isset($_GET['user_id'])) : ?>


		<h2>Member Details</h2>
		<p>Information below is held on behalf of the club and should remain confidential.</p>
		<?php

		// Get bills table
		$billsTable = new GCLBillsTable(array ( 'screen' => 'playerDetails', 'singular' => 'player', 'plural' => 'players' ));
		$billsTable->prepare_items();


		// Get membership form
		$membership_form = new WP_Query ( array(
			'post_type'      => 'membership_form',
			'posts_per_page' => 1,
			'orderby'        => 'date',
			'order'          => 'ASC',
			'author'         => $_GET['user_id']
		) );

		if ( $membership_form->have_posts() ) {
			$membership_form->the_post();

			$streetAddy = array();
			if (get_post_meta( get_the_id(), 'streetaddyl1', true)) $streetAddy[] = get_post_meta( get_the_id(), 'streetaddyl1', true);
			if (get_post_meta( get_the_id(), 'streetaddyl2', true)) $streetAddy[] = get_post_meta( get_the_id(), 'streetaddyl2', true);
			if (get_post_meta( get_the_id(), 'streetaddytown', true)) $streetAddy[] = get_post_meta( get_the_id(), 'streetaddytown', true);
			if (get_post_meta( get_the_id(), 'postcode', true)) $streetAddy[] = get_post_meta( get_the_id(), 'postcode', true);

			$dob = get_post_meta( get_the_id(), 'dob-month', true ) . '/' .
			       get_post_meta( get_the_id(), 'dob-day', true ) . '/' .
			       get_post_meta( get_the_id(), 'dob-year', true );


			for ($i = 1; $i <= get_post_meta( get_the_id(), 'condsdisablities_rowcount', true ); $i++) {
				$medCons[]	= array(
					'Condition'      => get_post_meta( get_the_id(), "condsdisablities_name_row$i", true ),
					'Medication'      => get_post_meta( get_the_id(), "condsdisablities_drugname_row$i", true ),
					'Dose' => get_post_meta( get_the_id(), "condsdisablities_drugdose_freq_row$i", true )
				);
			}

			for ($i = 1; $i <= get_post_meta( get_the_id(), 'allergies_rowcount', true ); $i++) {
				$allergies[]	= array(
					'Condition'      => get_post_meta( get_the_id(), "allergies_name_row$i", true ),
					'Medication'      => get_post_meta( get_the_id(), "allergies_drugname_row$i", true ),
					'Dose' =>       get_post_meta( get_the_id(), "allergies_drugdose_freq_row$i", true )
				);
			}

			for ($i = 1; $i <= get_post_meta( get_the_id(), 'allergies_rowcount', true ); $i++) {
				$injuries[]	= array(
					'What'        => get_post_meta( get_the_id(), "injuries_name_row$i", true ),
					'When'        => get_post_meta( get_the_id(), "injuries_when_row$i", true ),
					'Treatment'   => get_post_meta( get_the_id(), "injuries_treatmentreceived_row$i", true ),
					'Who Treated' => get_post_meta( get_the_id(), "injuries_who_row$i", true ),
					'Status'      => get_post_meta( get_the_id(), "injuries_status_row$i", true ),
				);
			}

			$personalDetails = array(
				'Name'              => get_post_meta( get_the_id(), 'firstname', true ) . ' ' . get_post_meta( get_the_id(), 'surname', true ),
				'Email'             => get_post_meta( get_the_id(), 'email_addy', true ),
				'Gender'            => get_post_meta( get_the_id(), 'othergender', true ) ? get_post_meta( get_the_id(), 'othergender', true ) : get_post_meta( get_the_id(), 'gender', true ),
				'Date of Birth'     => reformat_date($dob, 'jS \of F Y'),
				'Age'               => getage($dob),
				'Contact Number'    => get_post_meta( get_the_id(), 'contact_number', true),
				'Street Address'    => implode('<br />', $streetAddy)
			);

			$otherInfo = array(
				'Other sports'               =>  get_post_meta( get_the_id(), 'othersports', true ),
				'Training hours a week'      =>  get_post_meta( get_the_id(), 'hoursaweektrain', true ),
				'Previously played at'       =>  get_post_meta( get_the_id(), 'playedbefore', true ) == 'Yes' ? get_post_meta( get_the_id(), 'whereandseasons', true ) : 'No',
				'Height'                     =>  get_post_meta( get_the_id(), 'height', true ),
				'Weight'                     =>  get_post_meta( get_the_id(), 'weight', true ),
				'Referral Source'            =>  get_post_meta( get_the_id(), 'howdidyouhear', true ),
				'Skills'                     =>  get_post_meta( get_the_id(), 'whatcanyoubring', true ),
			);

			$gcl_sub_id = get_post_meta( get_the_id(), 'gcl_sub_id', true );

			$nokAddy = array();
			if ( get_post_meta( get_the_id(), 'nokstreetaddy', true ) ) $nokAddy[] = get_post_meta( get_the_id(), 'nokstreetaddy', true );
			if ( get_post_meta( get_the_id(), 'nokpostcode', true ) ) $nokAddy[] = get_post_meta( get_the_id(), 'nokpostcode', true );

			$nextOfKin = array(
				'Name'              => get_post_meta( get_the_id(), 'nokfirstname', true ) . ' ' . get_post_meta( get_the_id(), 'noksurname', true ),
				'Relationship'      => get_post_meta( get_the_id(), 'nokrelationship', true ),
				'Contact Number'    => get_post_meta( get_the_id(), 'nokcontactnumber', true ),
				'Address'           => get_post_meta( get_the_id(), 'sameaddress', true ) == 'Yes' ? $personalDetails['Street Address'] : implode('<br />', $nokAddy)
			);

			global $payment_statuses;

			$paymentInfo = array(
				'Subscription Status'        =>  $payment_statuses[get_post_meta( get_the_id(), 'payment_status', true)][0],
				'Membership Type'         => get_post_meta( get_the_id(), 'joiningas', true),
				'Number of Payments'    =>  0,
				'Total Paid'            =>  0,
				'Total Refunded'        =>  0,
				'Last Payment'           => 0
			);



		}

		else {

			$user = get_user_by('id', $_GET['user_id']);

			$personalDetails = array(
				'Name'              => $user->user_firstname . ' ' . $user->user_lastname,
				'Email'             => $user->data->user_email,
			);

			$paymentInfo['Membership Status'] = 'Not Joined';

			$gcl_sub_id = false;
		}


		?>
		<?php if (isset($_GET['addBills']) ): ?>
			<form method="POST">
				<?php for ($i = 0; $i < $_GET['addBills']; $i++) { ?>
					<div>
					<?php
				if($_POST) {
					$date = date( 'Y-m-d H:i:s', strtotime($_POST['date_' . $i]));

					// Create webhook log
					$hook_log = array(
						'post_status' => 'publish',
						'post_date' => $date,
						'post_type' => 'GCLBillLog'
					);


					$hook_log['post_author'] = $_GET['user_id'];

					// Log webhook
					$id = wp_insert_post( $hook_log );

					$mem_form = getMembershipFormFromGCLID($gcl_sub_id );
					$mem_form = $mem_form ? $mem_form :  getMembershipFormFromGCLID($gcl_sub_id );
					$mem_form = $mem_form->ID;

					new dBug($id);
					update_post_meta($id, 'membership_form_id', $mem_form);
					update_post_meta($id, 'source_id', $gcl_sub_id);
					update_post_meta($id, 'status', 'paid');
					update_post_meta($id, 'amount', (double) $_POST['amount_' . $i ]);
					update_post_meta($id, 'amount_minus_fees', (0.99 * (double) $_POST['amount_' . $i]));
					update_post_meta($id, 'source_type', 'subscription');

				} ?>
					<input type="date" name="date_<?php echo $i ?>" />
					<input type="text" name="amount_<?php echo $i ?>" />
					</div>
				<?php } ?>
				<button type="submit">Submit</button>
			</form>
		<?php endif ?>
		<div class="wrap">
			<h2><?php echo $name ?></h2>
			<h3>Personal Details</h3>
			<table class='widefat memberData'>
				<tbody>
				<?php foreach ( $personalDetails as $label => $data ) : ?>
					<?php if ($data) : ?>
						<tr>
							<th><?php echo $label?></th>
							<th><?php echo $data?></th>
						</tr>
					<?php endif ?>
				<?php endforeach ?>
				</tbody>
			</table>
				<?php
					$args = array('post_type' => 'GCLBillLog', 'author' => $_GET['user_id'], 'posts_per_page' => -1);
					$query = new WP_Query($args);


					setlocale(LC_MONETARY, 'en_GB.UTF-8');

					while ($query->have_posts())
					{

						$currentBiggest = 0;
						$query->the_post();

						switch ( get_post_meta(get_the_id(), 'status', true ) ) {
							case "paid":

								$paymentInfo['Number of Payments']++;
								$paymentInfo['Total Paid'] += get_post_meta(get_the_id(), 'amount', true);

								if (get_the_date(('U')) > $paymentInfo['Last Payment']) {
									$paymentInfo['Last Payment'] = get_the_date('U');
								}

								break;

							case "failed":
								break;

							case "refunded": case "chargedback":
								$paymentInfo['Total Refunded'] += get_post_meta(get_the_id(), 'amount', true);
							break;
						}

					}

					$paymentInfo['Last Payment'] = date('M j, Y', $paymentInfo['Last Payment']);

						if ($paymentInfo['Total Refunded'] > 0) {
							$paymentInfo['Net Total'] = money_format('%n', $paymentInfo['Total Paid'] - $paymentInfo['Total Refunded']);
							$paymentInfo['Total Refunded'] = money_format('%n', (int)$paymentInfo['Total Refunded'] );
						}

						else {
							unset ($paymentInfo['Total Refunded']);
						}
						$paymentInfo['Total Paid'] = money_format('%n', $paymentInfo['Total Paid'] );
					?>

					<h3>Payment and Membership</h3>
						<table class='widefat memberData'>
							<tbody>
							<?php foreach ( $paymentInfo as $label => $data ) : ?>
								<?php if ($data) : ?>
									<tr>
										<th><?php echo $label?></th>
										<th><?php echo $data?></th>
									</tr>
								<?php endif ?>
							<?php endforeach ?>
							</tbody>
						</table>

			<?php
			if ($gcl_sub_id && $paymentInfo['Number of Payments'] > 0) {
				$billsTable->display();
			} ?>


			<?php if ( count ( $nextOfKin ) > 0) : ?>
			<h3>Next of Kin</h3>
			<table class='widefat memberData'>
				<tbody>
				<?php foreach ( $nextOfKin as $label => $data ) : ?>
					<?php if ($data) : ?>
						<tr>
							<th><?php echo $label?></th>
							<th><?php echo $data?></th>
						</tr>
					<?php endif ?>
				<?php endforeach ?>
				</tbody>
			</table>
			<?php endif ?>

			<?php if ( count ( $otherInfo ) > 0) : ?>
			<h3>Other Information</h3>
			<table class='widefat memberData'>
				<tbody>
				<?php foreach ( $otherInfo as $label => $data ) : ?>
					<?php if ($data) : ?>
						<tr>
							<th><?php echo $label?></th>
							<th><?php echo $data?></th>
						</tr>
					<?php endif ?>
				<?php endforeach ?>
				</tbody>
			</table>
			<?php endif ?>
		</div>

		<?php if (count ($medCons) > 0) : ?>
			<h3>Medical Conditions/Disabilities</h3>
			<table class='widefat'>
				<thead>
					<tr>
						<th>Condition</th>
						<th>Medication</th>
						<th>Dose</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($medCons as $row) : ?>
					<tr>
						<td><?php echo $row['Condition'] ?></td>
						<td><?php echo $row['Medication'] ?></td>
						<td><?php echo $row['Dose'] ?></td>
					</tr>
				<?php endforeach ?>
				</tbody>
			</table>
		<?php endif ?>

		<?php if (count ($allergies) > 0) : ?>
			<h3>Allergies</h3>
			<table class='widefat'>
				<thead>
				<tr>
					<th>Allergy</th>
					<th>Medication</th>
					<th>Dose</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($allergies as $row) : ?>
					<tr>
						<td><?php echo $row['Condition'] ?></td>
						<td><?php echo $row['Medication'] ?></td>
						<td><?php echo $row['Dose'] ?></td>
					</tr>
				<?php endforeach ?>
				</tbody>
			</table>
		<?php endif ?>

		<?php if (count ($injuries) > 0) : ?>
			<h3>Injuries</h3>
			<table class='widefat'>
				<thead>
				<tr>
					<th>Injury</th>
					<th>When</th>
					<th>Treatment</th>
					<th>Treated By</th>
					<th>Status</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($injuries as $row) : ?>
					<tr>
						<td><?php echo $row['What'] ?></td>
						<td><?php echo $row['When'] ?></td>
						<td><?php echo $row['Treatment'] ?></td>
						<td><?php echo $row['Who Treated'] ?></td>
						<td><?php echo $row['Status'] ?></td>

					</tr>
				<?php endforeach ?>
				</tbody>
			</table>
		<?php endif ?>



	<?php else : ?>
	<h2>Players <a class='add-new-h2' href='<?php echo admin_url( 'admin.php?page=add-player' ) ?>'>Add Player</a></h2>
	<?php
	$formsTable = new Membership_Forms_Table(array ( 'screen' => 'playerList', 'singular' => 'player', 'plural' => 'players' ));
	$formsTable->prepare_items();

	if ( $_POST )
	{
		switch ( $_POST['action'] )
		{
			case -1: break;

			case 'bulk_email':
				include_once( __DIR__ . '/../../snippets/bulk_email_form.php');
				break;

			default:
				if (! isset ( $_POST['confirm_action'] ) )
					include_once( __DIR__ . '/../../snippets/action_are_you_sure.php');
				break;
		}
	}
	?>
	<form method="post">
		<?php
		$formsTable->views();
		$formsTable->display();
		?>
	</form>
	<?php endif ?>
</div>