<?php get_header(); ?>
<div id="wrapper">
    <div id="pagecol" class='ajaxcol'>
        <div class='page'>   
 
<?php if ( isset ( $GLOBALS['bisons_flash_message'] ) ) : ?>
        <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
    <?php endif ?>   
		<header>
	    <h2>Teams</h2>
	   </header>
	   <p>Here you will find details of all the teams we have played against or plan to play against. Click on the team names for more details.</p>
		<ul class='fa-ul'>
			<?php while ( have_posts() ) : the_post() ?>
				<li><span class='fa-li fa fa-thumb-tack'></span><a href='<?php the_permalink() ?>'><?php the_title() ?></a></li>
			<?php endwhile ?>
		</ul>
		</div>
    </div>

</div>
<?php get_footer(); ?>