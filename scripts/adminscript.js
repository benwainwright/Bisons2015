jQuery(document).ready(function() {
    window.send_to_editor_default = window.send_to_editor;
    jQuery('.custom-image-upload-button').click(function() {
        window.send_to_editor = window.attach_image;
        tb_show('', '')
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