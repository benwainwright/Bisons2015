<?php
update_post_meta($post, 'fee-name', esc_attr ( $_POST['fee-name']) );
update_post_meta($post, 'fee-amount', esc_attr ( $_POST['fee-amount']) );
update_post_meta($post, 'fee-type', esc_attr ( $_POST['fee-type']) );
update_post_meta($post, 'fee-order', esc_attr ( $_POST['fee-order']) );
update_post_meta($post, 'fee-description', esc_attr ( $_POST['fee-description']) );
update_post_meta($post, 'initial-payment', esc_attr ( $_POST['initial-payment']) );
update_post_meta($post, 'supporter-player', esc_attr ( $_POST['supporter-player']) );

if ( isset ( $_POST['requires-approval'] ) )
{
     if ($_POST['requires-approval'] == 'true') update_post_meta($post, 'requires-approval', 'true');
     else delete_post_meta($post, 'requires-approval');
}

if ( isset ( $_POST['fees-tables'] ) )
{
     if ($_POST['fees-tables'] == 'true') update_post_meta($post, 'fees-tables', 'true');
     else delete_post_meta($post, 'fees-tables');
}