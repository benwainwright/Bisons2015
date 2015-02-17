<div class="wrap">
<?php $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT); if ($user_id) : ?>
<h2>Emails sent to <?php echo get_userdata($user_id)->first_name.' '.get_userdata($user_id)->last_name ?> (All Email)</h2>
<?php else : ?>
<h2>Email Log</h2>    
<?php endif;
$emailTable = new Email_Log_Tables(); 
$emailTable->prepare_items();
$emailTable->views();
$emailTable->display(); 
?>
</div>