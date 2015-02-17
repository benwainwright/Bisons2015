<?php
if (!INCLUDED) exit;
if ( $_POST['confirm_action'] == 'true')
{
    if ( isset ( $_POST['user_id'] ) )
    {
        $usercount = 0;
        $errorcount = 0;
        
        new dBug ( stripslashes( ( $_POST['user_id']) ) );
        
        $user_ids = ( @unserialize( stripslashes( ( $_POST['user_id']) ) ) !== false ) ? unserialize( stripslashes( ( $_POST['user_id']) ) ) :  $_POST['user_id'];
        
        
        
        foreach ($user_ids as $id) 
        {
            $current_form = new WP_Query ( array (
                'post_type' => 'membership_form',
                'posts_per_page' => 1,  
                'orderby'   => 'date',
                'order'     => 'ASC',   
                'author'    => $id
            ));
            $result = false;
            while ( $current_form->have_posts() ) 
            {
                    $current_form->the_post();
                   
                    if ( wp_delete_post( get_the_id())  )
                    {
                        $usercount++;
                    }
                    else 
                    {
                        $errorcount++;   
                    }
            } 
   
        }
        if ( $usercount > 0 )
        {
            function delete_mem_form_error_notice() 
            {
                echo '<div class="error">';
                if ( $usercount == 1 )
                {
                    echo "<p>There was a problem deleting this membership form.</p>";    
                }
                else 
                {
                    echo "<p>There was a problem deleting $sentcount membership forms.</p>";    
                }
                
                echo '</div>';
            }
            add_action('admin_notices', 'delete_mem_form_error_notice');
        }
     
        if ( $errorcount > 0 )
        {
            function delete_mem_form_update_notice() 
            {
                echo '<div class="updated">';
                if ( $errorcount == 1 )
                {
                    echo "<p>Membership form deleted successfully.</p>";    
                }
                else 
                {
                    echo "<p>$sentcount membership forms deleted successfully.</p>";    
                }
                
                echo '</div>';
            }
            add_action('admin_notices', 'delete_mem_form_update_notice');
        } 
    } 
}