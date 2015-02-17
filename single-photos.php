<?php get_header(); ?>       
<?php while ( have_posts() ) : the_post();
    $flikr = new Flikr ( $GLOBALS['api_settings'] );
    $photos = $flikr->photosetsGetPhotos ( get_post_meta( get_the_id(), 'setid', true), 'url_q,url_o,url_m' )->photoset->photo;
    $photo_rows = array_chunk ( $photos, $_GET['cols'] ? $_GET['cols'] : 5 );
    ?>

<div id="wrapper">

    <div id="pagecol">
        <div class='page'>
<?php if ( $GLOBALS['bisons_flash_message'] ) : ?>
        <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
    <?php endif ?>
        <header>
            <h2><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
        </header>
        <ul class='nobullet'>
            <li class='date'>Album created on <a href='https://www.flickr.com/photos/bisonsrfc/'>Flickr</a> on the <?php the_date('jS \o\f F Y') ?></li>
            <?php if ( get_post_meta( get_the_id(), 'description', true) ) : ?><li><?php echo get_post_meta( get_the_id(), 'description', true) ?></li><?php endif ?>
        </ul>
        <p></p>
        <table class="photogallery">
            <tbody>
            <?php foreach ( $photo_rows as $row ) : ?>
                <tr>
                    <?php foreach ( $row as $photo ) :  ?>
                        <td><a class='image-link' href='<?php echo $photo->url_o ?>'><img src='<?php echo $photo->url_q ?>' /></a></td>
                    <?php endforeach ?>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<?php endwhile ?>
<?php get_footer(); ?>
