<?php

function plural_word ( $number, $singular, $plural )
{
	return $number > 1 ? $plural : $singular;
}
