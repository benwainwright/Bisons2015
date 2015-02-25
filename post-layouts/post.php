<div itemscope itemytype="http://schema.org/Article" <?php post_class('post') ?>>
      <header>
          <h2><a href="<?php the_permalink() ?>"><span itemprop="name"><?php the_title(); ?></span></a></h2>
          <?php include( __DIR__ . '/../snippets/post_meta.php' ) ?>
      </header>
      
      <?php 
      if ( has_post_thumbnail() ) {

            $thumbnailAtributes = array(
                  'itemprop'  => 'photo',
                  'class'     => 'alignright'
            );
            the_post_thumbnail($thumbnailAtributes);
      
      } 


      if ( is_single() ) the_content(); 
      else echo preg_replace("/<img(.*?)>/si", "", get_the_excerpt());
      comments_template(); ?>
</div>