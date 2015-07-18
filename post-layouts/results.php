<?php
$parent     = get_post_meta( get_the_id(), 'parent-fixture', true );
$fixdate    = get_post_meta( $parent, 'fixture-date', true );
$fixdate    = date( 'jS \o\f F Y', $fixdate );
$opposing   = get_post_meta( $parent, 'fixture-opposing-team', true );
$opplink    = get_permalink( get_post_meta( $parent, 'fixture_team', true ) );
$ourscore   = get_post_meta( get_the_id(), 'our-score', true );
$theirscore = get_post_meta( get_the_id(), 'their-score', true );
$homeaway   = get_post_meta( $parent, 'fixture-home-away', true );

?>
<div <?php post_class( 'post' ) ?>>
	<header>
		<h2><a href="<?php the_permalink() ?>">Match Result</a></h2>
		<?php include( __DIR__ . '/../snippets/post_meta.php' ) ?>
	</header>
	<div class='metaBox fixture'>

		<a itemprop="url" href="<?php the_permalink() ?>"><?php if ( has_post_thumbnail( $parent ) ) {
				echo get_the_post_thumbnail( $parent );
			} else { ?><img src='<?php echo get_template_directory_uri() ?>/images/ball.jpg' /><?php } ?></a>

		<div class='eventMeta'>
			<h3><?php echo $fixdate; ?></h3>
			<ul>

				<?php if ( $homeaway == 'Home' ) : ?>
					<li class="teamName"><i class="fa fa-star"></i>Bristol Bisons RFC (Home)</li>
					<li class='score'><?php echo $ourscore ?></li>
					<li class="teamName"><a href='<?php echo $opplink ?>'><i
								class="fa fa-star"></i><?php echo $opposing ?></a> (Away)
					</li>
					<li class='score'><?php echo $theirscore ?></li>
				<?php else : ?>
					<li class="teamName"><a href='<?php echo $opplink ?>'><i
								class="fa fa-star"></i><?php echo $opposing ?></a> (Home)
					</li>
					<li class='score'><?php echo $theirscore ?></li>
					<li class="teamName"><i class="fa fa-star"></i>Bristol Bisons RFC (Away)</li>
					<li class='score'><?php echo $ourscore ?></li>
				<?php endif ?>
			</ul>
			<?php if ( get_post_meta( get_the_id(), 'match_event_type_0', true ) ) : ?>
				<h4>Achievements</h4>
				<ul class='small'>
					<?php for ( $i = 0; get_post_meta( get_the_id(), 'match_event_type_' . $i, true ); $i ++ ) :
						$event_key    = get_post_meta( get_the_id(), 'match_event_type_' . $i, true );
						$event_player = get_user_by( 'id',
							get_post_meta( get_the_id(), 'match_event_player_' . $i, true ) );

						if ( isset( $player_name ) ) {
							$player_name = $event_player->data->display_name;
						}

						?>
						<li><i class="fa fa-flag"></i><?php global $match_events;
							echo $match_events[ $event_key ][0];

							if ( isset ( $player_name  ) ) {
							echo get_profile_url( $event_player->ID ) ? ' - <a href="' . get_profile_url( $event_player->ID ) . '">' . $player_name . '</a>' : ' - ' . $player_name;
								} ?>
						</li>
					<?php endfor ?>
				</ul>
			<?php endif ?>

			<div class='clear'></div>
		</div>
	</div>

	<p>Match results are now in. Match reports and photos will follow shortly. If you believe this result to be
		incorrect, please let me know.</p>


	<?php comments_template(); ?>
</div>