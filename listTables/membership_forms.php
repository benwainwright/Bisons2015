<?php


class Membership_Forms_Table extends WP_List_Table_Copy {
	private $users;

	private $paidRows;

	private $supportersRows;

	private $notJoinedRows;

	private $cancelledRows;

	private $rawData;

	private $noPayment;

	private $lastMonth;

	private $committeeMembers;

	private static $plural;

	private static $singular;

	function __construct( $args = array() ) {

		// Get attendance data
		$attendance = getAttendance()['players'];

		// Get users
		$users = getBisonsUsers();

		// Create table data array
		$data = array();

		foreach ( $users as $user ) {


			$row = array();

			// Work out attendance statistics
			$totalPossible = $attendance[ $user->ID ]['stats']['training'] + $attendance[ $user->ID ]['stats']['coaching'] + $attendance[ $user->ID ]['stats']['watching'] + $attendance[ $user->ID ]['stats']['absent'];
			$present       = $attendance[ $user->ID ]['stats']['training'] + $attendance[ $user->ID ]['stats']['coaching'] + $attendance[ $user->ID ]['stats']['watching'];




			$dd_status = getDDStatus($user->ID);

			$row = array(

				'id'             => $user->ID,

				'roles'          => $user->roles,
				'joined'         => get_user_meta( $user->ID, 'joined', true ),
				'user_id'        => $user->data->ID,
				'lastModified'   => get_user_meta( $user->ID, 'lastModified', true ),
				'lastAttended'   => $attendance[ $user->ID ]['lastAttended'],
				'presentPercent' => $totalPossible ? (int) round( ( 100 / $totalPossible ) * $present ) : 0,
				'fullname'       => $user->first_name . ' ' . $user->last_name,
				'type'           => get_user_meta( $user->ID, 'joiningas', true ) ? get_user_meta( $user->ID,
					'joiningas', true ) : 'N/A',
				'email'          => $user->data->user_email,
			);

			if ( get_user_meta( $user->ID, 'joined', true ) ) {


				$row['age'] = getage( get_user_meta( $user->ID, 'dob-day',
						true ) . '/' . get_user_meta( $user->ID, 'dob-month',
						true ) . '/' . get_user_meta( $user->ID, 'dob-year', true ) );
			} else {
				$row['age']          = 'Unknown';
				$row['lastModified'] = strtotime( $user->user_registered );
			}


			$data[] = $row;
		}


		$this->rawData = $data;


		parent::__construct( $args );


	}

	function get_columns() {
		$columns = array(
			'cb'             => '<input type="checkbox" />',
			'fullname'       => 'Name',
			'presentPercent' => 'Attendance',
			'type'           => 'Type',
			'lastModified'   => 'Last Modified',
			'lastAttended'   => 'Last Attended',
			'age'            => 'Age',
			'email'          => 'Email'
		);

		return $columns;
	}

	function usort_reorder( $a, $b ) {

		// If no sort, default to name
		$orderBy = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'fullname';

		// If no order, default to asc
		$order = ( ! empty( $_GET['order'] ) ) ? $_GET['order'] : 'asc';

		$result = null;

		if ( is_int( $a[ $orderBy ] ) ) {

			$result = ( $a[ $orderBy ] === $b[ $orderBy ] ) ? 0 : null;

			$result = ( $a[ $orderBy ] < $b[ $orderBy ] ) ? - 1 : 1;

			$order = ( ! empty( $_GET['order'] ) ) ? $_GET['order'] : 'desc';

		} else {
			// Determine sort order
			$result = strcasecmp( $a[ $orderBy ], $b[ $orderBy ] );
			$order  = ( ! empty( $_GET['order'] ) ) ? $_GET['order'] : 'asc';

		}

		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : - $result;

	}

	function get_sortable_columns() {
		$columns = array(
			'fullname'       => array( 'fullname', false ),
			'joined'         => array( 'joined', false ),
			'presentPercent' => array( 'presentPercent', false ),
			'lastModified'   => array( 'lastModified', false ),
			'lastAttended'   => array( 'lastAttended', false ),
			'type'           => array( 'type', false ),
			'age'            => array( 'age', false ),
			'email'          => array( 'email', false ),
		);

		return $columns;
	}

	function prepare_items() {

		$this->process_actions();

		$this->_column_headers = $this->get_column_info();

		// Sort data
		usort( $this->rawData, array( &$this, 'usort_reorder' ) );

		// Compiled filtered rows
		$this->notJoinedRows  = array();
		$this->supportersRows = array();

		foreach ( $this->rawData as $key => $row ) {


			if ( $row['joined'] == false ) {
				$this->notJoinedRows[] = $row;
			}



			if ( $row['type'] == 'Supporter' ) {
				$this->supportersRows[] = $row;
			}

			if ( $row['lastAttended'] > ( time() - 60*60*24*7*4)) {
				$this->lastMonth[] = $row;
			}

			$committee = false;
			foreach ($row['roles'] as $role) {
				$committee = $role == 'committee_member' ? true : $committee;
			}

			if ($committee) {
				$this->committeeMembers[] = $row;
			}
		}

		// If requested, swap them into the main data array
		switch ( $_GET['filter'] ) {

			case "notjoined":
				$this->data = $this->notJoinedRows;
				break;


			case "supporters":
				$this->data = $this->supportersRows;
				break;

			case "lastMonth":
				$this->data = $this->lastMonth;
				break;

			case "committee":
				$this->data = $this->committeeMembers;
				break;

			default:
				$this->data = $this->rawData;
		}


		$total_items      = count( $this->data );
		$per_page         = $this->get_items_per_page( 'forms_per_page', 5 );
		$current_page     = $this->get_pagenum();
		$this->found_data = array_slice( $this->data, ( ( $current_page - 1 ) * $per_page ), $per_page );
		$this->set_pagination_args( array(
			'total_items' => $total_items,                  //WE have to calculate the total number of items
			'per_page'    => $per_page                     //WE have to determine how many items to show on a page
		) );
		$this->items = $this->found_data;

	}

