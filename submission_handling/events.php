<?php
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}

if (isset ( $_POST['date'] ) ) { update_post_meta($post, 'date', strtotime( $_POST['date'] )); }
if (isset ( $_POST['enddate'] ) ) { update_post_meta($post, 'enddate', strtotime( $_POST['enddate']));}
if (isset ( $_POST['time'] ) ) { update_post_meta($post, 'time', esc_attr( $_POST['time'] ));}
if (isset ( $_POST['endtime'] ) ) { update_post_meta($post, 'endtime', esc_attr( $_POST['endtime'] ));}
if (isset ( $_POST['address'] ) ) { update_post_meta($post, 'address', esc_attr( $_POST['address'] ));}
if (isset ( $_POST['facebook-event'] ) ) { update_post_meta($post, 'facebook-event', esc_attr( $_POST['facebook-event'] ));}
if (isset ( $_POST['upload_image_id'] ) ) { update_post_meta($post, 'image_id', $_POST['upload_image_id']);}


if ( isset ( $_POST['hide_from_blog'] ))
{
	if ($_POST['hide_from_blog'] == 'true')
	{
	    update_post_meta($post, 'hide_from_blog', 'true');
	} else 
	{
	    delete_post_meta($post, 'hide_from_blog');
	}
}


if ( isset ( $_POST['whitebackground'] ))
{
	if ($_POST['whitebackground'] == 'true')
	{
	    update_post_meta($post, 'whitebackground', 'true');
	} else 
	{
	    delete_post_meta($post, 'whitebackground');
	}
}

if ( isset ( $_POST['allDay'] ))
{
	if ($_POST['allDay'] == 'true')
	{
	    update_post_meta($post, 'allDay', 'true');
	} else 
	{
	    delete_post_meta($post, 'allDay');
	}
}


if ( isset ( $_POST['ical_only'] ))
{
	if ($_POST['ical_only'] == 'true')
	{
	    update_post_meta($post, 'ical_only', 'true');
	} else
	{
	    delete_post_meta($post, 'ical_only');
	}
}