<?php

if ( ! is_user_logged_in() ) {
	wp_redirect ( home_urL() );
	exit;
}

if ( $_FILES ) {
	$file = $_GET['name'];
	$post = $_GET['postId'];

	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );

	$overrides = array( 'test_form' => false );


	$return = wp_handle_upload( $_FILES['my_image_upload'], $overrides, date( 'Y/m' ) );

	$fileName = pathinfo( $return['file'])['basename'];

	$thumbName = pathinfo( $return['file'])['filename'] . '_thumb.' . pathinfo( $return['file'])['extension'];
	$thumb = wp_get_image_editor( $return['file'], $args);

	if ( ! is_wp_error( $thumb ) ) {
		$thumb->resize( 128, 128, true );
		$thumb->save( dirname ( $return['file'] ) . '/' . $thumbName );
	}

	$return['size'] = filesize( $return['file']);
	$return['name'] = $fileName;
	$return['thumbnailUrl'] = dirname ($return['url'] ) . '/' . $thumbName;
	$return['deleteUrl'] = $return['url'];
	$return['deleteType'] = 'DELETE';

	echo json_encode($return);

	header ( 'Content-Type: application/json');

} else { ?>

<form id="featured_upload" method="post" action="#" enctype="multipart/form-data">
	<input type="file" name="my_image_upload" id="my_image_upload"  multiple="false" />
	<input type="hidden" name="post_id" id="post_id" value="55" />
	<?php wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' ); ?>
	<input id="submit_my_image_upload" name="submit_my_image_upload" type="submit" value="Upload" />
</form>

<?php }

