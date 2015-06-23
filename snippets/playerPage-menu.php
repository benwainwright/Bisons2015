<ul class='pageMenu'>

	<?php global $pagename; if ( $pagename != '' ) :  ?>
		<li><a href="<?php echo site_url('players-area') ?>"><i class="fa fa-arrow-circle-left"></i>Player's Area</a></li>
	<?php endif ?>
	<?php if ( current_user_can('edit_post', get_the_id()) ) { ?>
		<li><a href='<?php echo $GLOBALS['blog_info']['url']; ?>/wp-admin/post-new.php?post_type=player-page'><i class='fa fa-plus-square' ></i>Add</a></li>
		<li><a href='<?php echo admin_url(); ?>'><i class='fa fa-wordpress'></i>Dashboard</a></li>


	<?php } ?>
	<?php if (get_user_meta(get_current_user_id(), 'joined', true)) : ?>
		<li><a href='<?php echo site_url('players-area/your-subs') ?>'><i class='fa fa-credit-card'></i>Subs</a></li>
		<li><a href='<?php echo site_url('players-area/membership-form') ?>'><i class='fa fa-info'></i>Details</a></li>
	<?php else : ?>
		<li><a href='<?php echo site_url('players-area/membership-form') ?>'><i class='fa fa-info'></i>Join</a></li>
	<?php endif ?>
	<li><a href='<?php echo site_url('players-area/player-profile') ?>'><i class='fa fa-user'></i>Profile</a></li>
	<li><a href='<?php echo site_url('players-area/change-password') ?>'><i class='fa fa-key'></i>Password</a></li>

</ul>