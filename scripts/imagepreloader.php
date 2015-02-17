<?php header('Content-type: text/javascript') ?>
jQuery('#wrapper img').each(function() {
    jQuery(this).attr('the-url', jQuery(this).attr('src'));
    jQuery(this).attr('src', '<?php echo $_GET['templatelocation'] ?>/images/preloader.GIF');
});


jQuery('#wrapper img').load(function() {
    jQuery(this).attr('src', jQuery(this).attr('the-url'));
    jQuery(this).removeAttr('the-url');
});
