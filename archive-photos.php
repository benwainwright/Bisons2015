<?php get_header(); ?>
<div id="wrapper">
    <div id="pagecol" class='ajaxcol'>
        <div class='page'>   
 
<?php if ( $GLOBALS['bisons_flash_message'] ) : ?>
        <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
    <?php endif ?>   
			<header>
			    <h2><a href="<?php the_permalink() ?>">Photo Albums</a></h2>
			</header>
			<?php while ( have_posts() ) : the_post() ?>
                
                <div class='albumThumb'>
                    <a class="desktopthumb" href='<?php the_permalink() ?>'><img src='<?php echo get_post_meta( get_the_id(), 'primary_photo_url', true) ?>' /></a>
                    <div class='albumMeta'>
                        <h3><a href='<?php the_permalink() ?>'><?php the_title() ?></a></h3>
                        <ul>
                            <?php if ( get_post_meta( get_the_id(), 'description', true) ) : ?><li><?php echo get_post_meta( get_the_id(), 'description', true)  ?></li><?php endif ?>
                        </ul>
                    </div>
                </div>
		    <?php endwhile ?>
		    
    	<section class="pagination">
    		<ul>
    		    <li class="newer"><?php if ($prev_url = get_previous_posts_link('Newer')) { echo $prev_url; } else { echo "Newer"; } ?></li>
    		    <li class="older"><?php if ($next_url = get_next_posts_link('Older')) { echo $next_url; } else { echo "Older"; } ?></li>
    		</ul>
    	</section>
		</div>
    </div>
</div>

<?php get_footer(); ?>