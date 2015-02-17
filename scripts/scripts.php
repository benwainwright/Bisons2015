
jQuery(document).ready(function() {
    
    var aboveHeight = jQuery('#mainheader').outerHeight();
    
    
    // Stickybar
    jQuery(window).scroll(function(){
        
        if(jQuery(window).scrollTop() > aboveHeight && jQuery( window ).width() > 580) {
            jQuery('#menu').addClass('stickybar').css('top','0').next().css('padding-top','1em');
        } else {
            jQuery('#menu').removeClass('stickybar').next().css('padding-top','0');
        }
    });
    
    jQuery(window).resize(function() {
        width = parseInt(jQuery(this).width());
        if (width > 580)
        {
            jQuery('#menu').show();
        } else
        {
            jQuery('#menu').hide();
        }
        
    });

     
    jQuery('.image-link').magnificPopup({
        type:'image',
        gallery:{enabled:true}
        });
      
    /*
    jQuery('.gmap-address').each(function() {
        var address = jQuery(this).text();
        var classlist = jQuery(this).attr('class').split(/\s+/)
        jQuery.each(classlist, function(index, item) {
            if(item != 'gmap-address' && address != 'TBC') {
                jQuery('#' + item).show();
                jQuery('#' + item).parent().show();
                jQuery('#' + item).gmap3({
                    marker: { address : address },
                    map:{
                        options: {
                            zoom: 14
                        }
                    }
                });
            }
        });
    });
    */
      
    jQuery('#showmenu').click(function() {
        jQuery('#menu').toggle("fast");
    });
    
});