	function column_default( $item, $column_name ) {

		switch ( $column_name ) {
			case 'presentPercent':
				return $item [ $column_name ] . '&#37;';
			case 'type':
			case 'fullname':
			case 'age':
			case 'email':
				return $item [ $column_name ];
			case 'lastModified':
			case 'lastAttended':
				return $item [ $column_name ]  > 0 ? date( 'M jS, Y', (int) $item [ $column_name ]) : 'Never';
			default:
				new dBug ( $item );
		}
	}

	function get_bulk_actions() {
		$actions = array(
			'bulk_email'      => 'Send Email',
			'reset_pass'      => 'Reset Passwords',
			'download_csv'    => 'Download CSV',
			'printable_forms' => 'Download Printable Membership Data',
			'reset_2fa'       => 'Reset Two Factor',
		);

		if (! current_user_can('reset_2fa')) {
			unset($actions['reset_2fa']);
		}

			return $actions;
	}


	function get_views() {
		$views   = array();
		$current = ( ! empty( $_REQUEST['filter'] ) ? $_REQUEST['filter'] : 'all' );

		//All link
		$class        = ( $current == 'all' ? ' class="current"' : '' );
		$url          = remove_query_arg( 'filter' );
		$count        = count( $this->rawData );
		$views['all'] = "<a href='{$url }' {$class} >All <span class='count'>($count)</span></a>";

		// Not joined link
		$class               = ( $current == 'notjoined' ? ' class="current"' : '' );
		$url                 = add_query_arg( 'filter', 'notjoined' );
		$count               = count( $this->notJoinedRows );
		$views['not_joined'] = "<a href='{$url }' {$class} >Not Joined <span class='count'>($count)</span></a>";

		// Supporters
		$class               = ( $current == 'supporters' ? ' class="current"' : '' );
		$url                 = add_query_arg( 'filter', 'supporters' );
		$count               = count( $this->supportersRows );
		$views['supporters'] = "<a href='{$url }' {$class} >Supporters <span class='count'>($count)</span></a>";

		// Committee
		$class               = ( $current == 'committee' ? ' class="current"' : '' );
		$url                 = add_query_arg( 'filter', 'committee' );
		$count               = count( $this->committeeMembers );
		$views['committee'] = "<a href='{$url }' {$class} >Committee <span class='count'>($count)</span></a>";

		// Attended in the last month
		$class               = ( $current == 'lastMonth' ? ' class="current"' : '' );
		$url                 = add_query_arg( 'filter', 'lastMonth' );
		$count               = count( $this->lastMonth );
		$views['lastMonth'] = "<a href='{$url }' {$class} >Attended in last month <span class='count'>($count)</span></a>";

		return $views;

	}

	function column_lastModified( $item ) {
		return date( 'M j, Y', $item ['lastModified'] );

	}


	function column_joined( $item ) {
		$return = $item ['joined'] ? 'Yes' : 'No';

		return "<span class='memForm_$return'>$return</span>";
	}

	function process_actions() {


		if ( $_GET['action'] === 'markInactive' && wp_verify_nonce( $_GET['nonce'], 'mark_inactive_' . $_GET['user'] )
		) {
			echo "<h1>HELLO</h1>";

			update_user_meta($_GET['user'], 'inActive', true);
			$query = remove_query_arg( array( 'action', 'nonce', 'user' ), $_SERVER['QUERY_STRING'] );
			wp_redirect( admin_url( 'admin.php?' . $query ) );
			exit;
		}
	}

	function column_fullname( $item ) {

		$nonce = wp_create_nonce( 'mark_inactive_' . $item['id'] );


		$actions = array(
		'trash' => sprintf( '<a class="markInactive" href="?page=%s&action=%s&user=%s&nonce=%s">Inactive</a>', $_REQUEST['page'],
			'markInactive', $item['id'], $nonce )
		);

		$link = "<a href=' " . admin_url( "admin.php?page=players&user_id={$item[ 'user_id' ]}'" ) . ">" . $item ['fullname'] . "</a>";

		return sprintf( '%1$s %2$s', $link, $this->row_actions( $actions ) );
	}

	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="user_id[]" value="%s" />', $item['user_id']
		);
	}

	function column_email( $item ) {
		return "<a href='mailto:" . $item['email'] . "'>" . $item['email'] . "</a>";
	}
}

