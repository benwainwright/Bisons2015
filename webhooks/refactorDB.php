<?php

$users = get_users();

foreach($users as $user) {

	$current_form = new WP_Query ( array (
		'post_type' => 'membership_form',
		'posts_per_page' => 1,
		'orderby'   => 'date',
		'order'     => 'DESC',
		'author'    => $user->ID
	));


	if ($current_form->have_posts()) {
		$current_form->the_post();

		$formID = get_the_id();

		if (get_user_meta($user->ID, 'joined') != true)
			update_user_meta($user->ID, 'joined', true);

		if (get_user_meta($user->ID, 'lastModified', true) != get_the_modified_date('U'))
			update_user_meta($user->ID, 'lastModified', get_the_modified_date('U'));
	}

	$metaData = get_post_meta($formID);


	$theData = array();
	foreach($metaData as $key => $data) {


		$highDate = 0;
		$statusCancelled = false;
		$statusActive = true;
		$memInActive = false;
		$diff = false;

		foreach($data as $key2 => $version) {


			switch($key) {
				case "last_payment":
					$date = strtotime($version);
					$highDate = $date > $highDate ? $date : $highDate;

					break;

				case "payment_status":

					if (9 == $version) {
						$statusCancelled = true;
					}

					if (7 == $version) {
						$statusActive = true;
					}

					break;

				case "mem_status":

					if ('Inactive' == $version) {
						$memInActive = true;
					}

					break;
			}

			if ( $key2 > 0 && $version !== $data[$key2-1] && $version !== '' && $data[$key2-1] !== '') {
				$diff = true;
			}
		}


		if ( $diff !== true) {
			update_user_meta($user->ID, $key, $data[0]);
		}

		else {
			if ($highDate>0) {
				update_user_meta($user->ID, $key, date('Y-m-d H-i-s', $highDate));
			}

			else if ($statusCancelled) {
				update_user_meta($user->ID, $key, 9);
			}

			else if ($memInActive) {
				update_user_meta($user->ID, $key, 'Inactive');
			}

			else if ($statusActive) {
				update_user_meta($user->ID, $key, 8);
			}
			else {
				update_user_meta($user->ID, $key, $data[0]);
			}
		}

	}

	for($i = 1; isset($metaData['allergies_name_row' . $i]); $i++) {
		update_user_meta($user->ID, 'allergies_rowcount', $i);
	}

	for($i = 1; isset($metaData['condsdisablities_name_row' . $i]); $i++) {
		update_user_meta($user->ID, 'condsdisablities_rowcount', $i);
	}

	for($i = 1; isset($metaData['injuries_name_row' . $i]); $i++) {
		update_user_meta($user->ID, 'injuries_rowcount', $i);
	}

	wp_delete_post($formID, true);

}