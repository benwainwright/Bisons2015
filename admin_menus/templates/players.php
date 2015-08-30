<div class="wrap">
	<?php if ( isset( $_GET['user_id'] ) ) : $user = get_user_by( 'id', $_GET['user_id'] ); ?>


		<h2>Member Details</h2>
		<p>
			<?php endif ?>Information held by the club is only to be used in accordance with club business and in line with the provisions of the <a href="https://en.wikipedia.org/wiki/Data_Protection_Act_1998">Data Protection Act</a>. Any items that have been
			<span class="updated">recently updated</span> will be marked pink.
		</p>
	<p><?php if (get_user_meta($_GET['user_id'],'lastModified', true)) : ?>
		<em>Last updated on the <?php echo date('jS \\of F, Y', get_user_meta($_GET['user_id'],'lastModified', true)) ?>.</em></p>
		<?php

		if ( get_user_meta( $_GET['user_id'], 'joined', true ) ) {

			$updatedFields = get_user_meta( $_GET['user_id'], 'updatedFields', true );
			$updatedFields = is_array( $updatedFields ) ? $updatedFields : array( $updatedFields );

			$riskFactors = array(
				'fainting' => 'Fainting',
				'dizzyturns' => 'Dizzy Turns',
				'breathlessness' => 'Breathlessness or more easily tired than teammates',
				'bloodpressure' => 'History of high blood pressure',
				'diabetes' => 'Diabetes',
				'palpitations' => 'Heart palpitations',
				'chestpain' => 'Chest pain or tightness',
				'suddendeath' => 'Sudden death in immediate family of anyone under 50',
				'smoking' => 'Smoking',
			);

			$myFactors = array();
			$myFactorsStrings = array();
			$factorsChanged = false;

			foreach ( $riskFactors as $key => $factor ) {

				if ( array_search( $key, $updatedFields ) !== false ) {
					$factorsChanged = true;
					$factorString = "<span class='updated'>$factor</span>";
				}

				else {
					$factorString = $factor;
				}


				if ( get_user_meta ( $_GET['user_id'], $key, true ) ) {
					$myFactorsStrings[] = $factorString;
					$myFactors = $factor;
				}
			}

			$myFactorsString = implode(', ', $myFactorsStrings);



			$attendance = getAttendance()['players'][ $_GET['user_id'] ]['stats'];


			for ( $i = 1; $i <= get_user_meta( $_GET['user_id'], 'condsdisablities_rowcount', true ); $i ++ ) {
				$medCons[] = array(
					array(
						array( "condsdisablities_name_row$i" ),
						'Condition' => get_user_meta( $_GET['user_id'], "condsdisablities_name_row$i", true )
					),
					array(
						array( "condsdisablities_drugname_row$i" ),
						'Medication' => get_user_meta( $_GET['user_id'], "condsdisablities_drugname_row$i", true )
					),
					array(
						array( "condsdisablities_drugdose_freq_row$i" ),
						'Dose' => get_user_meta( $_GET['user_id'], "condsdisablities_drugdose_freq_row$i", true )
					)
				);
			}

			for ( $i = 1; $i <= get_user_meta( $_GET['user_id'], 'allergies_rowcount', true ); $i ++ ) {
				$allergies[] = array(
					array(
						array( "allergies_name_row$i" ),
						'Condition' => get_user_meta( $_GET['user_id'], "allergies_name_row$i", true )
					),
					array(
						array( "allergies_drugname_row$i" ),
						'Medication' => get_user_meta( $_GET['user_id'], "allergies_drugname_row$i", true )
					),
					array(
						array( "allergies_drugdose_freq_row$i" ),
						'Dose' => get_user_meta( $_GET['user_id'], "allergies_drugdose_freq_row$i", true )
					)
				);
			}

			for ( $i = 1; $i <= get_user_meta( $_GET['user_id'], 'allergies_rowcount', true ); $i ++ ) {
				$injuries[] = array(
					array(
						array( "injuries_name_row$i" ),
						'What' => get_user_meta( $_GET['user_id'], "injuries_name_row$i", true )
					),
					array(
						array( "injuries_when_row$i" ),
						'When' => get_user_meta( $_GET['user_id'], "injuries_when_row$i", true )
					),
					array(
						array( "injuries_treatmentreceived_row$i" ),
						'Treatment' => get_user_meta( $_GET['user_id'], "injuries_treatmentreceived_row$i", true )
					),
					array(
						array( "injuries_who_row$i" ),
						'Who Treated' => get_user_meta( $_GET['user_id'], "injuries_who_row$i", true )
					),
					array(
						array( "injuries_status_row$i" ),
						'Status' => get_user_meta( $_GET['user_id'], "injuries_status_row$i", true )
					),
				);
			}

			$streetAddy = array();
			if ( get_user_meta( $_GET['user_id'], 'streetaddyl1', true ) ) {
				$streetAddy['streetaddyl1'] = get_user_meta( $_GET['user_id'], 'streetaddyl1', true );
			}
			if ( get_user_meta( $_GET['user_id'], 'streetaddyl2', true ) ) {
				$streetAddy['streetaddyl2'] = get_user_meta( $_GET['user_id'], 'streetaddyl2', true );
			}
			if ( get_user_meta( $_GET['user_id'], 'streetaddytown', true ) ) {
				$streetAddy['streetaddytown'] = get_user_meta( $_GET['user_id'], 'streetaddytown', true );
			}
			if ( get_user_meta( $_GET['user_id'], 'postcode', true ) ) {
				$streetAddy['postcode'] = get_user_meta( $_GET['user_id'], 'postcode', true );
			}


			$dob = get_user_meta( $_GET['user_id'], 'dob-month', true ) . '/' .
			       get_user_meta( $_GET['user_id'], 'dob-day', true ) . '/' .
			       get_user_meta( $_GET['user_id'], 'dob-year', true );

			if ( get_user_meta( $_GET['user_id'], 'nokstreetaddy', true ) ) {
				$nokAddy[] = get_user_meta( $_GET['user_id'], 'nokstreetaddy', true );
			}
			if ( get_user_meta( $_GET['user_id'], 'nokpostcode', true ) ) {
				$nokAddy[] = get_user_meta( $_GET['user_id'], 'nokpostcode', true );
			}
			$details = array(
				'Personal'    => array(
					'Name'           => array(
						array( 'firstname', 'surname' ),
						$user->user_firstname . ' ' . $user->user_lastname
					),
					'Email'          => array( array( 'email_addy' ), addMailToLink( $user->user_email ) ),
					'Gender'         => array(
						array( 'gender', 'othergender' ),
						get_user_meta( $_GET['user_id'], 'othergender', true ) ? get_user_meta( $_GET['user_id'],
							'othergender', true ) : get_user_meta( $_GET['user_id'], 'gender', true )
					),
					'Date of Birth'  => array(
						array( 'dob-day', 'dob-month', 'dob-year' ),
						reformat_date( $dob, 'jS \of F Y' )
					),
					'Age'            => array( array(), getage( $dob ) ),
					'Contact Number' => array(
						array( 'contact_number' ),
						addTelLink( get_user_meta( $_GET['user_id'], 'contact_number', true ) )
					),
					'Street Address' => array(
						array( 'streetaddyl1', 'streetaddyl2', 'streetaddytown', 'postcode' ),
						implode( '<br />', $streetAddy )
					)
				),
				'Next of Kin' => array(
					'Name'           => array(
						array( 'nokfirstname', 'noksurname' ),
						get_user_meta( $_GET['user_id'], 'nokfirstname', true ) . ' ' . get_user_meta( $_GET['user_id'],
							'noksurname', true )
					),
					'Relationship'   => array(
						array( 'nokrelationship' ),
						get_user_meta( $_GET['user_id'], 'nokrelationship', true )
					),
					'Contact Number' => array(
						array( 'nokcontactnumber' ),
						addTelLink( get_user_meta( $_GET['user_id'], 'nokcontactnumber', true ) )
					),
					'Address'        => array(
						array( 'sameaddress', 'nokstreetaddy', 'nokpostcode' ),
						get_user_meta( $_GET['user_id'], 'sameaddress',
							true ) == 'Yes' ? $personalDetails['Street Address'] : implode( '<br />', $nokAddy )
					)
				),
				'Other Info'  => array(
					'Other sports'          => array(
						array( 'othersports' ),
						get_user_meta( $_GET['user_id'], 'othersports', true )
					),
					'Training hours a week' => array(
						array( 'hoursaweektrain' ),
						get_user_meta( $_GET['user_id'], 'hoursaweektrain', true )
					),
					'Previously played at'  => array(
						array( 'playedbefore', 'whereandseasons' ),
						get_user_meta( $_GET['user_id'], 'playedbefore',
							true ) == 'Yes' ? get_user_meta( $_GET['user_id'], 'whereandseasons', true ) : 'No'
					),
					'Height'                => array(
						array( 'height' ),
						get_user_meta( $_GET['user_id'], 'height', true )
					),
					'Weight'                => array(
						array( 'weight' ),
						get_user_meta( $_GET['user_id'], 'weight', true )
					),
					'Referral Source'       => array(
						array( 'howdidyouhear' ),
						get_user_meta( $_GET['user_id'], 'howdidyouhear', true )
					),
					'Skills'                => array(
						array( 'whatcanyoubring' ),
						get_user_meta( $_GET['user_id'], 'whatcanyoubring', true )
					),
				)
			);

			foreach ( $details as $sectionNumber => $section ) {

				foreach ( $section as $rowNumber => $row ) {

					foreach ( $row[0] as $fieldNumber => $field ) {

						if ( array_search( $field, $updatedFields ) !== false ) {
							$details[ $sectionNumber ][ $rowNumber ][0] = true;
						}
					}
					$details[ $sectionNumber ][ $rowNumber ][0] = is_array( $details[ $sectionNumber ][ $rowNumber ][0] ) ? false : true;
				}

			}

			$varied = array(
				'Medical Conditions or Disabilities' => $medCons,
				'Allergies'                          => $allergies,
				'Injuries'                           => $injuries
			);


			foreach ( $varied as $sectionTitle => $section ) {

				if ( is_array( $section ) ) {
					foreach ( $section as $rowNumber => $row ) {
						foreach ( $row as $colNumber => $column ) {
							foreach ( $column[0] as $fieldNumber => $field ) {

								if ( array_search( $field, $updatedFields ) !== false ) {
									$varied[ $sectionTitle ][ $rowNumber ][ $colNumber ][0] = true;
								}
							}
							$varied[ $sectionTitle ][ $rowNumber ][ $colNumber ][0] = is_array( $varied[ $sectionTitle ][ $rowNumber ][ $colNumber ][0] ) ? false : true;
						}
					}
				}

			}


			$totalPoss = $attendance['training'] + $attendance['coaching'] + $attendance['watching'] + $attendance['absent'];

			if ( $totalPoss > 0 ) {

				$sessionsPresent = $attendance['training'] + $attendance['coaching'] + $attendance['watching'];
				$attendanceInfo  = array(
					'Total Possible Sessions' => $totalPoss,
					'Sessions Present'        => $sessionsPresent,
					'Attendance Percentage'   => ( 100 / $totalPoss ) * $sessionsPresent . '&#37;'
				);
			}


		} else {

			$user = get_user_by( 'id', $_GET['user_id'] );

			$details = array(
				'Name'  => array( array(), $user->user_firstname . ' ' . $user->user_lastname ),
				'Email' => array( array(), $user->data->user_email )
			);


		}


		foreach ( $details as $title => $section ) :

			?>
			<h3><?php echo $title ?></h3>
			<table class="widefat">
				<?php foreach ( $section as $label => $row ) :

					?>
					<tr<?php if ( $row[0] ) {
						echo ' class="updated"'; } ?>>
						<th<?php if ( $row[0] ) {
							echo ' class="updated"'; } ?>>
							<?php echo $label ?>
						</th>
						<td<?php if ( $row[0] ) {
							echo ' class="updated"'; } ?>>
							<?php echo $row[1] ?>
						</td>
					</tr>
				<?php endforeach ?>
			</table>
		<?php endforeach ?>
		<h3>Risk Factors</h3>
		<div<?php if ($factorsChanged) echo " class='updated'" ?> id="riskFactors">
		<?php if (count ( $myFactors) > 0 ) : ?>
		<p><strong><?php echo $myFactorsString ?>.</strong></p>
			<?php else : ?>
			<p><em>None recorded...</em></p>
			<?php endif ?>
		</div>
		<?php foreach ( $varied as $title => $section ) : ?>

			<h3><?php echo $title ?></h3>


			<?php if ( count( $section ) > 0 ) :
				$columns      = $section[0];
				$columnsArray = array();
				foreach ( $columns as $col ) {
					foreach ( $col as $key => $value ) {
						if ( ! is_int( $key ) ) {
							$columnsArray[] = $key;
						}
					}
				}

				?>
				<table class="widefat">
					<tr>
						<?php foreach ( $columnsArray as $col ) : ?>
							<th><strong><?php echo $col ?></strong></th>
						<?php endforeach ?>
					</tr>
					<?php foreach ( $section as $row ) : ?>
						<tr>
							<?php foreach ( $row as $data ) : foreach ( $data as $key => $part ) : ?>
								<?php if ( ! is_int( $key ) ) {
									if ( $data[0] ) {
										echo "<td class='updated'>";
									} else {
										echo "<td>";
									}
									echo "$part";
									echo "</td>";
								}
								?>
							<?php endforeach; endforeach; ?>
						</tr>
					<?php endforeach ?>
				</table>
			<?php else : ?>
				<p><em>None recorded...</em></p>

			<?php endif ?>
		<?php endforeach ?>



	<?php


	else : ?>
		<h2>Players <a class='add-new-h2' href='<?php echo admin_url( 'admin.php?page=add-player' ) ?>'>Add Player</a>
			<a class='add-new-h2' href='<?php echo admin_url( 'post-new.php?post_type=attendance_registers' ) ?>'>Record
				Attendance</a></h2>
		<p>Please note that at this time, some of the information in this database (specifically attendance information)
			is not yet accurate because historical information (prior to implementing this feature) has not yet been
			recorded</p>
		<?php
		$formsTable = new Membership_Forms_Table( array(
			'screen'   => 'playerList',
			'singular' => 'player',
			'plural'   => 'players'
		) );
		$formsTable->prepare_items();

		if ( $_POST ) {
			switch ( $_POST['action'] ) {
				case - 1:
					break;

				case 'bulk_email':
					include_once( __DIR__ . '/../../snippets/bulk_email_form.php' );
					break;

				default:
					if ( ! isset ( $_POST['confirm_action'] ) ) {
						include_once( __DIR__ . '/../../snippets/action_are_you_sure.php' );
					}
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