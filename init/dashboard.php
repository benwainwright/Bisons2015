<?php
function add_dashboard_widgets() {
	wp_add_dashboard_widget(
		'bisons-info-widget',
		'Bisons Custom Theme',
		'display_bisons_info'
	);

	wp_add_dashboard_widget(
		'server-time-widget',
		'Server Time',
		'display_server_time'
	);

	wp_add_dashboard_widget(
		'top-ten-attenders',
		'Top Ten Attenders',
		'displayTopTenAttenders'
	);
	/*
	wp_add_dashboard_widget(
		'bisons-fixtures-widget',
		'Fixtures',
		'display_fictures_widget'
	); */
}

add_action( 'wp_dashboard_setup', 'add_dashboard_widgets' );


function displayTopTenAttenders() {
	$attendance = getAttendance()['players'];

	$attendancePercentages = array();

	foreach ( $attendance as $playerID => $details ) {

		$t                                  = $details['stats']['training'];
		$w                                  = $details['stats']['watching'];
		$c                                  = $details['stats']['coaching'];
		$a                                  = $details['stats']['absent'];
		$p                                  = $t + $w + $c;
		$sum                                = $p + $a;
		$presentPercentage                  = ( 100 / $sum ) * $p;
		$attendancePercentages[ $playerID ] = $presentPercentage;
	}

	arsort( $attendancePercentages );

	$i      = 1;
	$topTen = array();

	foreach ( $attendancePercentages as $userID => $percentage ) {

		$topTen[ $i ] = array(
			'name'       => get_user_by( 'id', $userID )->user_nicename,
			'ID'         => $userID,
			'percentage' => $percentage
		);

		if ( $i > 9 ) {
			break;
		} else {
			$i ++;
		}
	}

	?>
	<p>Attendance is calculated including sessions watched or coached.</p>
	<table class="widefat">
		<tbody>
		<?php foreach ( $topTen as $position => $row ) : ?>
			<tr>
				<td><?php echo $position ?></td>
				<td><?php echo $row['name'] ?></td>
				<td><?php echo round ( $row['percentage'] ) ?>%</td>
			</tr>
		<?php endforeach ?>
		</tbody>
	</table>
<?php

}

function display_bisons_info() { ?>
	<p>This website runs on a purpose built theme with quite a lot of customisation. For more details about how to use
		various aspects of the site, checkout the <a href='/committee-area/'>committee area</a>.</p>

<?php }

function display_server_time() {

	echo '<p>The server time is ' . date( 'g:i:s a' ) . '</p>';
}

$taxonomy = get_terms( array( 'seasons' ) );
foreach ( $taxonomy as $tax ) {
	if ( is_object( $tax ) ) {
		$taxeslight[] = $tax->name;
	}
}

function display_fictures_widget() {
	$fixtures = new WP_Query( array(
		'post_type' => 'fixture',
		'nopaging'  => 'true',
		'orderby'   => 'meta_value',
		'meta_key'  => 'fixture-date',
		'order'     => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'seasons',
				'field'    => 'slug',
				'terms'    => $taxeslight,
				'operator' => 'NOT IN'
			)
		)
	) );

	?>
	<div class="main">
		<ul>
			<?php while ( $fixtures->have_posts() ) : $fixtures->the_post(); ?>
				<li><span><?php echo get_post_meta( get_the_id(), 'fixture-opposing-team',
							true ) ?></span><?php echo get_post_meta( get_the_id(), 'fixture-opposing-team', true ) ?>
				</li>
			<?php endwhile; ?>
		</ul>
	</div>
<?php }


