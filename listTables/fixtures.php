<?php

class Fixtures_Table extends WP_List_Table_Copy {
	private $fixtures;

	function __construct() {


		$query_vars = array(
			'post_type'      => 'fixtures',
			'posts_per_page' => $this->get_items_per_page( 'fixtures_per_page', 5 )
		);

		if ( isset ( $_GET['paged'] ) ) {
			$query_vars['offset'] = ( $_GET['paged'] - 1 ) * $this->get_items_per_page( 'fixtures_per_page', 5 );
		}

		if ( isset ( $_GET['orderby'] ) ) {


			switch ( $_GET['orderby'] ) {
				case "opposingTeam" :
					$query_vars['orderby']  = "meta_value";
					$query_vars['meta_key'] = "fixture-opposing-team";
					break;


				case "date" :
					$query_vars['orderby']  = "meta_value_num";
					$query_vars['meta_key'] = "fixture-date";
					break;

				case "author" :
					$query_vars['orderby'] = "author";
					break;


				case "postDate" :
					$query_vars['orderby'] = "date";
					break;

				case "postDate" :
				default :
					$query_vars['orderby'] = "date";
					break;
			}
		} else {
			$query_vars['orderby']  = "meta_value_num";
			$query_vars['meta_key'] = "fixture-date";
		}

		if ( isset ( $_GET['order'] ) ) {
			if ( $_GET['order'] == 'ASC' ) {
				$query_vars['order'] = 'asc';
			} else {
				$query_vars['order'] = 'DESC';
			}
		} else {
			$query_vars['order'] = 'DESC';
		}

		if (isset ($_GET['season']) ) {
			$tax_query = array(
				array(
					'taxonomy' => 'seasons',
					'field'    => 'slug',
				)
			);

			if ( 'current' === $_GET['season'] ) {
				$taxonomy = get_terms( 'seasons' );
				foreach ( $taxonomy as $tax ) {
					$taxesLight[] = $tax->slug;
				}
				$tax_query[0]['terms']    = $taxesLight;
				$tax_query[0]['operator'] = 'NOT IN';
			} else {
				$tax_query[0]['terms']    = array( $_GET['season'] );
				$tax_query[0]['operator'] = 'IN';
			}

			if( 'all' !== $_GET['season']) {
				$query_vars['tax_query'] = $tax_query;
			}

		}


		// Get fixtures from Wordpress database
		$fixtures_data     = new WP_Query( $query_vars );
		$this->total_items = $fixtures_data->found_posts;

		// Loop through fixtures data and insert into an array
		$fixtures = array();
		while ( $fixtures_data->have_posts() ) {
			$fixtures_data->the_post();
			$seasons    = wp_get_post_terms( get_the_id(), 'seasons' );
			$season     = isset ( $seasons[0]->name ) ? $seasons[0]->name : "Current";
			$fixtures[] = array(
				'homeAway'     => get_post_meta( get_the_id(), 'fixture-home-away', true ),
				'kickOffDate'  => date( 'd/m/Y', (int) get_post_meta( get_the_id(), 'fixture-date', true ) ),
				'kickoffTime'  => get_post_meta( get_the_id(), 'fixture-kickoff-time',
					true ) ? get_post_meta( get_the_id(), 'fixture-kickoff-time', true ) : 'TBC',
				'opposingTeam' => get_post_meta( get_the_id(), 'fixture_team',
					true ) ? get_the_title( get_post_meta( get_the_id(), 'fixture_team', true ) ) : 'No Team',
				'author'       => get_the_author(),
				'postDate'     => get_the_time( 'd/m/Y' ),
				'season'       => $season,
				'id'           => get_the_id(),
				'permalink'    => get_the_permalink()
			);
		}

		// Create match results query
		$getresultsquery = new WP_Query( array(
			'post_type' => 'results',
			'nopaging'  => 'true'
		) );
		// Loop over results, store in an array
		while ( $getresultsquery->have_posts() ) : $getresultsquery->the_post();

			$results[] = array(
				'result_id'      => get_the_id(),
				'parent-fixture' => get_post_meta( get_the_id(), 'parent-fixture', true ),
				'their-score'    => get_post_meta( get_the_id(), 'their-score', true ),
				'our-score'      => get_post_meta( get_the_id(), 'our-score', true )
			);
		endwhile;

		// Iterate through fixtuers and look for results
		foreach ( $fixtures as &$fixture ) {
			foreach ( $results as $result ) {
				if ( $fixture['id'] == $result['parent-fixture'] ) {
					$fixture['result_id'] = $result['result_id'];
					$fixture['result']    = 'Us: <strong>' . $result['our-score'] . '</strong><br />Them: <strong>' . $result['their-score'] . '</strong>';
				}
			}

			$fixture['result'] = ( ! isset ( $fixture['result'] ) ) ? '<em>TBC</em>' : $fixture['result'];
		}
		$this->fixtures = $fixtures;

		parent::__construct();
	}

