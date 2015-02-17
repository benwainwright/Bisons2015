<?php get_header(); ?>
<?php 
$image_id = get_post_meta( $post->ID, 'image_id', true);
$image_url_large = wp_get_attachment_image_src( $image_id, 'large' );
$image_url_large = $image_url_large[0];
$image_url_original = wp_get_attachment_image_src( $image_id, 'original' );
$image_url_original = $image_url_original[0];

?>
<div id="wrapper">

    <div id="pagecol">
        <div class='page'> 
<?php if ( $GLOBALS['bisons_flash_message'] ) : ?>
        <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
    <?php endif ?>      

        <?php if ( have_posts() ) : ?>
            <?php while (have_posts() ) : the_post(); ?>
                <header>
                    <h2>Player Profile - <?php the_title(); ?></h2>
                </header>
                <?php if($image_id) : ?>
                <a class="image-link" href='<?php echo $image_url_original?>'>
                    <img class='alignright' src='<?php echo $image_url_large?>' alt='<?php get_post_meta( $post->ID, 'name', true) ?>'>
                </a>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'name', true) ) : ?>
                <h4>Name</h4>
                <p><?php echo get_post_meta( $post->ID, 'name', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'nickname', true) ) : ?>
                <h4>Nickname</h4>
                <p><?php echo get_post_meta( $post->ID, 'nickname', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'age', true) ) : ?>
                <h4>Age</h4>
                <p><?php echo get_post_meta( $post->ID, 'age', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'living', true) ) : ?>
                <h4>Do you work or study? If so, what?</h4>
                <p><?php echo get_post_meta( $post->ID, 'living', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'position', true) ) : ?>
                <h4>What position are you on (or off) the pitch?</h4>
                <p><?php echo get_post_meta( $post->ID, 'position', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'followed', true) ) : ?>
                <h4>What club or team do you follow?</h4>
                <p><?php echo get_post_meta( $post->ID, 'followed', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'exp', true) ) : ?>
                <h4>How long have you been playing?</h4>
                <p><?php echo get_post_meta( $post->ID, 'exp', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'jexp', true) ) : ?>
                <h4>How much did you know about rugby when you joined?</h4>
                <p><?php echo get_post_meta( $post->ID, 'jexp', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'whydoyouplay', true) ) : ?>
                <h4>Why do you play for the Bisons?</h4>
                <p><?php echo get_post_meta( $post->ID, 'whydoyouplay', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'proplayerasp', true) ) : ?>
                <h4>Which professional player would you like to perform like, and why?</h4>
                <p><?php echo get_post_meta( $post->ID, 'proplayerasp', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'proplayer', true) ) : ?>
                <h4>Which professional player <em>do</em> you perform like, and why?</h4>
                <p><?php echo get_post_meta( $post->ID, 'proplayer', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'chatup', true) ) : ?>
                <h4>What is your best chat up line?</h4>
                <p><?php echo get_post_meta( $post->ID, 'chatup', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'growingup', true) ) : ?>
                <h4>When you were growing up, what did you want to be?</h4>
                <p><?php echo get_post_meta( $post->ID, 'growingup', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'superst', true) ) : ?>
                <h4>Do you have any prematch superstitions/routines?</h4>
                <p><?php echo get_post_meta( $post->ID, 'superst', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'possessions', true) ) : ?>
                <h4>Your most treasured possession/s:</h4>
                <p><?php echo get_post_meta( $post->ID, 'possessions', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'breakfast', true) ) : ?>
                <h4>What do you normally eat for breakfast?</h4>
                <p><?php echo get_post_meta( $post->ID, 'breakfast', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'bestmem', true) ) : ?>
                <h4>Best achievement/memory of being in the club?</h4>
                <p><?php echo get_post_meta( $post->ID, 'bestmem', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'notholiday', true) ) : ?>
                <h4>Where is the one place you'd never go on holiday?</h4>
                <p><?php echo get_post_meta( $post->ID, 'notholiday', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'movielife', true) ) : ?>
                <h4>In the movie of your life, who would you be played by?</h4>
                <p><?php echo get_post_meta( $post->ID, 'movielife', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'cartoon', true) ) : ?>
                <h4>Favourite cartoon as a kid?</h4>
                <p><?php echo get_post_meta( $post->ID, 'cartoon', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'eventfromhistory', true) ) : ?>
                <h4>If you could turn back time and witness one event from history, what would it be?</h4>
                <p><?php echo get_post_meta( $post->ID, 'eventfromhistory', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'sigdish', true) ) : ?>
                <h4>What is your signature dish?</h4>
                <p><?php echo get_post_meta( $post->ID, 'sigdish', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'lastmeal', true) ) : ?>
                <h4>What would your last meal be?</h4>
                <p><?php echo get_post_meta( $post->ID, 'lastmeal', true); ?></p>
                <?php endif; ?>
                <?php if (get_post_meta( $post->ID, 'desertisland', true) ) : ?>
                <h4>Stranded on a desert island, what are your three essential items?</h4>
                <p><?php echo get_post_meta( $post->ID, 'desertisland', true); ?></p>
                <?php endif; ?>
               <?php if (get_post_meta( $post->ID, 'lastfifty', true) ) : ?>
                <h4>What would you buy with your last fifty pounds?</h4>
                <p><?php echo get_post_meta( $post->ID, 'lastfifty', true); ?></p>
                <?php endif; ?>
                
                <?php if (get_post_meta( $post->ID, 'bestplayer', true) ||
                          get_post_meta( $post->ID, 'fastestplayer', true) ||
                          get_post_meta( $post->ID, 'longestshower', true) ||
                          get_post_meta( $post->ID, 'biggestmoaner', true) ||
                          get_post_meta( $post->ID, 'dresssense', true) ||
                          get_post_meta( $post->ID, 'lasttobar', true) ||
                          get_post_meta( $post->ID, 'worstdancer', true) ||
                          get_post_meta( $post->ID, 'badinfluence', true) ||
                          get_post_meta( $post->ID, 'cheesegrindr', true) ) : ?>
              <p><em>Out of the current team, who would you say...</em></p>
               <?php if (get_post_meta( $post->ID, 'bestplayer', true) ) : ?>
                <h4>Is the best player?</h4>
                <p><?php echo get_post_meta( $post->ID, 'bestplayer', true); ?></p>
                <?php endif; ?>
               <?php if (get_post_meta( $post->ID, 'fastestplayer', true) ) : ?>
                <h4>Is the fastest player?</h4>
                <p><?php echo get_post_meta( $post->ID, 'fastestplayer', true); ?></p>
                <?php endif; ?>
               <?php if (get_post_meta( $post->ID, 'longestshower', true) ) : ?>
                <h4>Takes the longest to shower?</h4>
                <p><?php echo get_post_meta( $post->ID, 'longestshower', true); ?></p>
                <?php endif; ?>
               <?php if (get_post_meta( $post->ID, 'biggestmoaner', true) ) : ?>
                <h4>Is the biggest moaner?</h4>
                <p><?php echo get_post_meta( $post->ID, 'biggestmoaner', true); ?></p>
                <?php endif; ?>
               <?php if (get_post_meta( $post->ID, 'dresssense', true) ) : ?>
                <h4>Has the worst dress sense?</h4>
                <p><?php echo get_post_meta( $post->ID, 'dresssense', true); ?></p>
                <?php endif; ?>                
               <?php if (get_post_meta( $post->ID, 'lasttobar', true) ) : ?>
                <h4>Is always last to the bar?</h4>
                <p><?php echo get_post_meta( $post->ID, 'lasttobar', true); ?></p>
                <?php endif; ?>     
               <?php if (get_post_meta( $post->ID, 'worstdancer', true) ) : ?>
                <h4>Is the worst Dancer?</h4>
                <p><?php echo get_post_meta( $post->ID, 'worstdancer', true); ?></p>
                <?php endif; ?>            
               <?php if (get_post_meta( $post->ID, 'badinfluence', true) ) : ?>
                <h4>Is the worst influence on others?</h4>
                <p><?php echo get_post_meta( $post->ID, 'badinfluence', true); ?></p>
                <?php endif; ?>     
               <?php if (get_post_meta( $post->ID, 'cheesegrindr', true) ) : ?>
                <h4>Has the cheesiest Grindr profile?</h4>
                <p><?php echo get_post_meta( $post->ID, 'cheesegrindr', true); ?></p>
                <?php endif; ?>   
              <?php endif; ?>

        <?php endwhile; ?>
        <?php else : ?>
            <h2>Nothing Found</h2>
            <p>Sorry, but the content you are looking for isn't here...</p>
            <p><a href="<?php echo get_option('home'); ?>">Return to the homepage</a></p>
        <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>

