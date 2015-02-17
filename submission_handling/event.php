<?php
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])){exit();}

update_post_meta($post, 'date', strtotime( $_POST['date'] ));
update_post_meta($post, 'enddate', strtotime( $_POST['enddate']));
update_post_meta($post, 'time', esc_attr( $_POST['time'] ));
update_post_meta($post, 'endtime', esc_attr( $_POST['endtime'] ));
update_post_meta($post, 'address', esc_attr( $_POST['address'] ));
update_post_meta($post, 'facebook-event', esc_attr( $_POST['facebook-event'] ));
update_post_meta($post, 'description', wpautop($_POST['description']));
update_post_meta($post, 'image_id', $_POST['upload_image_id']);

if ($_POST['hide_from_blog'] == 'true')
{
    update_post_meta($post, 'hide_from_blog', 'true');
} else 
{
    delete_post_meta($post, 'hide_from_blog');
}


if ($_POST['whitebackground'] == 'true')
{
    update_post_meta($post, 'whitebackground', 'true');
} else 
{
    delete_post_meta($post, 'whitebackground');
}

if ($_POST['allDay'] == 'true')
{
    update_post_meta($post, 'allDay', 'true');
} else 
{
    delete_post_meta($post, 'allDay');
}

if ($_POST['ical_only'] == 'true')
{
    update_post_meta($post, 'ical_only', 'true');
} else
{
    delete_post_meta($post, 'ical_only');
}