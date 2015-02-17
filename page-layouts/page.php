<header>
    <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
        <?php if ( current_user_can('edit_post') ) { ?>
            <ul class='pageMenu'>
                <li><a class='fa fa-plus-square fa-lg' href='<?php echo $GLOBALS['blog_info']['url']; ?>/wp-admin/post-new.php?post_type=page'>New Page</a></li>
                <li><?php edit_post_link( 'Edit'); ?></li>
            </ul>
        <?php } ?>
</header>

<?php 
the_content(''); 
comments_template();
?>
   