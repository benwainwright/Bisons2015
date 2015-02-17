<p>
    <span itemprop="author" itemscope itemtype="http://schema.org/Person">
        <span span itemprop="name" class="postAuthor fa fa-user"><?php echo get_the_author(); ?></span>
    </span>
    <span itemprop="datePublished" content="<?php the_time("Y-m-d") ?>" class="fa fa-clock-o"><?php the_time('g:ia') ?></span>
    <span class="fa fa-calendar"><?php the_time('jS \o\f M Y') ?></span>
    <?php if (comments_open( get_the_id() )) {?><span><a class="fa fa-comment" href="<?php the_permalink() ?>#comments"><?php comments_number(' 0',' 1',' %'); ?></a></span>
    <?php } ?><span><?php edit_post_link( 'Edit', ''); ?></span>
</p>
