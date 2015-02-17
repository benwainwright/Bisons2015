<?php
function prepare_pages( $the_query )
{
    
    if ( $the_query->is_main_query() )
    {
        if ( ! is_object ( $the_query ) ) 
            return false;
        
        switch ( $the_query->query['post_type'] )
        {
       
            case 'player-page': 
                if( file_exists( dirname( __FILE__  ) . '/../prep_player_pages/' .  $the_query->query['name'] . '.php' ) )
                   include_once( dirname( __FILE__  ) . '/../prep_player_pages/' . $the_query->query['name'] . '.php' ); 
            break;
            
        }
    }
}
add_action ( 'pre_get_posts', 'prepare_pages' );
