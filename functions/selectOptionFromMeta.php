<?php
function selectOptionFromMeta( $user, $field, $test, $label = false, $data = false ) {
	$label = $label ? $label : $test;


	if ( is_array( $data ) ) {

		$dataString = '';

		foreach ( $data as $key => $theData ) {

			$theData = addSlashes( $theData );
			$dataString .= " data-$key='$theData'";
		}
	} else if ( is_string( $data ) ) {
		$data       = addSlashes( $data );
		$dataString = " data-info='$data'";
	}

	?>
	<option<?php if ( isset ( $dataString ) ) {
		echo $dataString; } ?> value="<?php echo $test ?>"<?php selected( get_user_meta( $user, $field, true ),
		$test ) ?>><?php echo $label ?></option> <?php
}