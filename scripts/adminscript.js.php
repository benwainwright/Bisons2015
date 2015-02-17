<?php header('content-type: text/javascript'); ?>
var count = 0;
function embed_map(input, canvas) {
    var the_address = '';
    the_address = jQuery(input).val();
 
    if(the_address != '') {
      
        jQuery(canvas).show();
        jQuery(canvas).find('td').gmap3('destroy');
        jQuery(canvas).find('td').gmap3({
            map:{
                address : the_address,
                options: {
                    zoom: 12
                }
    
            }
        });

        jQuery(canvas).show();
    } else {
        jQuery(canvas).hide()
    }
}



jQuery(document).ready(function() {
    
    
    
    var input = '.address-input';
    var canvas = '.map-row';

    embed_map('.address-input', '.map-row');
   
    jQuery('.address-input').focusout(function(){ embed_map(input, canvas); });
  
    
    
    window.send_to_editor_default = window.send_to_editor;
    window.attach_image = function(html) {
        jQuery('body').append('<div id="temp_image">' + html + '</div>');

        var img  = jQuery('#temp_image').find('img');

        imgurl   = img.attr('src');
        imgclass = img.attr('class');
        imgid    = parseInt(imgclass.replace(/[^0-9]*/g, ''), 10)
        jQuery('#upload_image_id').val(imgid);
        jQuery('#remove-event-image').show();
        
        jQuery('img#image_canvas').attr('src', imgurl);
        try{tb_remove(); } catch(e) {};

        jQuery('#temp_image').remove();
        jQuery('.image_canvas_description').text('');
        window.send_to_editor = window.send_to_editor_default;

    };
    
    jQuery('.custom-image-upload-button').click(function() {
        window.send_to_editor = window.attach_image;
        tb_show('', 'media-upload.php?post_id=<?php echo $_GET['post'] ?>&amp;type=image&amp;TB_iframe=true');
    });

    jQuery('.custom-image-remove-button').click(function() {
        jQuery('#upload_image_id').val('');
        jQuery('img#image_canvas').attr('src', '<?php echo $_GET['templateurl'].'/images/default-avatar.jpg' ?>');
        jQuery(this).hide();
    });


 
    jQuery( '#match-report-button').click(function(){
    var post_id = jQuery('#postid').val();
    window.location.replace('post-new.php?post_type=report&parent_post=' + post_id);
    });

    jQuery( '#match-result-button').click(function(){
    var post_id = jQuery('#postid').val();
    window.location.replace('post-new.php?post_type=result&parent_post=' + post_id);
    });

    jQuery('.custom-TinyMCE').addClass("mceEditor");
    if( typeof( tinyMCE ) == "object" &&
        typeof( tinyMCE.execCommand ) == "function" ) {
            tinyMCE.execCommand("mceAddControl", false, 'custom-TinyMCE')
        }

});