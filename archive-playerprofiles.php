<?php get_header(); ?>
<div id="wrapper">
    <div id="pagecol" class='ajaxcol'>
        <div class='page'>   
 
<?php if ( isset ( $GLOBALS['bisons_flash_message'] ) ) : ?>
        <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
    <?php endif ?>   
			<header>
			    <h2><a href="<?php the_permalink() ?>">Player Profiles</a></h2>
			</header>
            <p>Get to know some of our players below! Click on the photo to learn more about the individual player. Obviously, this isn't a dating website, so we prefer not to give out contact details. But if you think that your face might look good on this page, then why don't you come along to training?</p>
			<?php 
			
            global $wp_query;
            query_posts(
               array_merge(
                  $wp_query->query,
                  array('posts_per_page' => -1)
               )
            );
			while ( have_posts() ) : the_post() ?>
            <?php if ( has_post_thumbnail() ) :  ?>
                <div class='albumThumb'>
                    <a class="desktopthumb" href='<?php the_permalink() ?>'><?php the_post_thumbnail() ?></a>
                    <div class='profileMeta'>
                        <h3><a href='<?php the_permalink() ?>'><?php the_title() ?></a></h3>
                        <ul>
                        
                            <?php if ( $age = get_post_meta( get_the_id(), 'age', true ) ) { ?><li><strong>Age: </strong><?php echo $age; ?></li><?php } ?>
                            <?php if ( $nickname = get_post_meta( get_the_id(), 'nickname', true ) ) { ?><li><strong>Nickname: </strong><?php echo $nickname; ?></li><?php } ?>
                            <?php if ( $position = get_post_meta( get_the_id(), 'position', true ) ) { ?><li><strong>Position: </strong><?php echo $position; ?></li><?php } ?>
                            <?php if ( $exp = get_post_meta( get_the_id(), 'exp', true ) ) { ?><li><strong>Rugby experience: </strong><?php if ( strlen($exp) > 100 ) { echo substr( $exp, 0, 100 ) . "... (Click photo to read more)"; } else { echo $exp; } ?></li><?php } ?>
                            <?php if ( $jexp = get_post_meta( get_the_id(), 'jexp', true ) ) { ?><li><strong>Prior rugby Experience: </strong><?php if ( strlen($jexp) > 100 ) { echo substr( $jexp, 0, 100 ) . "... (Click photo to read more)"; } else { echo $jexp; } ?></li> <?php } ?>
                    
    

                        </ul>
                    </div>
                </div>
                <?php endif ?>
		    <?php endwhile ?>
		</div>
    </div>
</div>

<?php get_footer(); ?>