<?php header('content-type: text/javascript'); ?>

jQuery(document).ready(function() {
    window.send_to_editor_default = window.send_to_editor;

    window.attach_image = function(html) {
        jQuery('body').append('<div id="temp_image">' + html + '</div>');

        var img  = jQuery('#temp_image').find('img');

        imgurl   = img.attr('src');
        imgclass = img.attr('class');
        imgid    = parseInt(imgclass.replace(/[^0-9]*/g, ''), 10)
        jQuery('#upload_image_id').val(imgid);
        jQuery('#remove-event-image').show();
        jQuery('img#event_image').attr('src', imgurl);
        try{tb_remove(); } catch(e) {};

        jQuery('#temp_image').remove();
        jQuery('.event_image_description').text('');
        window.send_to_editor = window.send_to_editor_default;

    }
    jQuery('.custom-image-upload-button').click(function() {
        window.send_to_editor = window.attach_image;
        tb_show('', 'media-upload.php?post_id=<?php echo $_GET['post'] ?>&amp;type=image&amp;TB_iframe=true');
    });

    jQuery('.custom-image-remove-button').click(function() {
        jQuery('#upload_image_id').val('');
        jQuery('img#event_image').attr('src', '');
        jQuery('.event_image_description').text('No image');
        jQuery(this).hide();
    });


if( jQuery('.address-input').val() != '') {
the_address = jQuery('.address-input').val();
if(the_address != '') {
jQuery('.embed-map').show().gmap3({
map:{
address : the_address,
options: {
zoom: 12
}

}
})
}
}
jQuery( '#match-report-button').click(function(){
var post_id = jQuery('#postid').val();
window.location.replace('post-new.php?post_type=report&parent_post=' + post_id);
});

jQuery( '#match-result-button').click(function(){
var post_id = jQuery('#postid').val();
window.location.replace('post-new.php?post_type=result&parent_post=' + post_id);
});


jQuery('.address-input').focusout(function() {
the_address = jQuery('.address-input').val();
if(jQuery('.address-input').val() != '') {
jQuery('.embed-map').gmap3('destroy');
jQuery('.embed-map').show()

jQuery('.embed-map').show().gmap3({
marker: { address : the_address },
map:{
address : the_address,
options: {
zoom: 14
}

}
});

} else {
jQuery('.embed-map').hide()
}

});
});