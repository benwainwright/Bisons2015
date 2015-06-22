

<?php 
$post = $wp_query->get_queried_object();
$pagename = $post->post_name;
 
 ?>

<?php get_header(); ?>
<div id="wrapper">

    <div id="pagecol" class='ajaxcol'>
        <div class='page'>
<?php if ( isset ( $GLOBALS['bisons_flash_message'] )  ) : ?>
        <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
    <?php endif ?>
        <?php if ( have_posts() ) : 
                if( file_exists( dirname( __FILE__  ) . '/player-pages/' . $pagename . '.php' ) ) :
                    get_template_part( 'player-pages/' . $pagename );
                else :
                    while ( have_posts() ) : the_post(); ?>
                    <header>
                        <h2><?php the_title(); ?></h2>
                          <?php if ( current_user_can('edit_post') ) { ?>
                              <ul class='pageMenu'>
	                              <li><a class="fa fa-arrow-circle-left fa-lg" href="<?php echo site_url('players-area') ?>">Player's Area</a></li>
	                              <li><a class='fa fa-plus-square fa-lg' href='<?php echo $GLOBALS['blog_info']['url']; ?>/wp-admin/post-new.php?post_type=page'>New</a></li>
                                  <li><?php edit_post_link( 'Edit'); ?></li>
                              </ul>
                          <?php } ?>

                    </header>
	                    <?php get_template_part( 'snippets/playerPage', 'flashMessages' ) ?>
                    <?php the_content(); ?>
              <?php endwhile; 
              ?>
            
          <?php endif; ?>
          <?php else : ?>
            <h2>Nothing Found</h2>
            <p>Sorry, but the content you are looking for isn't here...</p>
            <p><a href="<?php echo get_option('home'); ?>">Return to the homepage</a></p>
        <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>

