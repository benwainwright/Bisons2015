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

	private static $plural;

	private static $singular;

	function __construct( $args = array() ) {

		// Get attendance data
		$attendance = getAttendance();

		// Get users
		$users = get_users();

		// Create table data array
		$data = array();

		foreach ( $users as $user ) {


			$row = array();

			// Work out attendance statistics
			$totalPossible = $attendance[ $user->ID ]['stats']['training'] + $attendance[ $user->ID ]['stats']['coaching'] + $attendance[ $user->ID ]['stats']['watching'] + $attendance[ $user->ID ]['stats']['absent'];
			$present       = $attendance[ $user->ID ]['stats']['training'] + $attendance[ $user->ID ]['stats']['coaching'] + $attendance[ $user->ID ]['stats']['watching'];



			$dd_status = getDDStatus($user->ID);

			$row = array(
				'joined'         => get_user_meta( $user->ID, 'joined', true ),
				'user_id'        => $user->data->ID,
				'DD_sub_id'      => get_user_meta( $user->ID, 'gcl_sub_id', true ),
				'lastModified'   => get_user_meta( $user->ID, 'lastModified', true ),
				'lastAttended'   => $attendance[ $user->ID ]['lastAttended'],
				'presentPercent' => $totalPossible ? round( ( 100 / $totalPossible ) * $present ) : 0,
				'dd_status'      => $dd_status ? $dd_status : 'None',
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
			'dd_status'      => 'Payment',
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
			'dd_status'      => array( 'dd_status', false ),
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
		$this->_column_headers = $this->get_column_info();

		// Sort data
		usort( $this->rawData, array( &$this, 'usort_reorder' ) );

		// Compiled filtered rows
		$this->paidRows       = array();
		$this->cancelledRows  = array();
		$this->notJoinedRows  = array();
		$this->supportersRows = array();
		$this->noPayment      = array();

		foreach ( $this->rawData as $key => $row ) {

			if ( $row['dd_status'] == 'Paid in Full' || $row['dd_status'] == 'active' ) {
				$this->paidRows[] = $row;
			}

			if ( $row['joined'] == false ) {
				$this->notJoinedRows[] = $row;
			}

			if ( $row['dd_status'] == 'cancelled' ) {
				$this->cancelledRows[] = $row;
			}

			if ( $row['dd_status'] == 'None' || $row['dd_status'] == 'cancelled' || $row['dd_status'] == 'expired' || $row['dd_status'] == 'inactive' ) {
				$this->noPayment[] = $row;
			}

			if ( $row['type'] == 'Supporter' ) {
				$this->supportersRows[] = $row;
			}

			if ( $row['lastAttended'] > ( time() - 60*60*24*7*4)) {
				$this->lastMonth[] = $row;
			}
		}

		// If requested, swap them into the main data array
		switch ( $_GET['filter'] ) {
			case "paid":
				$this->data = $this->paidRows;
				break;

			case "cancelleddd":
				$this->data = $this->cancelledRows;
				break;

			case "notjoined":
				$this->data = $this->notJoinedRows;
				break;

			case "nopayment":
				$this->data = $this->noPayment;
				break;

			case "supporters":
				$this->data = $this->supportersRows;
				break;

			case "lastMonth":
				$this->data = $this->lastMonth;
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
			'printable_forms' => 'Download Printable Membership Data'
		);

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

		// Paid
		$class         = ( $current == 'paid' ? ' class="current"' : '' );
		$url           = add_query_arg( 'filter', 'paid' );
		$count         = count( $this->paidRows );
		$views['paid'] = "<a href='{$url }' {$class} >Paid <span class='count'>($count)</span></a>";

		// Not joined link
		$class               = ( $current == 'notjoined' ? ' class="current"' : '' );
		$url                 = add_query_arg( 'filter', 'notjoined' );
		$count               = count( $this->notJoinedRows );
		$views['not_joined'] = "<a href='{$url }' {$class} >Not Joined <span class='count'>($count)</span></a>";

		// No Payment
		$class               = ( $current == 'nopayment' ? ' class="current"' : '' );
		$url                 = add_query_arg( 'filter', 'nopayment' );
		$count               = count( $this->noPayment );
		$views['no_payment'] = "<a href='{$url }' {$class} >No Payment <span class='count'>($count)</span></a>";

		// Cancelled DD link
		$class                = ( $current == 'cancelleddd' ? ' class="current"' : '' );
		$url                  = add_query_arg( 'filter', 'cancelleddd' );
		$count                = count( $this->cancelledRows );
		$views['cancelleddd'] = "<a href='{$url }' {$class} >Cancelled DD <span class='count'>($count)</span></a>";

		// Supporters
		$class               = ( $current == 'supporters' ? ' class="current"' : '' );
		$url                 = add_query_arg( 'filter', 'supporters' );
		$count               = count( $this->supportersRows );
		$views['supporters'] = "<a href='{$url }' {$class} >Supporters <span class='count'>($count)</span></a>";

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

	function column_dd_status( $item ) {
		$status = ucwords ( $item['dd_status'] );
		$class = str_replace(' ', '_', $status);
		return "<span class='dd_$class'>$status</span>";
	}

	function column_joined( $item ) {
		$return = $item ['joined'] ? 'Yes' : 'No';

		return "<span class='memForm_$return'>$return</span>";
	}

	function column_fullname( $item ) {
		return "<a href=' " . admin_url( "admin.php?page=players&user_id={$item[ 'user_id' ]}'" ) . ">" . $item ['fullname'] . "</a>";
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

