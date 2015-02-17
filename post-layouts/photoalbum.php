<?php 
$thumb = get_post_meta( get_the_id(), 'primary_photo_url', true );
$description = get_the_content();
?>
<div <?php post_class('post') ?>>
      <header>
      <h2><a href='<?php the_permalink() ?>'><?php the_title() ?></a></h2>
      </header>
      <p class="nomargin">A new photo album has been uploaded to Flickr! Click on the thumbnail below to view the whole album.</p>
      <table class="photosets">
      <tr>
          <td class="photosetsThumbs"><a href='<?php the_permalink() ?>'><img src='<?php echo $thumb ?>' /></a></td>
          <td>
              <ul class="metalist">
                  <?php if ($description) : ?><li><strong>Description</strong><br /><?php echo $description ?></li><?php endif ?>
                  <li><strong>Date Created</strong><br /><?php echo get_the_date('jS \o\f F Y') ?></li>
              </ul>

          </td>
      </tr>    
      </table>
</div>