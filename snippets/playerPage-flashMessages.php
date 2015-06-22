<?php global $bisonPlayersFlashMessage;


foreach ( $bisonPlayersFlashMessage as $message ) {

	$current = array(
		'priority' => 0
	);

	if ( $message['priority'] > $current['priority'] ) {
		$current = $message;
	}
}

if ( $current['priority'] > 0 ) : ?>
	<form class="flashmessage" method="POST">
	<p ><i class='fa fa-bell-o'></i><?php echo $current['message'] ?></p>
	<?php if ( isset( $current['confirmButtons'] ) ) : ?>
		<button type='submit' name='confirm_change' value='ok'>OK</button>
		<button type='submit' name='confirm_change' value='cancel'>Cancel</button>

	<?php endif ?>
	</form>
<?php endif ?>