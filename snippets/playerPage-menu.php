<ul class='pageMenu'>

	<?php global $pagename; if ( $pagename != '' ) :  ?>
		<li><a class="fa    fa-arrow-circle-left fa-lg" href="<?php echo site_url('players-area') ?>">Player's Area</a></li>
	<?php endif ?>
	<?php if ( current_user_can('edit_post', get_the_id()) ) { ?>
		<li><a class='fa fa-plus-square fa-lg' href='<?php echo $GLOBALS['blog_info']['url']; ?>/wp-admin/post-new.php?post_type=player-page'>Add</a></li>
		<li><a class='fa fa-wordpress fa-lg' href='<?php echo admin_url(); ?>'>Dashboard</a></li>


	<?php } ?>
	<?php if (get_user_meta(get_current_user_id(), 'joined', true)) : ?>
		<li><a class='fa fa-credit-card fa-lg' href='<?php echo site_url('players-area/your-subs') ?>'>Subs</a></li>
		<li><a class='fa fa-info fa-lg' href='<?php echo site_url('players-area/membership-form') ?>'>Your Details</a></li>
	<?php else : ?>
		<li><a class='fa fa-info fa-lg' href='<?php echo site_url('players-area/membership-form') ?>'>Join</a></li>
	<?php endif ?>
	<li><a class='fa fa-user fa-lg' href='<?php echo site_url('players-area/player-profile') ?>'>Profile</a></li>

</ul>