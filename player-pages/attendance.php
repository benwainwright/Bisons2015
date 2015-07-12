<?php

wp_enqueue_script('dynamicforms');

$formUser = bisonsGetUser();

$attendance = getAttendance()['players'][$formUser];
$stats = $attendance['stats'];
$rows = array();


$marks = array(
	'p' => '<td class=\'attendanceGood\'>Training</td>',
	'c' => '<td class=\'attendanceGood\'>Coaching</td>',
	'w' => '<td class=\'attendanceOK\'>Watching</td>',
	'a' => '<td class=\'attendanceBad\'>Absent</td>'
);


$totalPoss = $stats['training'] + $stats['coaching'] + $stats['watching'] + $stats['absent'];

if ( $totalPoss > 0 ) {

	$sessionsPresent = $stats['training'] + $stats['coaching'] + $stats['watching'];
	$attendanceInfo = array(
		'Total Possible Sessions'   => $totalPoss,
		'Sessions Present'          => $sessionsPresent,
		'Attendance Percentage'     => (100/$totalPoss)*$sessionsPresent . '&#37;'
	);
}




foreach ( $attendance['register'] as $session ) {

	if ( ! isset ( $rows[ $session['date']]) || $rows[ $session['date']] === 'a' ) {
		$rows[$session['date']] = $marks[$session['mark']];
	}

}
?>

<header>
	<h2>Attendance</h2>
	<?php get_template_part( 'snippets/playerPage', 'menu' ) ?>
</header>


<?php if  ( current_user_can ('committee_perms') ) : ?>
<form>
	<fieldset>
		<legend>Active Player</legend>
		<div>
			<label>Select</label>
			<select id='committeeSelectPlayer'>
				<option value='me'>Me</option>
				<?php $users = get_users(); foreach ($users as $user) : ?>
					<option value='<?php echo $user->data->ID; ?>' <?php if ( isset ( $_GET['player_id' ] ) ) { if (  $_GET['player_id' ] == $user->data->ID ) { echo " selected='selected'"; } } ?>><?php echo $user->data->display_name ?></option>
				<?php endforeach ?>
			</select>
		</div>
	</fieldset>
</form>
	<?php endif ?>


<p>Keeping attendance data helps us keep tabs on how the team is doing and can be really useful for other reasons. If you think the information below is wrong, please get in touch...</p>
<?php if ( count ( $rows) > 0 ) : ?>
<h3>Current Season Statistics</h3>
	<table>
		<tbody>
		<tr>
			<th>Possible Sessions</th>
			<td><?php echo $attendanceInfo['Total Possible Sessions'] ?></td>
		</tr>

		<tr>
			<th>Sessions Attended</th>
			<td><?php echo $attendanceInfo['Sessions Present'] ?></td>
		<tr>
			<th>Attendance Percentage</th>
			<td><?php echo round ( $attendanceInfo['Attendance Percentage'] )?>%</td>
		</tr>
		</tbody>
	</table>

	<h3>Registers Recorded</h3>
	<table>
		<tbody>
		<?php foreach ( $rows as $date => $mark ) : ?>
			<tr>
				<th><?php echo date('M j, Y', $date) ?></th>
				<?php echo $mark ?>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>
<?php endif ?>


