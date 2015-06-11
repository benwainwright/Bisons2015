<?php

function getAttendance($noCache = false) {

	$players = get_transient( 'bisons_attendance' );

	if ( false === $players || $noCache ) {

		$query = new WP_Query( array( 'post_type' => 'attendance_registers', 'posts_per_page' => - 1 ) );

		while ( $query->have_posts() ) {
			$query->the_post();

			$date = get_post_meta( get_the_id(), 'reg-date', true );

			foreach ( get_post_meta( get_the_id(), 'players_present', false ) as $player ) {
				$players[ $player ]['register'][ $date ] = 'p';
			}

			foreach ( get_post_meta( get_the_id(), 'players_watching', false ) as $player ) {
				$players[ $player ]['register'][ $date ] = 'w';
			}

			foreach ( get_post_meta( get_the_id(), 'players_coaching', false ) as $player ) {
				$players[ $player ]['register'][ $date ] = 'c';
			}

			foreach ( get_post_meta( get_the_id(), 'players_absent', false ) as $player ) {
				$players[ $player ]['register'][ $date ] = 'a';
			}

		}


		foreach ( $players as $userID => $player ) {


			$players[ $userID ]['stats']['training'] = 0;
			$players[ $userID ]['stats']['watching'] = 0;
			$players[ $userID ]['stats']['coaching'] = 0;
			$players[ $userID ]['stats']['absent']   = 0;


			foreach ( $player['register'] as $session ) {
				switch ( $session ) {

					case 'p':
						$players[ $userID ]['stats']['training'] ++;
						break;

					case 'w':
						$players[ $userID ]['stats']['watching'] ++;
						break;

					case 'c':
						$players[ $userID ]['stats']['coaching'] ++;
						break;

					case 'a':
						$players[ $userID ]['stats']['absent'] ++;
						break;
				}
			}
		}


		set_transient( 'bisons_attendance', $players, 60 * 60 * 24 );
	}

	return $players;

}