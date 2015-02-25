<?php
$postdate = get_post_meta( get_the_id(), 'fixture-date', true );
$textdate = get_post_meta( get_the_id(), 'text-date', true );
$kickoff = get_post_meta( get_the_id(), 'fixture-kickoff-time', true ) ? get_post_meta( get_the_id(), 'fixture-kickoff-time', true ) : 'TBC';
$kickoffexplode = explode ( ':', $kickoff);
$hour = $kickoffexplode[0];
$minute = $kickoffexplode[1];
$kickoff = reformat_date($kickoff, 'g:ia');
$fixdate = date('jS \o\f F Y', (int) $postdate);
$isodate = mktime ( (int) $hour, (int) $minute, 0, date('n', (int) $postdate), date('j', (int) $postdate), date('Y', (int) $postdate) );
$isodate = date('c', $isodate);
$oppteam = get_post_meta( get_the_id(), 'fixture-opposing-team', true ) ? get_post_meta( get_the_id(), 'fixture-opposing-team', true ) : 'TBC';
$opplink = get_post_meta( get_the_id(), 'fixture-opposing-team-website-url', true ) ? get_post_meta( get_the_id(), 'fixture-opposing-team-website-url', true ) : false;

$playtme = get_post_meta( get_the_id(), 'fixture-player-arrival-time', true ) ? get_post_meta( get_the_id(), 'fixture-player-arrival-time', true ) : false;
$playtme = reformat_date($playtme, 'g:ia');
$address = wpautop ( get_post_meta( get_the_id(), 'fixture-address', true ) ? get_post_meta( get_the_id(), 'fixture-address', true ) : 'TBC' );
$gmpcode = get_post_meta( get_the_id(), 'fixture-gmap', true ) ? get_post_meta( get_the_id(), 'fixture-gmap', true ) : false;
$fixface = get_post_meta( get_the_id(), 'fixture-facebook-event', true ) ? get_post_meta( get_the_id(), 'fixture-facebook-event', true ) : false;
$gmpcode = html_entity_decode($gmpcode);
$homeaway = get_post_meta(get_the_id(), 'fixture-home-away', true);
?>
<div itemscope itemtype="http://data-vocabulary.org/Event" <?php post_class('post') ?>>
      <header>
          <h2><a itemprop="url" href="<?php the_permalink() ?>"><span itemprop="eventType">Fixture</span> Details</a></h2>
          <?php include( __DIR__ . '/../snippets/post_meta.php' ) ?>
      </header>
      <div class='metaBox fixture'>
    		 

            <a itemprop="url" href="<?php the_permalink() ?>"><?php if ( has_post_thumbnail() ) { $thumbnailAtributes = array( 'itemprop'  => 'photo' );the_post_thumbnail("large", $thumbnailAtributes); } else { ?><img src='<?php echo get_template_directory_uri() ?>/images/ball.jpg' /><?php }?></a>
      <div class='eventMeta'>
      		<h4><?php if ( $textdate ) : echo $textdate; ?><?php else : ?><time itemProp="startDate" datetime="<?php echo $isodate ?>"><?php echo $fixdate; ?></time><?php endif ?></h4>
            <ul>
                  
                  <?php if ( $homeaway == 'Home' ) : ?>
                  <li class="fa fa-star teamName">Bristol Bisons RFC (Home)</li>
                  <li>Vs</li>
                  <li class="fa fa-star teamName"><?php echo team_link($oppteam, $opplink); ?> (Away)</li>
                  <?php else : ?>
                  <li class="fa fa-star teamName"><?php echo team_link($oppteam, $opplink); ?> (Home)</li>
                  <li>Vs</li>
                  <li class="fa fa-star teamName">Bristol Bisons RFC (Away)</li>
                  <?php endif ?>
              </ul>

                  <?php if (is_single()) { ?>
              <ul class='extra'>
                  <li class="fa fa-map-marker">Location<span itemprop="location"><br /><a href='http://maps.google.com?q=<?php echo strip_tags($address); ?>'><?php echo $address; ?></a></span></li>
                  <li class="fa fa-clock-o"><strong>Kickoff</strong><br /><?php echo $kickoff; ?></li>
                  <li class="fa fa-clock-o"><strong>Players Arrive</strong><br /><?php echo $playtme; ?></li>
                  <?php if($fixface) : ?><li class='fa fa-facebook-square'><a href='<?php echo $fbevent; ?>'>Facebook Event</a></li><?php endif ?>
              </ul>
                  <?php } ?>

          
          <div class='clear'></div>
      </div>
      </div>
      <p>Please note that these details are subject to change at any point. Check the <a href='<?php echo home_url('/fixtures/') ?>' title='fixtures'>fixtures</a> page for the most up to date information. Should you need more information about a fixture, please get in touch.</p>
      <?php if (! is_single() ) { ?><p>Click on the rugby ball above for more details...</p><?php } ?>
      <?php comments_template(); ?>
</div>

