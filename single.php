<?php get_header(); ?>

<div id="wrapper">

    <div class="ajaxcol"> 

    
	<div id="maincol">


    <?php if ( have_posts() ) : ?>
       <?php while (have_posts() ) : the_post(); ?>

                
                    <?php
                    if( file_exists( dirname( __FILE__  ) . '/post-layouts/' . get_post_type( ) . '.php' ) ) :
                        get_template_part( 'post-layouts/' . get_post_type( ) );
                    else :
                        get_template_part( 'post-layouts/post' );
                    endif; 
                    ?>
    <?php endwhile; ?>

    <?php else : ?>
        <h2>Nothing Found</h2>
        <p>Sorry, but the content you are looking for isn't here...</p>
        <p><a href="<?php echo get_option('home'); ?>">Return to the homepage</a></p>
<?php endif; ?>
    </div>
      <?php get_sidebar(); ?>

	</div>

      </div>
<?php get_footer(); ?>

