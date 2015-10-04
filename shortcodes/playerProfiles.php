<?php

function player_profiles_shortcode() {

	$users = get_users();

	foreach ( $users as $user ) { ?>
		<div class='albumThumb'>
			<a class="desktopthumb" href='<?php the_permalink() ?>'><?php the_post_thumbnail() ?></a>

			<div class='profileMeta'>
				<h3><a href=''><?php get_user_meta($user->ID, 'ppName') ?></a></h3>
				<ul>

					<?php if ( $age = get_user_meta($user->ID, 'age', true ) ) { ?>
						<li><strong>Age: </strong><?php echo $age; ?></li><?php } ?>
					<?php if ( $nickname = get_user_meta($user->ID, 'nickname', true ) ) { ?>
						<li><strong>Nickname: </strong><?php echo $nickname; ?></li><?php } ?>
					<?php if ( $position = get_user_meta($user->ID, 'position', true ) ) { ?>
						<li><strong>Position: </strong><?php echo $position; ?></li><?php } ?>
					<?php if ( $exp = get_user_meta($user->ID, 'exp', true ) ) { ?>
						<li><strong>Rugby experience: </strong><?php if ( strlen( $exp ) > 100 ) {
							echo substr( $exp, 0, 100 ) . "... (Click photo to read more)";
						} else {
							echo $exp;
						} ?></li><?php } ?>
					<?php if ( $jexp = get_user_meta($user->ID, 'jexp', true ) ) { ?>
						<li><strong>Prior rugby Experience: </strong><?php if ( strlen( $jexp ) > 100 ) {
								echo substr( $jexp, 0, 100 ) . "... (Click photo to read more)";
							} else {
								echo $jexp;
							} ?></li> <?php } ?>


				</ul>
			</div>
		</div>
	<?php }
}


add_shortcode( 'playerProfiles', 'player_profiles_shortcode' );