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
      <div class='fixturemap'>
      <div class='gmap-border'><div class="gmap-canvas" id="map-<?php the_id(); ?>"></div></div>
      <div class='eventMeta fixture'>
          <table>
              <tbody>
                  <tr>
                      <?php if ( $homeaway == 'Home' ) : ?>
                      <td><strong>Home</strong><br />Bristol Bisons RFC</td>
                      <td><strong>Away</strong><br /><?php echo team_link($oppteam, $opplink); ?></td>
                      <?php else : ?>
                      <td><strong>Home</strong><br /><?php echo team_link($oppteam, $opplink); ?></td>
                      <td><strong>Away</strong><br />Bristol Bisons RFC</td>
                      <?php endif ?>
                  </tr>
              </tbody>
          </table>
          <ul>
              <li><h5 class='datesmall'>Date</h5>
                  <?php if ( $textdate ) : echo $textdate; ?>
                  <?php else : ?><time itemProp="startDate" datetime="<?php echo $isodate ?>"><?php echo $fixdate; ?></time><?php endif ?>

              </li>
              <li><h5 class='timesmall'>Kickoff</h5><?php echo $kickoff; ?></strong></li>
              <li><h5 class='timesmall'>Players Arrive</h5><?php echo $playtme; ?></strong></li>
          </ul>
          <ul>
          <li><h5 class="addresssmall">Location</h5><span itemprop="location" class="gmap-address map-<?php the_id(); ?>"><?php echo $address; ?></span></li>
          <?php if($fixface) : ?><li><a class="facebooksmall" href='<?php echo $fbevent; ?>'>Facebook Event</a></li><?php endif ?>
          </ul>
          <div class='clear'></div>
      </div>
      </div>
      <p>This fixture has now been confirmed by the committee; We'd love it if you could come along and support us, and please feel free to bring friends, family and pets along!</p>
      <p>If you are a player with questions about the fixture, please get in touch with the relevant committee member; you can find contact details in the <a href='<?php echo site_url('players-area') ?>'>player&apos;s area</a>. For any other queries, please contact us via the contact form at the bottom of the <a href='<?php echo site_url('about-us') ?>'>about us</a> page.</p>
      <?php comments_template(); ?>
</div>

