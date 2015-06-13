<div class="wrap">
	<h2>New Season</h2>
	<form action='<?php echo admin_url('tools.php?page=new-season') ?>' method="POST">

<?php
if ($_POST && count ( $_POST['newCommittee'] ) > 0 && ! isset( $_POST['confirm'])) :
$newCommitteeMembers = array_unique($_POST['newCommittee']);
$currentCommittee = get_users(array('role' => 'committee_member'));
$unChanged = array();
$downgrading = array();



foreach($currentCommittee as $oldCommitteeMember) {


	$removed = true;

	foreach ( $newCommitteeMembers as $key => $newCommitteeMember ) {

		if ( $newCommitteeMember == $oldCommitteeMember->ID ) {

			$unChanged[] = $newCommitteeMember;
			unset( $newCommitteeMembers[ $key ] );
			$removed = false;
		}
	}

	if ( $removed ) {
		$downgrading[] = $oldCommitteeMember->ID;
	}
	$newCommitteeMembers = array_filter($newCommitteeMembers);
}

?>

	<p>This will perform the following actions. Are you <strong><em>sure</em></strong> you want to do this?</p>
	<ul>
		<li>Add the season tag <?php echo $_POST['seasonName'] ?> to all fixtures, events, email logs, and GoCardless event logs that don't already have one.<input type='hidden' name='seasonName' value='<?php echo $_POST['seasonName'] ?>' /></li>
		<?php if ( count ( $newCommitteeMembers ) > 0 ) : ?>
		<li>Upgrade the status of the following users to <strong>Committee Member</strong></li>
		<li class="containList">
			<ul>
				<?php foreach($newCommitteeMembers as $newMember) : ?>
					<?php $user = get_userdata( $newMember ); ?>
					<li><?php echo $user->first_name . ' ' . $user->last_name ?><input type='hidden' name='upgrade[]' value='<?php echo $newMember ?>'/></li>
				<?php endforeach ?>
			</ul>
		</li>
		<?php endif ?>
		<?php if ( count ( $downgrading ) > 0 ) : ?>
		<li>Downgrade the status of the following user to <strong>Player</strong></li>
		<li class="containList">
			<ul>
				<?php foreach($downgrading as $oldMember) : ?>
					<?php $user = get_userdata( $oldMember ); ?>
					<li><?php echo $user->first_name . ' ' . $user->last_name ?><input type='hidden' name='downgrade[]' value='<?php echo $oldMember ?>'/></li>
				<?php endforeach ?>
			</ul>
		</li>
		<?php endif ?>
		<?php if ( count ( $unChanged ) > 0 ) : ?>
		<li>Take no action on the following as they already have <strong>Committee Member</strong> status</li>
		<li class="containList">
			<ul>
				<?php foreach($unChanged as $sameMember) : ?>
					<?php $user = get_userdata( $sameMember ); ?>
					<li><?php echo $user->first_name . ' ' . $user->last_name ?></li>
				<?php endforeach ?>
			</ul>
		</li>
		<?php endif ?>
	</ul>
	<button class='button' type="submit" name='confirm' value="yes">Yes</button>
	<button class='button' type="submit" name='confirm' value="no">No</button>
<?php elseif ($_POST['confirm'] === 'yes') :


	// Add taxonomy
	$types = array('fixtures', 'GCLBillLog', 'GCLSubLog', 'GCLPreAuthLog', 'attendance_registers', 'email_log', 'events');

	$termId = wp_insert_term($_POST['seasonName'], 'seasons')['term_id'];

	foreach ($types as $type) {
		$query = new WP_Query( array ( 'post_type' => $type,
		                               'posts_per_page' => -1,
		                               'tax_query' => wp_excludePostsWithTermTaxQuery('seasons') ) );


		while ($query->have_posts()) {
			$query->the_post();
			new dBug(wp_set_object_terms(get_the_id(), (int) $termId, 'seasons'));
		}

	}


	$downgradingSelf = false;

	foreach ($_POST['upgrade'] as $userID) {
		$u = new WP_User( $userID );
		$u->remove_role( 'player' );
		$u->add_role( 'committee_member' );
		send_mandrill_template($userID, 'user-upgraded', array(), array('admin'));
	}

	foreach ($_POST['downgrade'] as $userID) {
		$u = new WP_User( $userID );
		$u->remove_role( 'committee_member' );
		$u->add_role( 'player' );
		send_mandrill_template($userID, 'user-downgraded', array(), array('admin'));

		if ($userID == get_current_user_id()) {
			$downgradingSelf = true;
		}
	}

	if($downgradingSelf) {
		$homePage = site_url();
		echo "<script type='text/javascript'>setTimeout(function(){ document.location = '$homePage';},3000)</script>";
	}

	echo "<p>Done! If your own committee status has been revoked, you will be redirected to the home page shortly.</p>";



	else: ?>


	<p>Clicking the 'Create Season' button at the bottom of this page will run a script which sets the website up for the new season by performing the following actions:</p>
	<ul>
		<li>Creating a new 'season' tag in the database with a name you supply.</li>
		<li>Applying that tag to all fixtures, events, GoCardless logs and Email logs that don't already have a season tag.</li>
		<li>Downgrade all committee members to players.</li>
		<li>Upgrade players selected below to committee.</li>
		<li>Send out an email to these users notifying them of the changes.</li>
	</ul>
	<p>Do NOT use these script unless you are CERTAIN you are doing it correctly. If you do not select yourself as committee for the new season, <strong>you will LOSE your committee privileges and will no longer be able to access this dashboard</strong>.</p>

	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="seasonName">Season Tag Name</label></th>
				<td>
					<input class='notempty' type='text' name='seasonName' value='<?php echo (date('Y') - 1) . '/' . date('Y') ?>' />
					<span class="description">Don't change this unless you have a particularly good reason for doing so</span>
				</td>
			</tr>

			<?php $users = get_users(); ?>

			<?php for($i = 1; $i <= 10; $i++) : ?>
			<tr>
				<th><label for="newCommittee">Committee Member <?php echo $i ?></label></th>
				<td>
					<select name="newCommittee[]">
						<option value="0"></option>
						<?php foreach ($users as $user) : ?>
							<option value="<?php echo $user->ID?>"><?php echo $user->first_name . ' ' . $user->last_name ?></option>
						<?php endforeach ?>
					</select>
				</td>
			</tr>

			<?php endfor ?>
		</tbody>
	</table>


	<button class='button' type="submit" value="create">Create Season</button>

<?php endif ?>
	</form>
</div>
