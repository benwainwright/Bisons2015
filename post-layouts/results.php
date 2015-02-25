<?php
$parent = get_post_meta( get_the_id(), 'parent-fixture', true);
$fixdate = get_post_meta( $parent, 'fixture-date', true );
$fixdate = date('jS \o\f F Y', $fixdate); 
$opposing = get_post_meta( $parent, 'fixture-opposing-team', true );
$opplink = get_post_meta( $parent, 'fixture-opposing-team-website-url', true );
$ourscore = get_post_meta( get_the_id(), 'our-score', true );
$theirscore = get_post_meta( get_the_id(), 'their-score', true );
$homeaway = get_post_meta($parent, 'fixture-home-away', true);

?>
<div <?php post_class('post') ?>>
      <header>
          <h2><a href="<?php the_permalink() ?>">Match Result</a></h2>
          <?php include( __DIR__ . '/../snippets/post_meta.php' ) ?>
      </header>
      <div class='metaBox fixture'>
    
            <a itemprop="url" href="<?php the_permalink() ?>"><?php if ( has_post_thumbnail($parent) ) { echo get_the_post_thumbnail($parent); } else { ?><img src='<?php echo get_template_directory_uri() ?>/images/ball.jpg' /><?php }?></a>
      <div class='eventMeta'>
      		<h4><?php echo $fixdate; ?></h4>
            <ul>
                  
                  <?php if ( $homeaway == 'Home' ) : ?>
                  <li class="fa fa-star teamName">Bristol Bisons RFC (Home)</li>
                  <li><?php echo $ourscore ?></li>
                  <li class="fa fa-star teamName"><?php echo team_link($opposing, $opplink); ?> (Away)</li>
                  <li><?php echo $theirscore ?></li>
                  <?php else : ?>
                  <li class="fa fa-star teamName"><?php echo team_link($opposing, $opplink); ?> (Home)</li>
                  <li><?php echo $theirscore ?></li>
                  <li class="fa fa-star teamName">Bristol Bisons RFC (Away)</li>
                  <li><?php echo $ourscore ?></li>
                  <?php endif ?>
              </ul>
          
          <div class='clear'></div>
      </div>
      </div>

      <p>Match results are now in. Match reports and photos will follow shortly. If you believe this result to be incorrect, please let me know.</p>

                            
<?php comments_template(); ?>
</div>