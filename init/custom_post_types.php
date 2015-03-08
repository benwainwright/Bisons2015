<?php



// Register post types with Wordpress
function create_post_types() {
    register_post_type( 'fixtures', array(
        'labels' => array (
            'name' => __( 'Fixtures', 'bisonsrfc'  ),
            'singular_name' => __( 'Fixture', 'bisonsrfc'  ),
            'add_new_item' => __( 'Add new fixture', 'bisonsrfc' ),
            'edit_item' => __( 'Edit fixture', 'bisonsrfc' ),
            'view_item' => __( 'View fixture', 'bisonsrfc' ),
            'search_item' => __( 'Search fixtures', 'bisonsrfc' ),
            ),
        'public' => true,
        'has_archive' => true,
		'show_in_menu' => false,
        'taxonomies' => array('seasons'),
        'supports' => array('thumbnail')
        )
    );
	
	/**
	 * Create 'Teams' post type
	 */
	 
	 register_post_type ('teams', array(
        'labels' => array (
            'name' => __( 'Teams', 'bisonsrfc'  ),
            'singular_name' => __( 'Team', 'bisonsrfc'  ),
            'add_new_item' => __( 'Add new team', 'bisonsrfc' ),
            'edit_item' => __( 'Edit team', 'bisonsrfc' ),
            'view_item' => __( 'View team', 'bisonsrfc' ),
            'search_item' => __( 'Search teams', 'bisonsrfc' ),
            ),
        'show_in_menu' => false,
		'public' => true,
		'has_archive' => false,
		'supports'	=> array ('thumbnail', 'title')
	 ));
    
    
    register_post_type( 'playerprofiles', array(
        'labels' => array (
            'name' => __( 'Player Profiles', 'bisonsrfc' ),
            'singular_name' => __( 'Player Profile', 'bisonsrfc' ),
            'add_new_item' => __( 'Add new player profile', 'bisonsrfc' ),
            'edit_item' => __( 'Edit player profile', 'bisonsrfc' ),
            'view_item' => __( 'View player profile', 'bisonsrfc' ),
            'search_item' => __( 'Search player profiles', 'bisonsrfc' ),
            ),
        'public' => true,
        'show_in_menu' => false,
        'has_archive' => true,
        'menu_position' => 3,
        'supports' => array('comments', 'title', 'thumbnail')
        )
    );
    /**
      * Create 'Events' post type
      */
    register_post_type( 'events', array(
        'labels' => array (
            'name' => __( 'Events', 'bisonsrfc' ),
            'singular_name' => __( 'Event', 'bisonsrfc' ),
            'add_new_item' => __( 'Add new event', 'bisonsrfc' ),
            'edit_item' => __( 'Edit event', 'bisonsrfc' ),
            'view_item' => __( 'View event', 'bisonsrfc' ),
            'search_item' => __( 'Search events', 'bisonsrfc' ),
            ),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-calendar',
        'supports' => array('comments', 'revisions', 'title', 'editor', 'thumbnail')
        )
    );
    
    /**
     * Create 'results' post type
     */
    register_post_type( 'results', array(
        
        'labels' => array (
            'name' => __( 'Results', 'bisonsrfc' ),
            'singular_name' => __( 'Result', 'bisonsrfc' ),
            'add_new_item' => __( 'Add new result', 'bisonsrfc' ),
            'edit_item' => __( 'Edit result', 'bisonsrfc' ),
            'view_item' => __( 'View result', 'bisonsrfc' ),
            'search_item' => __( 'Search results', 'bisonsrfc' ),
            ),
        'public' => true,
        'show_in_menu' => false,
        'has_archive' => false,
        'hierarchical' => true,
        'menu_position' => 6,
        'supports' => array(
            'page-attributes'
        )
        )
    );



    /*
     *  Create player pages post type
     */
    register_post_type( 'player-page', array(

        'labels' => array (
            'name' => __( 'Player Pages', 'bisonsrfc' ),
            'singular_name' => __( 'Player Page', 'bisonsrfc' ),
            'add_new_item' => __( 'Add new player page', 'bisonsrfc' ),
            'edit_item' => __( 'Edit player page', 'bisonsrfc' ),
            'view_item' => __( 'View player page', 'bisonsrfc' ),
            'search_item' => __( 'Search player page', 'bisonsrfc', 'thumbnail' ),
            ),
        'public' => true,
        'rewrite'=> array('slug' => 'players-area'),
        'show_in_menu' => false,
        'has_archive' => true,
        'hierarchical' => true,
        'menu_position' => 8,

        )

    );
    
        
    
    register_post_type( 'committee-profile', array(
        
        'labels' => array (
            'name' => __( 'Committee Profile', 'bisonsrfc' ),
            'singular_name' => __( 'Committee Profile', 'bisonsrfc' ),
            'add_new_item' => __( 'Add new Committee Profile', 'bisonsrfc' ),
            'edit_item' => __( 'Edit Committee Profile', 'bisonsrfc' ),
            'view_item' => __( 'View Committee Profile', 'bisonsrfc' ),
            'search_item' => __( 'Search Committee Profile', 'bisonsrfc', 'thumbnail' ),
            ),
        'public' => true,
        'show_in_menu' => false,
        'has_archive' => true,
        'hierarchical' => true,
        'supports' => false
            )
    );
    
    
    /**
     * Flickr gallery type
     * Used to index flickr galleries, give them a freindly name and add them to the blog
     * 
     */
    register_post_type( 'photos', array(
        'labels' => array(
            'name' => __( 'Photos', 'bisonsrfc' ),
            'singular_name' => __( 'photo', 'bisonsrfc' ),
        ),
	    'public' => true,
	    'exclude_from_search' => false,
	    'has_archive' => true
	    )
    );
    
    
    register_post_type ( 'membership_form', array(
        'public' => false, 
        'exclude_from_search' => true,
        'has_archive' => false,
    ) );
    
    register_post_type ( 'membership_fee', array(
        'labels' => array (
            'name' => __( 'Membership Fees', 'bisonsrfc' ),
            'singular_name' => __( 'Membership Fee', 'bisonsrfc' ),
            'add_new_item' => __( 'Add new fee', 'bisonsrfc' ),
            'edit_item' => __( 'Edit fee', 'bisonsrfc' ),
            'view_item' => __( 'View fee details', 'bisonsrfc' ),
            'search_item' => __( 'Search membership fees', 'bisonsrfc' ),
            ),

        'public' => true, 
        'exclude_from_search' => true,
        'show_ui' => true,
        'show_in_menu' => false,
        'show_in_nav_menus' => false, 
        'has_archive' => false,
        'supports' => false
    ) );
    
    register_post_type ( 'email_log', array(
        'public' => false, 
        'exclude_from_search' => true,
        'has_archive' => false,
    ) );
    
    register_post_type ( 'webhook', array(
        'public' => false, 
        'exclude_from_search' => true,
        'has_archive' => false,
    ) );

}
add_action( 'init', 'create_post_types');

