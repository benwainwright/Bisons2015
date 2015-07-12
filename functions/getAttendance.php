<?php

function getAttendance( $noCache = false, $season = false ) {

	$data = get_transient( 'bisons_attendance' );

	if ( false === $players || $noCache || $season ) {

		$query = array(
			'post_status'    => 'publish',
			'post_type'      => 'attendance_registers',
			'posts_per_page' => - 1
		);

		if ( 'current' === $season ) {
			$tax_query = wp_excludePostsWithTermTaxQuery( 'seasons' );
		} else if ( $season ) {
			$tax_query = array(
				array(
					'taxonomy' => 'seasons',
					'field'    => 'slug',
					'terms'    => array( $season ),
					'operator' => 'IN'
				)
			);
		}

		if ( $season ) {
			$query['tax_query'] = $tax_query;
		}

		$query   = new WP_Query( $query );
		$players = array();

		$stats = array(
			'totalSessions' => 0,
			'allTrained'    => array(),
			'allWatched'    => array(),
			'allCoached'    => array()
		);

		$sumTrained = 0;
		$sumPresent = 0;

		while ( $query->have_posts() ) {
			$query->the_post();

			$stats['totalSessions'] ++;

			$date = get_post_meta( get_the_id(), 'reg-date', true );

			$present = get_post_meta( get_the_id(), 'players_present', false );

			$sumTrained += count ( $present );
			$sumPresent += count ( $present );

			foreach ( $present as $player ) {
				$players[ $player ]['register'][] = array( 'date' => $date, 'mark' => 'p' );
				if ( false === array_search( $player, $stats['allTrained'] ) ) {
					$stats['allTrained'][] = $player;
				}
			}

			$watching = get_post_meta( get_the_id(), 'players_watching', false );

			foreach ( $watching as $player ) {
				$players[ $player ]['register'][] = array( 'date' => $date, 'mark' => 'w' );
				if ( false === array_search( $player, $stats['allWatched'] ) ) {
					$stats['allWatched'][] = $player;
				}
			}
			$sumPresent += count ( $watching );


			$coaching = get_post_meta( get_the_id(), 'players_coaching', false );

			foreach ( $coaching as $player ) {
				$players[ $player ]['register'][] = array( 'date' => $date, 'mark' => 'c' );
				if ( false === array_search( $player, $stats['allCoached'] ) ) {
					$stats['allCoached'][] = $player;
				}
			}

			$sumPresent += count ( $coaching );


			$absent = get_post_meta( get_the_id(), 'players_absent', false );

			foreach ( $absent as $player ) {
				$players[ $player ]['register'][] = array( 'date' => $date, 'mark' => 'a' );
			}

		}


		if ( $stats['totalSessions'] > 0 ) {
			$stats['averagePlayersTraining'] = $sumTrained / $stats['totalSessions'];
			$stats['averagePlayersPresent'] = $sumPresent / $stats['totalSessions'];

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

		$data = array();
		$data['players'] = $players;
		$data['stats']   = $stats;

		set_transient( 'bisons_attendance', $data, 60 * 60 * 24 );
	}


	return $data;

}