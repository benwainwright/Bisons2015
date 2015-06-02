<?php
class GCLBillsTable extends WP_List_Table_Copy
{
	private $data;

	public static $singular = 'bill';
	public static $plural = 'bills';


	function __construct()
	{
		$query_args = array( 'post_type' => 'GCLBillLog', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'ASC');

		if ( isset($_GET['user_id']) ) {
			$query_args['author'] = $_GET['user_id'];
		}


		$query = new WP_Query($query_args);

		$data = array();
		while ($query->have_posts()) {
			$query->the_post();
			global $post;
			$row = array();

			$row['date'] = get_the_date();
			$row['user'] = get_the_author();
			$row['userID'] = $post->post_author;
			$row['source_id'] = get_post_meta(get_the_id(), 'source_id',true);
			$row['status'] = get_post_meta(get_the_id(), 'status',true);
			$row['amount'] = get_post_meta(get_the_id(), 'amount',true);
			$row['amount_minus_fees'] = get_post_meta(get_the_id(), 'amount_minus_fees',true);
			$row['source_type'] = get_post_meta(get_the_id(), 'source_type',true);

			$data[] = $row;
		}

		$this->data = $data;

		parent::__construct(
			array('singular'    =>  GCLBillsTable::$singular,
			      'plural'      =>  GCLBillsTable::$plural)
		);


	}

	function get_columns()
	{
		return array(
			'cb'                      => '<input type="checkbox" />',
			'date'                    => 'Date',
			'user'                    => 'User',
			'source_id'               => 'Source ID',
			'status'                  => 'Status',
			'amount'                  => 'Amount',
			'amount_minus_fees'       => 'Amount minus fees',
			'source_type'             => 'source_type'
		);
	}

	function usort_reorder( $a, $b )
	{
		// If no sort, default to date
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'datae';

		// If no order, default to asc
		$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';

		// Determine sort order
		$result = strcmp( $a[$orderby], $b[$orderby] );

		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : -$result;
	}

	function get_sortable_columns()
	{
		return array(
			'date'                    => array('date', false),
			'user'                    => array('user', false),
			'status'                  => array('status', false),
			'amount'                  => array('amount', false),
			'amount_minus_fees'       => array('amount_minus_fees', false),
			'source_type'             => array('source_type', false)
		);
	}

	function prepare_items()
	{


		$this->_column_headers = $this->get_column_info();

		usort( $this->data, array( &$this, 'usort_reorder' ) );

		$total_items = count($this->data);
		$per_page = $this->get_items_per_page('bills_per_page', 5);

		$current_page = $this->get_pagenum();
		$this->found_data = array_slice($this->data,(($current_page-1)*$per_page),$per_page);
		$this->set_pagination_args( array(
			'total_items' => $total_items,                  //WE have to calculate the total number of items
			'per_page'    => $per_page                     //WE have to determine how many items to show on a page
		) );
		$this->items = $this->found_data;

	}

	function column_default( $item, $column_name )
	{

		switch ( $column_name )
		{
			case 'amount': case 'amount_minus_fees':
				setlocale(LC_MONETARY, 'en_GB.UTF-8');
				return money_format( '%n', $item [ $column_name ]);
			break;

			case 'user':
				$return = isset($_GET['user_id'])
					? $item [$column_name]
					: "<a href=' " . admin_url("admin.php?page=bills&user_id={$item[ 'userID' ]}'") . ">" .$item [$column_name] . "</a>";
				return $return;
			break;


			case 'status':
				return ucwords ( $item [ $column_name ] );
			break;

			case 'source_type':
				return ucwords ( $item [ $column_name ] );

			case 'date':

			case 'source_id':
				return $item [ $column_name ];
			break;

			default:
				new dBug ( $item );
		}
	}

	function column_cb($item) {
		return sprintf(
			'<input type="checkbox" name="user_id[]" value="%s" />', $item['user_id']
		);
	}

}