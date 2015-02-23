<?php get_header(); ?>

<div id="wrapper">

    <div class="ajaxcol"> 

    
	<div id="maincol">
<?php if ( isset ( $GLOBALS['bisons_flash_message'] ) ) : ?>
        <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
    <?php endif ?>
    <header class="mobileonly">
    <?php $options = get_option('club-info-settings-page'); ?>
	<h2><?php echo $options['welcome-title'] ?></h2>
	</header>
	<section class="mobileonly"><?php echo wpautop( $options['welcome-text'] ) ?></section>
    
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

    	<section class="pagination">
    		<ul>
    		    <li class="newer"><?php if ($prev_url = get_previous_posts_link('Newer')) { echo $prev_url; } else { echo "Newer"; } ?></li>
    		    <li class="older"><?php if ($next_url = get_next_posts_link('Older')) { echo $next_url; } else { echo "Older"; } ?></li>
    		</ul>
    	</section>

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

