<?php get_header(); ?>

<div id="wrapper">
    <div id="pagecol" class="ajaxcol">
        <div class='page'>
        <?php if ( $GLOBALS['bisons_flash_message'] ) : ?>
                <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
            <?php endif ?>
<?php if (have_posts()) : while (have_posts()) : the_post();

        // If a file for the relavent post type exists in the layouts subfolder, use it, otherwise use layouts/posts
        if( file_exists( dirname( __FILE__  ) . '/page-layouts/' . $pagename . '.php' ) ) :
            get_template_part( 'page-layouts/' . $pagename );
        else :
            get_template_part( 'page-layouts/page' );
        endif; ?>
<?php endwhile; endif; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>