<?php header('content-type: text/javascript'); ?>
jQuery(document).ready(function() 
{
    setTimeout(function(){ document.location = '<?php echo $_GET['url'] ?>'; }, <?php echo $_GET['time'] ?>);
});