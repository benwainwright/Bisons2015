<p>
    <span itemprop="author" itemscope itemtype="http://schema.org/Person">
        <span span itemprop="name" class="postAuthor"><i class="fa fa-user"></i><?php echo get_the_author(); ?></span>
    </span>
    <span itemprop="datePublished" content="<?php the_time("Y-m-d") ?>"><i class="fa fa-clock-o"></i><?php the_time('g:ia') ?></span>
    <span><i class="fa fa-calendar"></i><?php the_time('jS \o\f M Y') ?></span>
    <?php if (comments_open( get_the_id() )) {?><span><a href="<?php the_permalink() ?>#comments"><i class="fa fa-comment" ></i><?php comments_number(' 0',' 1',' %'); ?></a></span>
    <?php } ?><span><?php edit_post_link( 'Edit', ''); ?></span>
</p>