	function get_columns() {
		$columns = array(
			'cb'           => '<input type="checkbox" />',
			'opposingTeam' => 'Opposing Team',
			'kickOffDate'  => 'Kickoff',
			'result'       => 'Score',
			'author'       => 'Author',
			'postDate'     => 'Post Date',
			'season'       => 'Season'
		);

		return $columns;
	}

	function extra_tablenav($which) {

		$seasons = get_terms ( array ( 'seasons' ) );


		if (is_array($seasons) && count($seasons) > 0) :?>
		<div class="alignleft actions">
			<form action='<?php echo admin_url('admin.php?page=fixturelist') ?>' method="GET">
				<input type="hidden" name="page" value="fixturelist" />
				<select name="season">
					<option value="all">All Seasons</option>
					<option <?php selected($_GET['season'], 'current') ?>value="current">Current</option>
					<?php foreach ($seasons as $season) : ?>
					<option <?php selected($_GET['season'], $season->slug) ?>value="<?php echo $season->slug ?>"><?php echo $season->name ?></option>
					<?php endforeach ?>
				</select>
				<button class="button action">Filter</button>
			</form>
		</div>
		<?php endif;
	}
	function usort_reorder( $a, $b ) {
		// If no sort, default to date
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'date';

		// If no order, default to asc
		$order = ( ! empty( $_GET['order'] ) ) ? $_GET['order'] : 'asc';

		// Determine sort order
		$result = strcmp( $a[ $orderby ], $b[ $orderby ] );

		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : - $result;
	}

	function get_sortable_columns() {
		$columns = array(
			'opposingTeam' => array( 'opposingTeam', false ),
			'homeAway'     => array( 'homeAway', false ),
			'kickOffDate'  => array( 'kickOffDate', false ),
			'author'       => array( 'author', false ),
			'postDate'     => array( 'postDate', false )
		);

		return $columns;
	}

	function prepare_items() {

		foreach($this->fixtures as $fixture) {

			if('current' === $fixture['season']) {
				$this->current_season[] = $fixture;
			}

			else {
				$this->past_seasons[] = $fixture;
			}
		}

		$per_page     = $this->get_items_per_page( 'fixtures_per_page', 5 );
		$current_page = $this->get_pagenum();
		$this->set_pagination_args( array(
			'total_items' => $this->total_items,                  //WE have to calculate the total number of items
			'per_page'    => $per_page                     //WE have to determine how many items to show on a page
		) );


		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items           = $this->fixtures;
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'kickoffTime':
			case 'opposingTeam':
			case 'author':
			case 'postDate':
			case 'season':
			case 'result':
				return $item [ $column_name ];
			case 'kickOffDate':
				return $item ['kickOffDate'] . '<br /><strong>' . $item ['kickoffTime'] . '</strong>';
			default:
				new dBug ( $item );
		}
	}

	function column_cb($item) {
		return sprintf(
			'<input type="checkbox" name="id[]" value="%s" />', $item['id']
		);
	}

	function get_bulk_actions() {
		$actions = array(
			'set_season'    => 'Set Season',
		);
		return $actions;
	}



	function column_opposingTeam( $item ) {
		  $actions = array(
			  'edit'   => '<a href=\'' . home_url( 'wp-admin/post.php?post=' . $item['id'] . '&action=edit' ) . '\'>Edit</a>',
			  'view'   => '<a href=\'' . $item['permalink'] . '\'>View</a>',
			  'delete' => '<a href=\'\'>Delete</a>'
		  );

		  return sprintf( '%1$s %2$s', $item['opposingTeam'] . "<br /><strong>" . $item['homeAway'] . "</strong>",
			  $this->row_actions( $actions ) );
	  }

	  function column_result( $item ) {

		  if ( isset ( $item['result_id'] ) ) {
			  $actions = array(
				  'Edit score' => '<a href=\'' . admin_url( 'post.php?post=' . $item['result_id'] . '&action=edit' ) . '\'>Edit Score</a>',
			  );
		  } else {
			  $actions = array(
				  'Add score' => '<a href=\'' . admin_url( 'post-new.php?post_type=results&parent_post=' . $item['id'] ) . '\'>Add Score</a>',
			  );
		  }

		  return sprintf( '%1$s %2$s', $item['result'], $this->row_actions( $actions ) );

	  }
}

