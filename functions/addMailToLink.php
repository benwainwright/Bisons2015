<?php
/**
 * Simple function to create html anchor tags containing properly escaped mailto: links
 *
 * @param $email
 * @param bool $text
 * @param bool $subject
 * @param bool $body
 * @param bool $cc
 * @param bool $bcc
 *
 * @return string
 */
function addMailToLink($email, $text = false, $subject = false, $body = false, $cc = false, $bcc = false) {

	$content = array();

	if ( $subject ) {
		$subject = rawurlencode($subject);
		$content[] = "subject=$subject";
	}

	if ( $body ) {
		$body = rawurlencode($body);
		$content[] = "body=$body";
	}

	if ( $cc ) {
		$cc = rawurlencode($cc);
		$content[] = "cc=$cc";
	}

	if ( $bcc ) {
		$bcc = rawurlencode($bcc);
		$content[] = "bcc=$bcc";
	}

	if ( count ( $content ) > 0 ) {
		$contentString = '?' . implode( '&', $content );
	}

	else {
		$contentString = '';
	}

	$text = $text ? $text : $email;

	return "<a href='mailto:$email$contentString'>$text</a>";

}