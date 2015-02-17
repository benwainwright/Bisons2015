<?php

function custom_taxonomies() {

    /*
     * Create 'Seasons' taxonomy
     */
    register_taxonomy(
        'seasons',
        'fixture',
        array(
            'hierarchical' => false,
            'labels' => array(
            'name' => _x( 'Seasons', 'taxonomy general name', 'bisonsrfc'  ),
            'singular_name' => _x( 'Season', 'taxonomy singular name', 'bisonsrfc'  ),
            'search_items' =>  __( 'Search Seasons', 'bisonsrfc'  ),
            'all_items' => __( 'All Seasons', 'bisonsrfc'  ),
            'edit_item' => __( 'Edit Season', 'bisonsrfc'  ),
            'update_item' => __( 'Update Season', 'bisonsrfc'  ),
            'add_new_item' => __( 'Add New Season', 'bisonsrfc'  ),
            'new_item_name' => __( 'New Season Name', 'bisonsrfc'  ),
            'menu_name' => __( 'Seasons', 'bisonsrfc'  ),
            )
        )
    );
    
    

    /*
     * Create separate categories for committee and player pages
     */
    register_taxonomy(
        'committee-page-groups',
        'committee-page',
        array(
            'hierarchical' => false,
            'labels' => array(
                'name' => _x( 'Page Groups', 'taxonomy general name', 'bisonsrfc'  ),
                'singular_name' => _x( 'Page Group', 'taxonomy singular name', 'bisonsrfc'  ),
                'search_items' =>  __( 'Search Page Groups', 'bisonsrfc'  ),
                'all_items' => __( 'All Page Groups', 'bisonsrfc'  ),
                'edit_item' => __( 'Edit Page Group', 'bisonsrfc'  ),
                'update_item' => __( 'Update Page Group', 'bisonsrfc'  ),
                'add_new_item' => __( 'Add New Page Group', 'bisonsrfc'  ),
                'new_item_name' => __( 'New Page Group Name', 'bisonsrfc'  ),
                'menu_name' => __( 'Page Groups', 'bisonsrfc'  ),
            )
        )
    );
    register_taxonomy(
        'player-page-groups',
        'player-page',
        array(
            'hierarchical' => false,
            'labels' => array(
                'name' => _x( 'Page Groups', 'taxonomy general name', 'bisonsrfc'  ),
                'singular_name' => _x( 'Page Group', 'taxonomy singular name', 'bisonsrfc'  ),
                'search_items' =>  __( 'Search Page Groups', 'bisonsrfc'  ),
                'all_items' => __( 'All Page Groups', 'bisonsrfc'  ),
                'edit_item' => __( 'Edit Page Group', 'bisonsrfc'  ),
                'update_item' => __( 'Update Page Group', 'bisonsrfc'  ),
                'add_new_item' => __( 'Add New Page Group', 'bisonsrfc'  ),
                'new_item_name' => __( 'New Page Group Name', 'bisonsrfc'  ),
                'menu_name' => __( 'Page Groups', 'bisonsrfc'  ),
            )
        )
    );


}
add_action('init', 'custom_taxonomies');