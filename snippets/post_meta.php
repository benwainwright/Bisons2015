<ul class='meta'>
    <li>
    	<span itemprop="author" itemscope itemtype="http://schema.org/Person">
        <span span itemprop="name" class="postAuthor fa fa-user"><?php echo get_the_author(); ?></span>
    </span>
    </li>
    <li><span itemprop="datePublished" content="<?php the_time("Y-m-d") ?>" class="fa fa-clock-o"><?php the_time('g:ia') ?></span></li>
    <li><span class="fa fa-calendar"><?php the_time('jS \o\f M Y') ?></span></li>
    <?php if (comments_open( get_the_id() )) {?><li><span><a class="fa fa-comment" href="<?php the_permalink() ?>#comments"><?php comments_number(' 0',' 1',' %'); ?></a></span></li>
    <?php } ?>
    <?php if ( current_user_can('edit_post', get_the_id() ) ) { ?>
	<li><span><a class='fa fa-pencil-square' href='<?php echo get_edit_post_link() ?>'>Edit</a></span></li>
	<?php } ?>
</ul>
