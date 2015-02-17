<?php get_header(); ?>

<div id="wrapper">

    <div id="pagecol">
        <div class='page'>
<?php if ( $GLOBALS['bisons_flash_message'] ) : ?>
        <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
    <?php endif ?>
        <?php if ( have_posts() ) : ?>
            <?php while (have_posts() ) : the_post(); 
            $gallery = get_post_meta( get_the_id(), 'setid')[0];
            $flikr = new Flikr ( $GLOBALS['api_settings'] );
            $photos = $flikr->photosetsGetPhotos ( $gallery, 'url_q,url_z,' )->photoset->photo;
            $photoinfo = $flikr->photosetsGetInfo( $gallery )->photoset;
            $title = $photoinfo->title->_content;
            
            $description = $photoinfo->description->_content;
            $created = date ( 'jS \o\f F Y' , $photoinfo->date_create );
            $updated = date ( 'jS \o\f F Y' , $photoinfo->date_update );
            
            ?>
			<header>
			    <h2><a href="<?php the_permalink() ?>"><?php echo $title ?></a></h2>
			    <p>Album created on the <?php echo $created ?><?php if ( $created != $updated ) { ?> and last updated on the <?php echo $updated; } ?></p>
			</header>
			<div class="pagecontent">
			<p>Click photos below to view. To download the photos at their original resolutions, have a look at <a href='<?php echo $photosurl.'sets/'.$gallery ?>' title='<?php echo $userid ?> on Flickr'>our Flickr page</a>.</p>
			</div>
			<table class="photogallery">
			    <tbody>
        
				<?php 
				
				$cols = 5;
				$i = 0;
				foreach ( $photos as $photo ) :
				    
				
				    if ($i == 0 ) echo "<tr>";
				    echo "<td><a class='fancybox' rel='gallery' href='$photo->url_z'><img src='$photo->url_q' /></a></td>";
				    if ($i == $cols - 1) : echo "</tr>"; $i = 0;
				    else : $i++; 
				    endif;
				endforeach;
				if ( $i != 0 ) echo "</tr>"; ?>
				
			    </tbody>
			</table>
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

        