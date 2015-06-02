<?php function my_login_logo() { ?>

<style type="text/css">
      body.login div#login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/pinkbisonsvg.svg);
            padding-bottom: 30px;
            padding-bottom: 30px;
            background-size: 150px;
            width: 150px;
            height: 100px;
      }
</style>

<?php } add_action( 'login_enqueue_scripts', 'my_login_logo' );

function disable_password_reset() { return false; }
add_filter ( 'allow_password_reset', 'disable_password_reset' );

function remove_lost_your_password($text) 
{ return str_replace( array('Lost your password?', 'Lost your password'), '', trim($text, '?') );  }
 add_filter( 'gettext', 'remove_lost_your_password'  );
 
function custom_login_message() { $message = "You have tried to reach a page that can only be accessed by a current club member. <strong>Note - Only certain users need to use a Google Authenticator code. If you do not have one, just leave this box blank.</strong>"; return $message; }
add_filter('login_message', 'custom_login_message');
 