// Add custom metaboxes to the forms
function add_custom_forms ( $post ) {
    add_meta_box(
        'player-page-edit',
        'Description',
        'player_page_description_form',
        'player-page',
        'normal',
        'core'

    );
    
    add_meta_box(
        'player-profile-edit',
        'Details',
        'player_profile_edit_form',
        'playerprofiles',
        'normal',
        'core'
    );
    
    add_meta_box(
        'com-page-edit',
        'Description',
        'committee_page_description_form',
        'committee-page',
        'normal',
        'core'
    );

    add_meta_box(
        'fixture-edit',
        'Fixture details',
        'fixtures_content',
        'fixtures',
        'normal',
        'high'
    );
	
	add_meta_box(
		'teams',
		'Team details',
		'team_edit_box',
		'teams',
		'normal',
		'high'
	);

    add_meta_box(
        'event-edit',
        'Event details',
        'events_content',
        'events',
        'normal',
        'high'
    );
    
	if ( isset ( $_GET['parent_post']) || isset ( $_GET['post']) )
	{
		
    	$parentpost =  isset ( $_GET['parent_post'] ) ?  $_GET['parent_post'] : get_post_meta( $_GET['post'], 'parent-fixture', true);
    	$fixdate = date('jS \o\f F Y', get_post_meta( $parentpost, 'fixture-date', true ));

	    add_meta_box(
	        'result-edit',
	        'Match Result',
	        'results_content',
	        'results',
	        'normal',
	        'high'
	    );
	}

       add_meta_box(
        'fixture-link-selector',
        'Link to fixture',
        'fixture_link_selector',
        'post',
        'normal',
        'high'
    );
    
    
    if ( current_user_can('attribute_post' ) )
    {
       add_meta_box(
        'attribute-post',
        'Attribute Post',
        'attribute_post',
        'post',
        'normal',
        'high'
        );
    }
    
    
       add_meta_box(
        'committee-profile-edit',
        'Profile',
        'committee_profile',
        'committee-profile',
        'normal',
        'high'
    );
    
    add_meta_box(
        'fees-edit-form',
        'Membership Fees',
        'membership_fee_postform',
        'membership_fee',
        'normal',
        'core'
    );
    
}
add_action( 'add_meta_boxes', 'add_custom_forms');

