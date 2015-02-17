<?php

class Fixtures_Table extends WP_List_Table_Copy
{
      private $fixtures;
      
      function __construct()
      {
            // Get fixtures from Wordpress database
		$fixtures_data = new WP_Query(array(
                  'post_type' => 'fixture',
                  'orderby'   => 'meta_value',
                  'meta_key'  => 'fixture-date',
                  'order'     => 'ASC'
		));

            // Loop through fixtures data and insert into an array
            $fixtures = array();
            while ( $fixtures_data->have_posts() ) 
            {
                  $fixtures_data->the_post();
                  $seasons = wp_get_post_terms( get_the_id(), 'seasons');
                  $season = isset ( $seasons[0]->name ) ? $seasons[0]->name : "Current" ;
                  $fixtures[] = array(
                        'homeAway' => get_post_meta(get_the_id(), 'fixture-home-away', true),
                        'date' => date('jS \o\f F Y', (int) get_post_meta( get_the_id(), 'fixture-date', true ) ),
                        'kickoffTime' => get_post_meta( get_the_id(), 'fixture-kickoff-time', true ) ? get_post_meta( get_the_id(), 'fixture-kickoff-time', true ) : 'TBC',
                        'opposingTeam' => get_post_meta( get_the_id(), 'fixture-opposing-team', true ) ? get_post_meta( get_the_id(), 'fixture-opposing-team', true ) : 'TBC',
                        'author' => get_the_author(),
                        'postDate' => get_the_time('jS \o\f F Y'),
                        'season' => $season,
                        'id' => get_the_id(),
                        'permalink' => get_the_permalink()
                  );
            }

            $this->fixtures = $fixtures;
            parent::__construct();
      }
      
	function get_columns()
      {
            $columns = array(
                  'opposingTeam' => 'Opposing Team',
                  'homeAway' => 'Home or Away',
                  'date' => 'Date',
                  'kickoffTime' => 'Kickoff',
                  'author' => 'Author',
                  'postDate' => 'Post Date',
                  'season' => 'Season'
            );
            
            return $columns; 
      }
      
      function usort_reorder( $a, $b )
      {
  		// If no sort, default to date
  		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'date';
  		
            // If no order, default to asc
  		$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
  		
            // Determine sort order
  		$result = strcmp( $a[$orderby], $b[$orderby] );
  		
            // Send final sort direction to usort
 		return ( $order === 'asc' ) ? $result : -$result;
	}
      
      function get_sortable_columns()
      {
            $columns = array(
                  'opposingTeam' => array('opposingTeam', false),
                  'homeAway' => array('homeAway', false),
                  'date' =>  array('date', false),
                  'kickoffTime' => array('kickoffTime', false),
                  'author' => array('author', false),
                  'postDate' => array('postDate', false),
                  'season' => array('season', false),
                  );
            return $columns;
      }
      function prepare_items()
      {
            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden, $sortable);
  		usort( $this->fixtures, array( &$this, 'usort_reorder' ) );
            $this->items = $this->fixtures;  
      }
      
	function column_default( $item, $column_name )
      {
            switch ( $column_name )
            {
                  case 'homeAway':
                  case 'date':
                  case 'kickoffTime':
                  case 'opposingTeam':
                  case 'author':
                  case 'postDate':
                  case 'season':
                  	return $item [ $column_name ];
                  default:
                        new dBug ( $item );
            }
      }
      
      function column_opposingTeam ($item)
      {
             $actions = array(
                  'edit' => '<a href=\''.home_url('wp-admin/post.php?post='.$item['id'].'&action=edit').'\'>Edit</a>',
                  'view' => '<a href=\''.$item['permalink'].'\'>View</a>',
                  'delete' => '<a href=\'\'>Delete</a>'
             );
             return sprintf('%1$s %2$s', $item['opposingTeam'], $this->row_actions($actions) );

      }
}

