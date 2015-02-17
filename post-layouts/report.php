<?php
$parent = get_post_meta( get_the_id(), 'parent-fixture', true);
$fixdate = get_post_meta( $parent, 'fixture-date', true );
$oppteam = get_post_meta( $parent, 'fixture-opposing-team', true );
$opplink = get_post_meta( $parent, 'fixture-opposing-team-website-url', true );
$fixdate = date('jS \o\f  F Y', $fixdate);


?>
<div <?php post_class('post') ?>>
      <header>
          <h2><a href="<?php the_permalink() ?>">Match Report</a></h2>
          <p>Match report posted on the <?php the_time('jS \o\f F Y') ?> by <?php echo get_the_author(); ?> - <a href="<?php the_permalink() ?>#comments"><?php comments_number('No Comments','1 Comment','% Comments'); ?></a><?php edit_post_link( 'Edit post', ' - '); ?></p>
      </header>
      <ul class="metalist">
      <li class='date'>Fixture was on the <?php echo $fixdate; ?></li>
      <li class="info">Played against <?php echo team_link($oppteam, $opplink); ?></li>
      </ul>
      <?php the_content(''); ?>
      <?php comments_template(); ?>
</div>