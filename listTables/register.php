<?php

class RegisterListTable extends WP_List_Table_Copy {


	function __construct($noCache = false, $args = array())
	{
		if ( ( ! $data = get_transient('attendance_register'))  || $noCache) {
			$query = new WP_Query( array( 'post_type' => 'attendance_registers', 'posts_per_page' => - 1 ) );

			$players = array();

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

			$users = get_users();
			$data  = array();
			foreach ( $users as $user ) {

				$training = 0;
				$watching = 0;
				$coaching = 0;
				$absent   = 0;

				foreach ( $players[ $user->ID ]['register'] as $session ) {
					switch ( $session ) {

						case 'p':
							$training ++;
							break;

						case 'w':
							$watching ++;
							break;

						case 'c':
							$coaching ++;
							break;

						case 'a':
							$absent ++;
							break;
					}
				}
				$totalPossible = $training + $watching + $coaching + $absent;

				$present = $training + $watching + $coaching;

				$data[] = array(
					'id'              => $user->ID,
					'name'            => $user->first_name . ' ' . $user->last_name,
					'training'        => $training,
					'trainingPercent' => ( 100 / $totalPossible ) * $training,
					'watching'        => $watching,
					'watchingPercent' => ( 100 / $totalPossible ) * $watching,
					'coaching'        => $coaching,
					'coachingPercent' => ( 100 / $totalPossible ) * $coaching,
					'present'         => $present,
					'presentPercent'  => ( 100 / $totalPossible ) * $present,
					'absent'          => $absent,
					'absentPercent'   => ( 100 / $totalPossible ) * $absent,
					'totalPossible'   => $totalPossible
				);
			}
			set_transient('attendance_register', $data, 60*60*24);
		}
		$this->data = $data;
		parent::__construct($args);
	}



	function prepare_items()
	{
		$this->_column_headers = $this->get_column_info();

		// Sort data
		usort( $this->data, array( &$this, 'usort_reorder' ) );

		$total_items      = count( $this->data );
		$per_page         = $this->get_items_per_page( 'users_per_page', 5 );
		$current_page     = $this->get_pagenum();
		$this->found_data = array_slice( $this->data, ( ( $current_page - 1 ) * $per_page ), $per_page );
		$this->set_pagination_args( array(
			'total_items' => $total_items,                  //WE have to calculate the total number of items
			'per_page'    => $per_page                     //WE have to determine how many items to show on a page
		) );
		$this->items = $this->found_data;
	}

	function usort_reorder( $a, $b ) {
		// If no sort, default to date
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'name';

		// If no order, default to asc
		$order = ( ! empty( $_GET['order'] ) ) ? $_GET['order'] : 'asc';

		// Determine sort order
		$result = strcmp( $a[ $orderby ], $b[ $orderby ] );

		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : - $result;
	}

	function get_columns() {
		$columns = array(
			'cb'       => '<input type="checkbox" />',
			'name'          => 'Name',
			'present'      => 'Present',
			'training'      => 'Training',
			'watching'      => 'Watching',
			'coaching'      => 'Coaching',
			'absent'        => 'Absent',
		);

		return $columns;
	}

	function get_sortable_columns() {
		$columns = array(
			'name'          => array('name', false),
			'present'      => array('present', false),
			'training'      => array('training', false),
			'watching'      => array('watching', false),
			'coaching'      => array('coaching', false),
			'absent'        => array('absent', false),
			);

		return $columns;
	}

	function column_default( $item, $column_name ) {

		switch ( $column_name ) {


			case 'present':
				return $item [ 'presentPercent' ] . '&#37; (' . $item['present'] . ')';
				break;
			case 'training':
				return $item [ 'trainingPercent' ] . '&#37; (' . $item['training'] . ')';
				break;
			case 'watching':
				return $item [ 'watchingPercent' ] . '&#37; (' . $item['watching'] . ')';
				break;
			case 'coaching':
				return $item [ 'coachingPercent' ] . '&#37; (' . $item['coaching'] . ')';
				break;
			case 'absent':
				return $item [ 'absentPercent' ] . '&#37; (' . $item['absent'] . ')';
				break;
			case 'name':
				return $item [ $column_name ];
			default:
				new dBug ( $item );
		}
	}

	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="user_id[]" value="%s" />', $item['id']
		);
	}

}