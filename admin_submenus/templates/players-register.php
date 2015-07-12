<?php
$register = new RegisterListTable( false,
	array( 'screen' => 'registerList', 'singular' => 'register', 'plural' => 'registers' ) );
$register->prepare_items();


$query = new WP_Query( array(
	'post_type'      => 'attendance_registers',
	'posts_per_page' => - 1,
	'post_status'    => 'publish'
) );

$taxonomy = get_terms( 'seasons' );

foreach ( $taxonomy as $tax ) {
	$taxes[] = array('slug' => $tax->slug, 'name' => $tax->name);
}

$taxes[] = array('slug' => 'current', 'name' => 'Current');

$statsRows = array();


foreach ( $taxes as $seasons ) {

	$attendance = getAttendance( false, $seasons['slug'] )['stats'];

	if ( $attendance['totalSessions'] > 0 ) {
		$statsRows[ $seasons['name'] ] = $attendance;
	}
}

?>
<div class="wrap">
	<h2>Register <a class='add-new-h2' href='<?php echo admin_url( 'post-new.php?post_type=attendance_registers' ) ?>'>Record
			Attendance</a></h2>
	<table class="widefat">
		<thead>
		<tr>
			<th>Season</th>
			<th>Sessions</th>
			<th>Total Trained</th>
			<th>Average Trained</th>
			<th>Total Present</th>
			<th>Average Present</th>
		</tr>
		</thead>


		<tbody>
		<?php foreach ( $statsRows as $season => $data ) : ?>

			<tr>
				<td><?php echo $season ?></td>
				<td><?php echo $data['totalSessions'] ?></td>
				<td><?php echo count( $data['allTrained'] ) ?></td>
				<td><?php echo $data['averagePlayersTraining'] ?></td>
				<td><?php echo count( $data['allTrained'] ) + count( $data['allWatched'] ) + count( $data['allCoached'] ) ?></td>
				<td><?php echo $data['averagePlayersPresent'] ?></td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>
	<p>Hover over a register entry to edit or delete.</p>
	<?php $register->display() ?>
</div>