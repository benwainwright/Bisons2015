    <header>
    <h2><a href="<?php the_permalink() ?>">Player Profiles</a></h2>
    <?php if ( current_user_can('edit_post') ) { ?>
    <ul class='meta'>
        <li><a class='fa fa-plus-square fa-lg' href='<?php echo $GLOBALS['blog_info']['url']; ?>/wp-admin/post-new.php?post_type=playerprofile'>Add</a></li>
    </ul><?php } ?>
    
</header>
<?php 
$players = new WP_Query( array( 'post_type' => 'playerprofile', 
                                'nopaging' => 'true', 'meta_query' => array ( array(
                                    'key' => 'image_id',
                                    'compare' => ' EXISTS' ) )

) );
if( ! $players->have_posts() ) : ?>
    <p class="infoalert">No player profiles yet - check back later.</p>
<?php else : ?>
<p>Get to know some of our players below! Click on the photo to learn more about the individual player. Obviously, this isn't a dating website, so we prefer not to give out contact details. But if you think that your face might look good on this page, then why don't you come along to training?</p>
<table class='playerProfilesv2'>

<?php 

$cols = 5;
$i = 0;
while ( $players->have_posts() ) : $players->the_post(); 
        $image_url = wp_get_attachment_image_src( get_post_meta( get_the_id(), 'image_id', true), 'large' );


    if ($i == 0 ) echo "<tr>";
    echo "<td><a class=\"image-link mobilethumb\" href='".get_the_permalink()."'><img src='$image_url[0]' /></a></td>";
    if ($i == $cols - 1) : echo "</tr>"; $i = 0;
    else : $i++; 
    endif;
endwhile;

if ( $i != 0 ) echo "</tr>"; ?>
    </tbody>
</table>
</table>

<table class="playerprofiles">
    <tbody>

    <?php while ( $players->have_posts() ) : $players->the_post(); 
        $image_url = wp_get_attachment_image_src( get_post_meta( get_the_id(), 'image_id', true), 'large' );

        
        ?>
        <tr>
            <td class="thumbs"><a href="<?php the_permalink(); ?>"><img src='<?php echo $image_url[0]; ?>'></img></a></td>
            <td>
                <ul class="metalist">
                    <?php if ( $name = get_post_meta( get_the_id(), 'name', true ) ) { ?><li><strong>Name: </strong><?php echo $name; ?></li><?php } ?>
                    <?php if ( $nickname = get_post_meta( get_the_id(), 'nickname', true ) ) { ?><li><strong>Nickname: </strong><?php echo $nickname; ?></li><?php } ?>
                    <?php if ( $age = get_post_meta( get_the_id(), 'age', true ) ) { ?><li><strong>Age: </strong><?php echo $age; ?></li><?php } ?>
                    <?php if ( $position = get_post_meta( get_the_id(), 'position', true ) ) { ?><li><strong>Position: </strong><?php echo $position; ?></li><?php } ?>
                    <?php if ( $exp = get_post_meta( get_the_id(), 'exp', true ) ) { ?><li><strong>Rugby experience: </strong><?php if ( strlen($exp) > 100 ) { echo substr( $exp, 0, 100 ) . "... (Click photo to read more)"; } else { echo $exp; } ?></li><?php } ?>
                    <?php if ( $jexp = get_post_meta( get_the_id(), 'jexp', true ) ) { ?><li><strong>Prior rugby Experience: </strong><?php if ( strlen($jexp) > 100 ) { echo substr( $jexp, 0, 100 ) . "... (Click photo to read more)"; } else { echo $jexp; } ?></li> <?php } ?>
                    <?php if ( current_user_can('edit_post') ) { ?><li><a class='editsmall' href='<?php echo get_edit_post_link( get_the_id() ); ?>'>Edit profile</a></li><?php } ?>
                </ul>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php endif;



