<?php get_header(); ?>
<!--email_off-->  
<div id="wrapper">
    <div id="pagecol" class='ajaxcol'>
        <div class='page'>   
<?php if ( isset ( $GLOBALS['bisons_flash_message'] ) ) : ?>
        <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
    <?php endif ?>    
    <header>
        <h2>Player's Area</h2>
		
		
    <ul class='pageMenu'>
    <?php if ( current_user_can('edit_post', get_the_id()) ) { ?>
        <li><a class='fa fa-plus-square fa-lg' href='<?php echo $GLOBALS['blog_info']['url']; ?>/wp-admin/post-new.php?post_type=player-page'>Add</a></li>
		<li><a class='fa fa-wordpress fa-lg' href='<?php echo admin_url(); ?>'>Dashboard</a></li>
    <?php } ?>
    </ul>		
    </header>
    
    
            <?php
            $committee_profiles = new WP_Query ( array(
                 'post_type' => 'committee-profile',
                 'nopaged'  => 'true'
            ));
            if( $committee_profiles->have_posts() ) : ?>
            <h3>The committee</h3>
            <p>If you have any questions, try asking an established member first. If they are unable to answer your question, please get in contact with a member of our committee - their contact details are below. If you are considering running for a committee position, have a more detailed read of the different <a href='<?php echo $GLOBALS['blog_info']['url'] ?>/committee-profile/'>committee positions</a>. If you are not sure about something, please ask us!</p>

            <?php while ($committee_profiles->have_posts() ) : $committee_profiles->the_post(); 
                $incumbent = get_post_meta( get_the_id(), 'incumbent', true );
                $photourl = wp_get_attachment_image_src( get_post_meta( $incumbent, 'image_id', true), 'medium' );
                if ($photourl) :
                $name = get_post_meta( $incumbent, 'name', true);
                $askme = get_post_meta( get_the_id(), 'askme', true);
                $askme = strtolower( substr($askme, 0, 1) ).substr($askme, 1);
		    $phonenum = get_post_meta( get_the_id(), 'posphone', true);

            ?>
            <div class='committeeList'>
                <a class="desktopthumb" href='<?php the_permalink() ?>'><img src='<?php echo $photourl[0]; ?>' /></a>
                <div class='albumMeta'>
                             <ul>
						
                            <li><h3><?php echo get_post_meta( get_the_id(), 'posname', true) ?> (<?php echo get_post_meta( get_post_meta( get_the_id(), 'incumbent', true ), 'name', true); ?>)</h3>
</li>
                                <?php if(get_post_meta( get_the_id(), 'posemail', true)) { ?><li class='fa fa-envelope-o'><a href='mailto:<?php echo get_post_meta( get_the_id(), 'posemail', true) ?>'><?php echo get_post_meta( get_the_id(), 'posemail', true) ?></a></li><?php } ?>
                                <?php if(get_post_meta( get_the_id(), 'posphone', true)) { ?><li class='fa fa-phone'><a href='tel:<?php echo $phonenum ?>'><?php echo $phonenum ?></a></li><?php } ?>
							  <li><?php if($askme) { ?><strong>Ask me about</strong> <?php echo $askme ?><?php } ?></li>

                            </ul>   

                </div>
            </div>
			<?php endif; ?>
            <?php endwhile; ?>
            
            
            
            <?php endif; ?>
            
            <div class='clear'>
                  

            <?php
            $page_groups = get_categories( array(
                    'taxonomy' => 'player-page-groups')
            );

            foreach($page_groups as $group) { ?>
                <h3><?php echo $group->name; ?></h3>
                <p><?php echo $group->description ?></p>
                <table>
                    <tbody>

                    <?php $page_list = new WP_Query(array(
                        'post_type' => 'player-page',
                        'paged' => -1,
                        'player-page-groups' => $group->slug

                    ));
                    while($page_list->have_posts()) :
                        $page_list->the_post();
                        $post = get_post();
                        $link = get_post_meta(get_the_id(), 'link', true);
                        ?> <tr>
                        <td class="left-col"><span class='<?php echo $link ? 'fa fa-external-link-square' : 'fa fa-file' ?>'><a href='<?php echo $link ? $link : get_permalink(); ?>' title='<?php echo get_the_title(); ?>'><?php echo get_the_title(); ?></a></span>
                        </td>
                        <td><?php echo get_post_meta(get_the_id(), 'description', true) ?>
</td>

                    </tr>
                    <?php endwhile;
                    ?>
                    </tbody></table> 
                    
                    <?php } ?>
              </div>
        </div>
    </div>
</div>
<!--/email_off-->  
<?php get_footer(); ?>