<?php

function email_pending ( $post_id )
{
      if ( wp_is_post_revision ( $post_id) )
            return;
      
      
}

add_action ( 'save_post', 'email_pending' );