<?php
/**
* Create custom Next Match feed widget
*/
class NextMatch extends WP_Widget {

    /**
    * Register widget with Wordpress
    */
    function __construct() {
        $widget_ops = array(
        'classname' => 'NextMatch',
        'description' => 'Displays the next match in your sidebar.'
        );

        parent::__construct('NextMatch', 'Next Match', $widget_ops);
    }

    /**
    * Front-end display of widget
    *
    * @see WP_Widget::widget()
    * @param array $args Widget arguments
    * @param array $instance Saved values from database
    *
    */
    public function widget( $args, $instance) 
    {

        $next_match_query = new WP_Query ( array (
        	'post_type'	=> 'fixtures',
        	'orderby'	=>	'meta_value',
			'meta_key'  => 'fixture-date',
			'posts_per_page' => 1
		));
		
		if ( $next_match_query->have_posts() ) {
	        extract( $args, EXTR_SKIP );
	
	        echo $before_widget;
	
	        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
	
	        if( !empty( $title ) ) echo $before_title . $title . $after_title;
			
			while ( $next_match_query->have_posts() )
			{
				$next_match_query->the_post();
				echo "<ul>";
				echo "<li><i class='fa fa-calendar'></i>".date( 'jS \o\f F Y' , get_post_meta( get_the_id(), 'fixture-date', true ) )."</li>";
				echo "<li><i class='fa fa-flag'></i>Bristol Bisons RFC VS " . ( get_post_meta( get_the_id(), 'fixture_team', true ) ? get_the_title( get_post_meta( get_the_id(), 'fixture_team', true ) ) : 'No Team' ) . "<a href='".get_permalink()."'>More...</a></li>";
				echo "</ul>";
			}
	
			
			
	
	        echo $after_widget;
		}
    }

    /**
    * @see WP_Widget::form()
    * Backend widget form
    * @param $instance Previously saved values from database
    */
    public function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => 'Next Match'));
        $title = $instance['title'];

        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>" /></label></p>
    <?php
    }

    /**
     * Sanitize widget values as they are saved
     * @param $new_instance
     * @param $old_instance
     */
    public function update( $new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        return $instance;
    }
}

/**
 * Register widget
 */

function register_nextMatch_widget() {
    register_widget( 'NextMatch');
}

add_action( 'widgets_init', 'register_nextMatch_widget');