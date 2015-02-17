<?php
/**
* Create custom Twitter feed widget
*/
class Twitter_Widget extends WP_Widget {

    /**
    * Register widget with Wordpress
    */
    function __construct() {
        $widget_ops = array(
        'classname' => 'Twitter_Widget',
        'description' => 'Displays tweets from a specific Twitter timeline in your sidebar. Custom Bisons Online widget.'
        );

        parent::__construct('Twitter_Widget', 'Twitter Feed', $widget_ops);
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

        extract( $args, EXTR_SKIP );

        echo $before_widget;

        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

        if( !empty( $title ) ) echo $before_title . $title . $after_title;

        // Widget code goes here
        $twitter = new Twitter( $GLOBALS['api_settings'] );

        $parameters = array(
            'count' => $instance['num_tweets'],
            'trim_user' => 'true',
            'screen_name' => $instance['screen_name']
        );
        
        $i = 0; 
        
        $tweet_array = array();
	  do
        {
              $tweets = $twitter->request ( 'statuses/user_timeline', $parameters );
              $size = 0;
              unset ($tweet_array);
              $tweet_array = array();  
		  echo "<ul>";
              foreach($tweets as $tweet) 
              {
                  $size++;
                  $tweet_text = preg_replace('/(https?:\/\/?[\da-z\.-]+\.[a-z\.]{2,6}[\/\w\.-]+\/?)/', "<a href='$1'>$1</a>", $tweet->text); // Turn URLs into links
                  $tweet_text = preg_replace('/\#([a-zA-Z0-9_]+)/', "<a href='http://www.twitter.com/search?q=%23$1' title='#$1 on Twitter'>#$1</a>", $tweet_text); // Turn Hashtags into links
                  $tweet_text = preg_replace('/\@([a-zA-Z0-9_]+)/', "<a href='http://www.twitter.com/$1' title='$1 on Twitter'>@$1</a>", $tweet_text); // Turn Twitter handles into links
                  array_push ($tweet_array, $tweet_text);
              }
              $i++;
        } while ( $size == 0 && $i < 4 );
        foreach ( $tweet_array as $tweet ) echo "<li class=\"tweet\">$tweet</li>"; 
	  echo "</ul>";
        echo "<p class=\"followme\">For more, follow <a href=\"http://www.twitter.com/".$instance['screen_name']."\">@".$instance['screen_name']."</a> on Twitter</p>";
        echo $after_widget;
    }

    /**
    * @see WP_Widget::form()
    * Backend widget form
    * @param $instance Previously saved values from database
    */
    public function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => 'Twitter', 'screen_name' => '', 'num_tweets' => 5));
        $title = $instance['title'];
        $screen_name = $instance['screen_name'];
        $num_tweets = $instance['num_tweets'];

        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('screen_name'); ?>">Twitter Account: <input class="widefat" id="<?php echo $this->get_field_id('screen_name'); ?>" name="<?php echo $this->get_field_name('screen_name'); ?>" value="<?php echo esc_attr($screen_name); ?>" /></label></p>
        <p><label for="<?php echo $this->get_field_id('num_tweets'); ?>">Number of tweets to display: <input class="numfield" id="<?php echo $this->get_field_id('num_tweets'); ?>" name="<?php echo $this->get_field_name('num_tweets'); ?>" value="<?php echo esc_attr($num_tweets); ?>" /></label></p>

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
        $instance['screen_name'] = $new_instance['screen_name'];
        $instance['num_tweets'] = $new_instance['num_tweets'];

        return $instance;
    }
}

/**
 * Register widget
 */

function register_twitter_widget() {
    register_widget( 'Twitter_Widget');
}

add_action( 'widgets_init', 'register_twitter_widget');