// Callback functions to print custom form content
function player_profile_edit_form ( $post ) { include_once ( dirname(__FILE__)  . '/../postforms/player-profiles.php' ); }
function fixtures_content( $post ) { include_once( dirname(__FILE__) . '/../postforms/fixtures.php'); }
function events_content( $post ) { include_once( dirname(__FILE__) . '/../postforms/events.php'); }
function results_content( $post ) { include_once( dirname(__FILE__) . '/../postforms/results.php');}
function committee_page_description_form( $post ) { include_once( dirname(__FILE__) . '/../postforms/com-page.php');}
function player_page_description_form( $post ) { include_once( dirname(__FILE__) . '/../postforms/player-page.php'); }
function fixture_link_selector ( $post ) { include_once( dirname(__FILE__) . '/../postforms/post.php'); }
function attribute_post ( $post ) { include_once( dirname(__FILE__) . '/../postforms/post-attribute.php'); }
function committee_profile ( $post ) { include_once( dirname(__FILE__) . '/../postforms/committee-profile.php'); } 
function membership_fee_postform ( $post ) { include_once( dirname(__FILE__) . '/../postforms/memfees.php'); } 
function team_edit_box ( $post ) { include_once( dirname(__FILE__) . '/../postforms/teams.php'); } 


// Include custom post types in main blog
function modify_blog_post_types($query) {
    if( is_home() && $query->is_main_query() || is_feed() )
        $query->set( 'post_type', array('post', 'fixtures', 'results', 'report', 'events', 'photoalbum') );
    return $query;
}
add_filter ('pre_get_posts', 'modify_blog_post_types');

// Exclude fixtures/results marked as hidden from blog
function exclude_hidden( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'meta_query', array(
            array(
                'key'       =>  'hide_from_blog',
                'compare'   =>  'NOT EXISTS'
            ),
            array(
                'key'       =>  'ical_only',
                'compare'   =>  'NOT EXISTS'
            )
        ) );
        
        
    }
}
add_action( 'pre_get_posts', 'exclude_hidden' );



// Load code that handles submission of custom post types
function save_custom_post_form( $post ) {
	
	if ( isset ( $_POST ['post_type'] ) )
	{
	    if( file_exists( dirname( __FILE__  ) . '/../submission_handling/' . $_POST ['post_type'] . '.php' ) )
	        include_once( dirname( __FILE__  ) . '/../submission_handling/' . $_POST ['post_type'] . '.php' );
    }
}
add_action( 'save_post', 'save_custom_post_form');

// For child forms, if the user accidentilly finds his way onto them without a parent post set, remove the editing interface
function restrict_parentless_children_forms() {
    if( $_SERVER['PHP_SELF'] == '/wp-admin/post-new.php' &&
        ! isset ( $_GET['parent_post'] ) && isset($_GET['post_type']) ) {
            switch($_GET['post_type']) {
                case "report":
                    $type = 'report';
                    break;
                case "result":
                    $type = 'result';
                    break;
				
				default: $type = false;	
					
            }

			if ( $type )
			{
	            remove_post_type_support( $type, 'title');
	            remove_post_type_support( $type, 'editor');
	            remove_post_type_support( $type, 'author');
	            remove_post_type_support( $type, 'excerpt');
	            remove_post_type_support( $type, 'thumbnail');
	            remove_post_type_support( $type, 'trackbacks');
	            remove_post_type_support( $type, 'custom-fields');
	            remove_post_type_support( $type, 'comments');
	            remove_post_type_support( $type, 'revisions');
	            remove_post_type_support( $type, 'page-attributes');
	            remove_post_type_support( $type, 'post-formats');
			}
        }
}
add_action( 'admin_init', 'restrict_parentless_children_forms');
