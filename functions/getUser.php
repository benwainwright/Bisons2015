<?php

function bisonsGetUser() {
	return ( isset ( $_GET['player_id'] ) && current_user_can( 'committee_perms' ) )
		? $_GET['player_id'] : get_current_user_id();
}
