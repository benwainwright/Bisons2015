<?php
if ( $_POST && wp_verify_nonce( $_POST['nonce'], 'make_users_active' ) ) {

	foreach ( $_POST['inactiveUsers'] as $user ) {
		delete_user_meta( $user, 'inActive' );
	}
}
?>

<div class="wrap">
	<h2>Inactive Players</h2>

	<p>All players that have been marked as 'inactive' are listed here. While players are marked inactive, they are not
		visible in any other areas of the site (with the exception of those visible to the administrator). To make a
		player active again, click the checkbox and press 'submit'.</p>
	
	<?php $users = getBisonsUsers( true );
	if ( count( $users ) > 0 ) : ?>
		<form method="POST">
			<ul>
				<?php foreach ( $users as $key => $user ) : ?>
					<li class="checkList"><label><input type='checkbox' name="inactiveUsers[]"
					                                    value="<?php echo $user->ID ?>"/><?php echo $user->display_name ?><?php if ( $user->user_email ) { ?> (<?php echo $user->user_email ?>)<?php } ?>
						</label></li>
				<?php endforeach ?>
			</ul>
			<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'make_users_active' ) ?>"/>
			<button class='button button-primary button-large' type="submit">Submit</button>
		</form>
	<?php endif ?>
</div>