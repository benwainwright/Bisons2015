<?php

/** 
 * Register sidebar
 *
 *
 */

function sidebar_widgets_init() {
    
    register_sidebar( array(
        'name' => 'Home right sidebar',
        'id' => 'sidebar',
        'before_widget' => '<div id="widget">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>'
    ) );
}
add_action( 'widgets_init', 'sidebar_widgets_init' );