<?php

class Players_No_Mem_form extends WP_List_Table_Copy
{
      private $users;
      
      public static $singular = 'user';
      public static $plural = 'users';
      
      function __construct()
      {
        // Get users from Wordpress database
        $users = get_users();
        
        // Create table data array
        $data = array();
        foreach ( $users as $user )
        {
          $membership_form = new WP_Query ( array (
                     'post_type' => 'membership_form',
                     'posts_per_page' => 1,
                     'orderby'   => 'date',
                     'order'     => 'ASC',
                     'author' => $user->data->ID
                        ) );

          $recent_emails = new WP_Query ( array (
            'post_type' => 'email_log',
            'posts_per_page' => 1,
            'orderby'       => 'date',
            'order'         => 'DESC',
             'meta_key'   => 'user_id',
             'meta_value' => $user->id
          ));         
            if ( $recent_emails->have_posts() ) 
            {
                $loghtml = '<ul>';
               
                while ( $recent_emails->have_posts() )
                {
                    $recent_emails->the_post();
                    $loghtml .= "<li><strong>Template: </strong>".get_post_meta(get_the_id(), 'template', true)." <br />";
                    $loghtml .= "<strong>Timestamp:</strong> ".get_the_date('g:i:a d/m/Y')."<br />";
                    $loghtml .= "<strong>Status:</strong> ".ucfirst ( get_post_meta(get_the_id(), 'status', true) )."<br />";
                    $loghtml .= get_post_meta(get_the_id(), 'reject_reason', true) ? "<strong>Reject Reason</strong> ".get_post_meta(get_the_id(), 'reject_reason', true)."<br />" : '';
                    $loghtml .= "</li>";
                }
                $loghtml .= '</ul>';
            }
            else
            {
                $loghtml = 'Empty';   
            }
            
            
            if ( ! $membership_form->have_posts() )       
            {
                $data[] = array(
                    'user_id'                => $user->id,
                    'name'              => $user->data->display_name,
                    'dateReg'   => reformat_date( $user->data->user_registered, 'jS \o\f F Y' ),
                    'type'              => $user->roles[0],
                    'email'             => $user->data->user_email,
                    'last_email' => $loghtml
                );
            }
        }
        $this->data = $data;
        
        
        parent::__construct(
            array('singular'    =>  Players_No_Mem_form::$singular,
                  'plural'      =>  Players_No_Mem_form::$plural)
        );
      }
      
    function get_columns()
    {
            $columns = array(
                  'cb'  => '<input type="checkbox" />',
                  'name' => 'Name',
                  'dateReg' => 'Date Registered',
                  'type' => 'Type',
                  'email' => 'Email',
                  'last_email' => 'Last Email'
            );
            
            return $columns; 
      }
      
      function usort_reorder( $a, $b )
      {
        // If no sort, default to date
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'dateReg';
        
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
                  'name' => array('name', false),
                  'dateReg' => array('dateReg', false),
                  'type' =>  array('type', false),
                  'email' => array('email', false),
                  );
            return $columns;
      }
      function prepare_items()
      {
          
           $per_page = $this->get_items_per_page('awaiting_users_per_page', 5);;
           $current_page = $this->get_pagenum();
           $total_items = count($this->data);
            usort( $this->data, array( &$this, 'usort_reorder' ) );
           $this->found_data = array_slice($this->data,(($current_page-1)*$per_page),$per_page);
            
           $this->set_pagination_args( array(
              'total_items' => $total_items,                  //WE have to calculate the total number of items
              'per_page'    => $per_page                     //WE have to determine how many items to show on a page
           ) );
           $this->items = $this->found_data;

            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden, $sortable);
            $this->items = $this->found_data;  
      }
      
     function get_bulk_actions() {
      $actions = array(
        'reset_pass'    => 'Reset Password',
        'resend_welcome'    => 'Resend Welcome Email',
        'bulk_email'    => 'Send Email',
        'send_membership_due_email' => 'Membership Due Email',
        'send_membership_followup_email' => 'Membership Followup Email'
      );
      return $actions;
      }
      
      function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="user_id[]" value="%s" />', $item['user_id']
        );    
    }
      
    function column_default( $item, $column_name )
      {
            
            switch ( $column_name )
            {
                  case 'name':
                  case 'dateReg':
                  case 'type':
                  case 'email':
                    return $item [ $column_name ];
                  default:
                        new dBug ( $item );
            }
      }
      
      function column_last_email ( $item )
      {
          $actions = array ( 'All Emails' => sprintf('<a href="%s">All Emails</a>', admin_url('admin.php?page=email&user_id='.$item['user_id'])));
          return sprintf('%1$s %2$s', $item['last_email'], $this->row_actions($actions) );
      }
}

