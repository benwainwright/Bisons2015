<?php

function create_advanced_posting_layout ( )
{
    /**
     * Simplify the posting screen unless a user role has the 'advanced_posting_layout' permission
     */
    if( !current_user_can( 'advanced_posting_layout' ) ) {
        remove_post_type_support( 'post', 'author');
        remove_post_type_support( 'post', 'trackbacks');
        remove_post_type_support( 'post', 'custom-fields');
        remove_post_type_support( 'post', 'comments');
        remove_post_type_support( 'post', 'revisions');
        remove_post_type_support( 'post', 'page-attributes');
        remove_post_type_support( 'post', 'formats');   
        remove_post_type_support( 'post', 'excerpt');
        
        remove_post_type_support( 'page', 'author');
        remove_post_type_support( 'page', 'trackbacks');
        remove_post_type_support( 'page', 'custom-fields');
        remove_post_type_support( 'page', 'comments');
        remove_post_type_support( 'page', 'revisions');
        remove_post_type_support( 'page', 'page-attributes');
        remove_post_type_support( 'page', 'formats');   
        remove_post_type_support( 'page', 'excerpt');           
        
        remove_post_type_support( 'fixture', 'author');
        remove_post_type_support( 'fixture', 'trackbacks');
        remove_post_type_support( 'fixture', 'custom-fields');
        remove_post_type_support( 'fixture', 'comments');
        remove_post_type_support( 'fixture', 'revisions');
        remove_post_type_support( 'fixture', 'page-attributes');
        remove_post_type_support( 'fixture', 'formats');
        remove_post_type_support( 'fixture', 'excerpt');

        remove_post_type_support( 'event', 'author');
        remove_post_type_support( 'event', 'trackbacks');
        remove_post_type_support( 'event', 'custom-fields');
        remove_post_type_support( 'event', 'comments');
        remove_post_type_support( 'event', 'revisions');
        remove_post_type_support( 'event', 'page-attributes');
        remove_post_type_support( 'event', 'formats');
        remove_post_type_support( 'event', 'excerpt');

        remove_post_type_support( 'report', 'author');
        remove_post_type_support( 'report', 'trackbacks');
        remove_post_type_support( 'report', 'custom-fields');
        remove_post_type_support( 'report', 'comments');
        remove_post_type_support( 'report', 'revisions');
        remove_post_type_support( 'report', 'page-attributes');
        remove_post_type_support( 'report', 'formats'); 
        remove_post_type_support( 'report', 'excerpt');     
        
        remove_post_type_support( 'result', 'author');
        remove_post_type_support( 'result', 'trackbacks');
        remove_post_type_support( 'result', 'custom-fields');
        remove_post_type_support( 'result', 'comments');
        remove_post_type_support( 'result', 'revisions');
        remove_post_type_support( 'result', 'page-attributes');
        remove_post_type_support( 'result', 'formats'); 
        remove_post_type_support( 'result', 'excerpt');

    } 
}
add_action ( 'init', 'create_advanced_posting_layout');