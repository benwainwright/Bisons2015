<header>
    <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
        <?php if ( current_user_can('edit_post', get_the_id()) ) { ?>
            <ul class='pageMenu'>
                <li><a class='fa fa-plus-square fa-lg' href='<?php echo $GLOBALS['blog_info']['url']; ?>/wp-admin/post-new.php?post_type=page'>New Page</a></li>
                <li><?php edit_post_link( 'Edit'); ?></li>
            </ul>
        <?php } ?>
</header>

<?php 
  if ( has_post_thumbnail() ) {
   
        $thumbnailAtributes = array(
              'itemprop'  => 'photo',
              'class'     => 'alignright'
        );
        the_post_thumbnail( 'full');
  } 
the_content(''); 
comments_template();
?>
   