<?php
/**
* Create custom text widget designed to be displayed on mobile as well
*/
class Mobile_Text_Widget extends WP_Widget {

    /**
    * Register widget with Wordpress
    */
    function __construct() {
        $widget_ops = array(
        'classname' => 'Mobile_Text_Widget',
        'description' => 'Welcome text, displayed in sidebar and mobile header.'
        );

        parent::__construct('Mobile_Text_Widget', 'Welcome Text', $widget_ops);
    }

    /**
    * Front-end display of widget
    *
    * @see WP_Widget::widget()
    * @param array $args Widget arguments
    * @param array $instance Saved values from database
    *
    */
    public function widget( $args, $instance) {

        extract( $args, EXTR_SKIP );

        echo $before_widget;
        
        $options = get_option('club-info-settings-page');
        
        echo $before_title . $options['welcome-title'] . $after_title;
        echo wpautop( $options['welcome-text'] );
        echo $after_widget;
    }

    /**
    * @see WP_Widget::form()
    * Backend widget form
    * @param $instance Previously saved values from database
    */
    public function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ));
        $title = $instance['title'];
        $text = $instance['text'];

        ?>
        <p>The content for this widget is defined in the <a href='<?php echo admin_url ( 'admin.php?page=club-info-settings' ) ?>'>club info</a> settings page.</p>
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
        $instance['text'] = $new_instance['text'];
        return $instance;
    }
}

/**
 * Register widget
 */

function register_mobile_text_widget() {
    register_widget( 'Mobile_Text_Widget');
}

add_action( 'widgets_init', 'register_mobile_text_widget');