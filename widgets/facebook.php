<?php
/**
* Create custom Twitter feed widget
*/
class Facebook_Widget extends WP_Widget {

    /**
    * Register widget with Wordpress
    */
    function __construct() {
        $widget_ops = array(
        'classname' => 'Facebook_Widget',
        'description' => 'Displays posts from a specific Facebook page. Custom Bisons Online widget.'
        );

        parent::__construct('Facebook_Widget', 'Facebook Page', $widget_ops);
    }

    /**
    * Front-end display of widget
    *
    * @see WP_Widget::widget()
    * @param array $args Widget arguments
    * @param array $instance Saved values from database
    *
    */
    public function widget( $args, $instance ) {

        extract( $args, EXTR_SKIP );

        echo $before_widget;

        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

        if( !empty( $title ) )
        echo $before_title . $title . $after_title;

        // Widget code goes here
        $facebook = new Facebook( $GLOBALS['api_settings'] );
        $posts = $facebook->request ( $instance['page_name'], 'posts' )->data;
        
        
        ?>
        <ul>
        <?php 
        $i = 0;
        foreach($posts as $post) {
            if( isset( $post->message ) && $i < $instance['num_posts'] ) { ?>
            <li class="fbpagepost"><?php echo $post->link ? '<a href="'.$post->link.'">' : null ?><?php echo $post->message; ?><?php echo $post->link ? '</a>' : null ?></li>
        <?php
            $i++;
            } 
        } ?>
        </ul>
        <p class="followme">For more, like <a href="http://www.facebook.com/<?php echo $instance['page_name']; ?>"><?php echo $instance['page_name']; ?></a> on Facebook</p>
<?php
        echo $after_widget;
    }

    /**
    * @see WP_Widget::form()
    * Backend widget form
    * @param $instance Previously saved values from database
    */
    public function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => 'Facebook', 'page_name' => '', 'num_posts' => 5));
        $title = $instance['title'];
        $page_name = $instance['page_name'];
        $num_posts = $instance['num_posts'];

        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('page_name'); ?>">Facebook Page Name: <input class="widefat" id="<?php echo $this->get_field_id('page_name'); ?>" name="<?php echo $this->get_field_name('page_name'); ?>" value="<?php echo esc_attr($page_name); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('num_posts'); ?>">Number of posts to display: <input class="numfield" id="<?php echo $this->get_field_id('num_posts'); ?>" name="<?php echo $this->get_field_name('num_posts'); ?>" value="<?php echo esc_attr($num_posts); ?>" /></label></p>

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
        $instance['page_name'] = $new_instance['page_name'];
        $instance['num_posts'] = $new_instance['num_posts'];

        return $instance;
    }
}

/**
 * Register widget
 */

function register_facebook_widget() {
    register_widget( 'Facebook_Widget');
}

add_action( 'widgets_init', 'register_facebook_widget');