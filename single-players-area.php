

<?php 
$post = $wp_query->get_queried_object();
$pagename = $post->post_name;
 
 ?>

<?php get_header(); ?>
<div id="wrapper">

    <div id="pagecol">
        <div class='page'>
<?php if ( $GLOBALS['bisons_flash_message'] ) : ?>
        <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
    <?php endif ?>
        <?php if ( have_posts() ) : 
                if( file_exists( dirname( __FILE__  ) . '/hardcodedplayerpages/' . $pagename . '.php' ) ) :
                    get_template_part( 'hardcodedplayerpages/' . $pagename );
                else :
                    while ( have_posts() ) : the_post(); ?>
                    <header>
                        <h2><?php the_title(); ?></h2>
                    </header>
                    <?php the_content(); ?>
                    <?php edit_post_link( 'Edit post'); ?> 
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

