<?php

class Email_Log_Tables extends WP_List_Table_Copy
{
      public static $singular = 'email';
      public static $plural = 'emails';
      
      function __construct()
      {
          // Load and filter pagination and ordering information from the querystring    
          $paged = filter_input(INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT);
          $order = ($_GET['order'] == 'asc') ? 'ASC' : 'DESC';
          $paged = $paged ? $paged : 1;
          
          $query = array (
            'post_type' => 'email_log',
            'posts_per_page' => $this->get_items_per_page('emails_per_page', 5),
            'order' => $order,
            'paged' => $paged
          );
          
          
          switch ( $_GET['orderby'])
          {
              case "date": $query['orderby'] = 'date'; break;
              case "name": $query['orderby'] = 'meta_value'; $query['meta_key'] = 'user_name'; break;
              case "status": $query['orderby'] = 'meta_value'; $query['meta_key'] = 'status'; break;
              case "template": $query['orderby'] = 'meta_value'; $query['meta_key'] = 'template'; break;
          }
          
          if ( $_GET['user_id'] )
          {
            $query['meta_query'] = array(
                array(
                    'key'     => 'user_id',
                    'value'   => $_GET['user_id'],
                    'compare' => '='
                )
            );
          }
          
          // Load from Wordpress
          $emails = new WP_Query ( $query );
          // Save the total number of posts so I can use it when preparing posts
          $this->post_count = $emails->found_posts;
          
          
          // Populate data table
          while ( $emails->have_posts() )
          {
              $emails->the_post();
              
              
              // If there are errors, insert them into the 'status' column.
              if ( get_post_meta(get_the_id(), 'reject_reason', true) )
              {
                  $status = ucfirst ( get_post_meta(get_the_id(), 'status', true) ).
                            '<br /><em>'.
                            ucfirst ( get_post_meta(get_the_id(), 'reject_reason', true) ).
                            '</em>';
                            
              }
              else 
              {
                  $status = ucfirst ( get_post_meta(get_the_id(), 'status', true) );    
              }
              
              if ( $clicks = get_post_meta( get_the_id(), 'clicks', true) )
              {
                $click_html = '<ul>';
                foreach ( $clicks as $click )
                    $click_html .= '<li>'.$click['url'].'</li>';     
                $click_html .= '</ul>';
              }
              else $click_html = 'None';
              
              $data[] = array(
                'timestamp'         => get_the_date('g:i:a, jS \o\f F Y'),
                'post_id'           => get_the_id(),
                'recipient_name'    => get_post_meta(get_the_id(), 'user_name', true) ? get_post_meta(get_the_id(), 'user_name', true) : 'None given',
                'user_id'           => get_post_meta(get_the_id(), 'user_id', true),
                'mandrill_id'       => get_post_meta(get_the_id(), 'email_id', true),
                'clicks'            => $click_html,
                'email_address'     => get_post_meta(get_the_id(), 'email', true),
                'status'            => $status,
                'template'          => get_post_meta(get_the_id(), 'template', true),
                'merge_data'        => get_post_meta(get_the_id(), 'merge_data', true)
              );
          }

          // Save data
          $this->data = $data;
          
          
          // Run parent constructor
          parent::__construct(
            array('singular'    =>  Email_Log_Tables::$singular,
                  'plural'      =>  Email_Log_Tables::$plural,
                  'ajax'        => false)
          );
      }
      
      // Setup columns
      function get_columns()
      {
          $columns = array(
            'timestamp'         => 'Date',
            'recipient_name'    => 'Name',
            'email_address'     => 'Email',
            'status'            => 'Status',
            'template'          => 'Template',
            'clicks'            => 'Clicks'
          );    
          
          if ( isset ( $_GET['user_id']) ) unset ($columns['recipient_name']);
          return $columns;
      }
      
      // Indicate which columns can be sorted
      function get_sortable_columns()
      {
            $columns = array(
                    'timestamp'       => array('date', false),
                    'recipient_name'  => array('name', false),
                    'email_address'   => array('email', false),
                    'status'          => array('status', false),
                    'template'        => array('template', false),
                  );
            return $columns;
      }
      
      function prepare_items()
      {
            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden, $sortable);
            
             
            // Set pagination arguments (does what it says on the tin)
            $this->set_pagination_args( array(
                'total_items'   =>    $this->post_count,
                'per_page'      =>    $this->get_items_per_page('emails_per_page', 5) 
            ));
            
            // Save data into the items variable */
            $this->items = $this->data; 
            
      }
      
      // Determine what to do with the data in each column
      function column_default ( $item, $column_name )
      {
          switch ( $column_name )
          {
              case 'timestamp':
              case 'recipient_name':
              case 'status':
              case 'template':
              case 'email_address':
              case 'clicks':
                return $item [ $column_name ];
              default:
                  new dBug ( $item );
          }
      }
}
