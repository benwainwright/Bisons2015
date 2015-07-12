<?php

function getBisonsUsers()
{
	return get_users( array('meta_key' => 'inActive', 'meta_compare' => 'NOT EXISTS'));
}