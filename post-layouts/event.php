<?php
$eventdate = get_post_meta(get_the_id(), 'date', true );
$date = date('jS \o\f F Y', $eventdate);
$isodate = date('c', $eventdate);
$enddate = get_post_meta( get_the_id(), 'enddate', true ) ? date( 'jS \o\f F Y' , (int) get_post_meta( get_the_id(), 'enddate', true ) ) : null;
$time = get_post_meta(get_the_id(), 'time', true ) ? reformat_date(get_post_meta(get_the_id(), 'time', true ), 'g:ia') :  false; 
$endtime = get_post_meta(get_the_id(), 'endtime', true );
$address = get_post_meta(get_the_id(), 'address', true );
$fbevent = get_post_meta(get_the_id(), 'facebook-event', true );
$description = get_post_meta(get_the_id(), 'description', true );
$image_id = get_post_meta(get_the_id(), 'image_id', true );
$image_url = wp_get_attachment_url( $image_id );

?>
<div itemscope itemtype="http://data-vocabulary.org/Event" <?php post_class('post') ?>>
      <header>
          <h2><a itemprop="url" href="<?php the_permalink() ?>"><span itemprop="summary"><?php the_title(); ?></span></a></h2>
          <?php include( __DIR__ . '/../snippets/post_meta.php' ) ?>
      </header>
      
            <div class="metaBox">
                  <?php 
                  if ( has_post_thumbnail() ) {
                   
                        $thumbnailAtributes = array(
                              'itemprop'  => 'photo',
                              'class'     => 'alignright'
                        );
                        the_post_thumbnail();
                  } 
                  ?>
                  <div class='eventMeta'>
                      <h4>Event Details <?php if($fbevent) : ?><br /><a class="fblink facebooksmall" href='<?php echo $fbevent; ?>'>Facebook Event</a><?php endif ?></h4>
                  <ul>
                        <?php echo datetime_string ( $date, $enddate, $time, $endtime, false, $isodate ) ?>
                  </ul>
        	      <ul>
            	      <li><h5 class='addresssmall'>Location</h5><?php echo str_replace ("\n", '<br />', $address) ?></li>
		      </ul>
            <div class='clear'></div>
            </div>
      </div>
      <span itemprop="description"><?php if ( is_single() ) the_content(); else the_excerpt(); ?></span>
      <?php comments_template(); ?>
</div>