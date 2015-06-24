
function loadgmaps()
{
    jQuery('.gmap-address').each(function() {
        
        var address = jQuery(this).text();
        var addressHtml = jQuery (this).html();
        var classlist = jQuery(this).attr('class').split(/\s+/);
        if ( ! jQuery(this).parent().hasClass('page') && ! jQuery(this).parent().prop('tagName') == 'td' ) jQuery(this).parent().hide(); 
        jQuery.each(classlist, function(index, item) {
              
            if(item != 'gmap-address' && address != 'TBC') {
                  
                jQuery('#' + item).show();
                jQuery('#' + item).parent().show();
                jQuery('#' + item).gmap3({
                      
 
                  marker:{
                        address : address,
                	},
                      
                   map:{
                        options: {
                            draggable: false,
                            zoom: 14,
                            keyboardShortcuts:false,
                            scrollwheel: false,
                            zoomControl: false,
                            streetViewControl:false
                        }
                    }
                });
            }
        });
    });
}

jQuery.fn.redraw = function() {
    return this.hide(0, function(){jQuery(this).show()});
};

jQuery(document).ready(function() {

    jQuery('body').redraw();

    var aboveHeight = jQuery('#mainheader').outerHeight();
    
    loadgmaps();
    
    // Stickybar
    jQuery(window).scroll(function(){
        
        if(jQuery(window).scrollTop() > aboveHeight && jQuery( window ).width() > 580) {
            jQuery('#menu').addClass('stickybar').css('top','0').next().css('padding-top','1em');
        } else {
            jQuery('#menu').removeClass('stickybar').next().css('padding-top','0');
        }
    });
    
    if ( parseInt( jQuery(window).width() ) <= 580)
    {
        jQuery('.postAuthor').each( function() {
            jQuery(this).data('fullName', jQuery(this).text())
            var names = jQuery(this).data('fullName').split(" ");
            jQuery(this).text(names[0]);
        });
    }

    
    jQuery(window).resize(function() {
        width = parseInt(jQuery(this).width());
        if (width > 580)
        {
            jQuery('#menu').show();
            jQuery('.postAuthor').each( function() {
                jQuery(this).text(jQuery(this).data('fullName'));
            });
        } else
        {
            jQuery('.postAuthor').each( function() {
                
                if (!jQuery('.postAuthor').data('fullName'))
                {
                    jQuery(this).data('fullName', jQuery(this).text());
                }
                var names = jQuery(this).data('fullName').split(" ");
                jQuery(this).text(names[0]);
            });
            
            jQuery('#menu').hide();
        }
        
    });

     
    jQuery('.image-link').magnificPopup({
        type:'image',
        gallery:{enabled:true}
        });


    jQuery('#showmenu').click(function() {
        jQuery('#menu').toggle("fast");
    });
    
});