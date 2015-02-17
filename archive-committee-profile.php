
 if (! current_user_can ('view_players_area') ) $loggedin = true; 

?>
<?php get_header(); ?>

<div id="wrapper">
    <div id="pagecol">
        <div class='page'>
        <header>
        <?php if ( $GLOBALS['bisons_flash_message'] ) : ?>
                <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
            <?php endif ?>
        <h2>The Committee</h2>
        </header>
        <p>If you have some time to spare and would like to give back to the team, have you considered being part of the commitee? Committee positions are elected on a yearly basis during the AGM, which usually takes place during June/July. Have a look at the profiles of each position below to get a better idea where you would fit in. Note that contact details for the committee are only available for logged in players. If you would like to get in contact with a member of our committee, please use the contact form on the <a href='<?php echo $GLOBALS['blog_info']['url'] ?>/about-us/'>About Us</a> page.</p>

        <?php if (have_posts()) : 
            while (have_posts()) : the_post();
            
                $incumbent = get_post_meta( get_the_id(), 'incumbent', true );
                $photourl = wp_get_attachment_image_src( get_post_meta( $incumbent, 'image_id', true), 'large' );
                $posemail = get_post_meta( get_the_id(), 'posemail', true);
                $posphone = get_post_meta( get_the_id(), 'posphone', true);
                $name = get_post_meta( get_post_meta( get_the_id(), 'incumbent', true ), 'name', true);

                ?>
                <div class='comprofile'>
                <h3><?php the_title(); ?><?php if ($name) echo " ($name)"; ?></h2>
                <?php if ($photourl[0]) { ?><a href='#'><img class="alignright" src='<?php echo $photourl[0] ?>' /></a><?php } ?>
                <?php echo get_post_meta(get_the_id(), 'summary', true) ?>
                <h4>Skills needed:</h4>
                <?php echo get_post_meta(get_the_id(), 'skills', true) ?>
                <h4>Main responsibilities:</h4>
                <?php echo get_post_meta(get_the_id(), 'posresp', true) ?>
                <?php if ( ( $posphone || $posemail) && $loggedin ) : ?>
                <h4>Get in touch</h4>
                <ul>
                    <?php if($posemail) { ?><li class='email'><a href='mailto:<?php echo $posemail ?>'><?php echo $posemail ?></a></li><?php } ?>
                    <?php if($posphone) { ?><li class='phone'><?php echo $posphone ?></li><?php } ?>
                </ul>
                <?php endif ?>
                <div class='expand'></div>
                </div>
            <?php endwhile; endif; ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>