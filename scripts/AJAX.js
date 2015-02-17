

// Menubar clicks redraw page with AJAX
jQuery('#menu li a').click(function(){
    if ( jQuery(this).attr('class') != 'loginout')
    {
          
          var nua = navigator.userAgent;
          var is_android_stock_browser = ((nua.indexOf('Mozilla/5.0') > -1 && nua.indexOf('Android ') > -1 && nua.indexOf('AppleWebKit') > -1) && !(nua.indexOf('Chrome') > -1));
          
          if( jQuery( window ).width() < 581 ) jQuery('#menu').hide();
          var url = jQuery(this).attr('href');
          jQuery('.current-menu-item').removeClass('current-menu-item');
          jQuery(this).parent().addClass('current-menu-item');
          jQuery('#wrapper').animate({ opacity: 0 }, 400, function(){
              jQuery('.loadinglogo').remove();
              if ( ! is_android_stock_browser ) jQuery('#menu').after('<img class="loadinglogo" src="/wp-content/themes/bisonsv4/images/gif-load.gif">');

              jQuery('#wrapper').load(url + ' .ajaxcol', function(responseText) {
                  var newTitle = responseText.match("<title>(.*?)</title>")[1];
                  var stateObject = { title: newTitle }
                  history.pushState(stateObject, newTitle, url);
                  jQuery('title').html(newTitle) 
                  loadgmaps();
                  if ( ! is_android_stock_browser ) jQuery('.loadinglogo').remove();
                  jQuery('#wrapper').animate({ opacity: 1 });
                  jQuery("html, body").animate({ scrollTop: 0 }, "fast");
              });
          });
          return false;
    }
});