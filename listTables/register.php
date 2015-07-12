<?php

class RegisterListTable extends WP_List_Table_Copy {


	function __construct( $noCache = false, $args = array() ) {
		$query = new WP_Query( array( 'post_type' => 'attendance_registers', 'posts_per_page' => - 1 ) );

		$users     = get_users();
		$userNames = array();

		foreach ( $users as $user ) {
			$userNames[ $user->ID ] = $user->data->display_name;
		}

		$data = array();

		while ( $query->have_posts() ) {
			$query->the_post();

			$training = get_post_meta( get_the_id(), 'players_present', false );

			foreach ( $training as &$user ) {
				$user = $userNames[ $user ];
			}


			$coaching = get_post_meta( get_the_id(), 'players_coaching', false );

			foreach ( $coaching as &$user ) {
				$user = $userNames[ $user ];
			}

			$watching = get_post_meta( get_the_id(), 'players_watching', false );

			foreach ( $watching as &$user ) {
				$user = $userNames[ $user ];
			}

			$data[] = array(
				'id'       => get_the_id(),
				'date'     => get_post_meta( get_the_id(), 'reg-date', true ),
				'training' => implode( ', ', $training ),
				'coaching' => implode( ', ', $coaching ),
				'watching' => implode( ', ', $watching ),
			);
		}

		$this->data = $data;


		parent::__construct( $args );


	}


	function prepare_items() {

		$this->process_actions();

		$this->_column_headers = $this->get_column_info();

		// Sort data
		usort( $this->data, array( &$this, 'usort_reorder' ) );

		$total_items      = count( $this->data );
		$per_page         = $this->get_items_per_page( 'players_per_page', 10 );
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
		$orderBy = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'date';

		// If no order, default to asc
		$order = ( ! empty( $_GET['order'] ) ) ? $_GET['order'] : 'desc';

		$result = null;

		if ( is_int( $a[ $orderBy ] ) ) {


			$result = ( $a[ $orderBy ] === $a[ $orderBy ] ) ? 0 : null;

			$result = ( $a[ $orderBy ] < $b[ $orderBy ] ) ? - 1 : 1;
		} else {
			// Determine sort order
			$result = strcmp( $a[ $orderBy ], $b[ $orderBy ] );
		}

		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : - $result;

	}

	function get_columns() {
		$columns = array(
			'date'     => 'Date',
			'training' => 'Training',
			'coaching' => 'Coaching',
			'watching' => 'Watching',
		);

		return $columns;
	}

	function get_sortable_columns() {
		$columns = array(
			'date' => array( 'date', false ),
		);

		return $columns;
	}


	function process_actions() {
		if ( $_GET['action'] === 'delete' && wp_verify_nonce( $_GET['nonce'], 'delete_register_' . $_GET['register'] )
		) {

			wp_delete_post( $_GET['register'] );
			$query = remove_query_arg( array( 'action', 'nonce', 'register' ), $_SERVER['QUERY_STRING'] );
			wp_redirect( admin_url( 'admin.php?' . $query ) );
			exit;
		}
	}

	function column_date( $item ) {

		$nonce = wp_create_nonce( 'delete_register_' . $item['id'] );

		$actions = array(
			'delete' => sprintf( '<a href="?page=%s&action=%s&register=%s&nonce=%s">Delete</a>', $_REQUEST['page'],
				'delete', $item['id'], $nonce )
		);

		return sprintf( '%1$s %2$s', date( 'M j, Y', (int) $item ['date'] ), $this->row_actions( $actions ) );

	}

	function column_default( $item, $column_name ) {

		switch ( $column_name ) {

			case 'training':
			case 'coaching':
			case 'watching':
				return $item[ $column_name ] ? $item[ $column_name ] : 'None';
			default:
				new dBug ( $item );
		}
	}


}