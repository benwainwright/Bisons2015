<?php

function getAttendance($noCache = false) {

	$players = get_transient( 'bisons_attendance' );

	if ( false === $players || $noCache ) {


		$players = array();
		$query = new WP_Query( array( 'post_type' => 'attendance_registers', 'posts_per_page' => - 1 ) );

		while ( $query->have_posts() ) {
			$query->the_post();

			$date = get_post_meta( get_the_id(), 'reg-date', true );

			foreach ( get_post_meta( get_the_id(), 'players_present', false ) as $player ) {
				$players[ $player ]['register'][] = array('date' => $date, 'mark' => 'p');
			}

			foreach ( get_post_meta( get_the_id(), 'players_watching', false ) as $player ) {
				$players[ $player ]['register'][] = array('date' => $date, 'mark' => 'w');
			}

			foreach ( get_post_meta( get_the_id(), 'players_coaching', false ) as $player ) {
				$players[ $player ]['register'][] = array('date' => $date, 'mark' => 'c');
			}

			foreach ( get_post_meta( get_the_id(), 'players_absent', false ) as $player ) {
				$players[ $player ]['register'][] = array('date' => $date, 'mark' => 'a');
			}

		}


		
		foreach ( $players as $userID => $player ) {

			$highestDate = 0;

			$players[ $userID ]['stats']['training'] = 0;
			$players[ $userID ]['stats']['watching'] = 0;
			$players[ $userID ]['stats']['coaching'] = 0;
			$players[ $userID ]['stats']['absent']   = 0;


			foreach ( $player['register'] as $session ) {

				switch ( $session['mark'] ) {

					case 'p':
						$highestDate = $session['date'] > $highestDate ? $session['date'] : $highestDate;
						$players[ $userID ]['stats']['training'] ++;
						break;

					case 'w':
						$highestDate = $session['date'] > $highestDate ? $session['date'] : $highestDate;
						$players[ $userID ]['stats']['watching'] ++;
						break;

					case 'c':
						$highestDate = $session['date'] > $highestDate ? $session['date'] : $highestDate;
						$players[ $userID ]['stats']['coaching'] ++;
						break;

					case 'a':
						$players[ $userID ]['stats']['absent'] ++;
						break;
				}
			}

			$players[ $userID ]['lastAttended'] = $highestDate;
		}


		set_transient( 'bisons_attendance', $players, 60 * 60 * 24 );
	}


	return $players;

}