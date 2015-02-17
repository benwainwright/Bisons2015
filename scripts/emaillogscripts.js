jQuery(document).ready(function() {
    jQuery('a.showlink').click(function() {
        jQuery(this).siblings('div').toggle();
        jQuery(this).text( jQuery(this).text() == "Show" ? "Hide" : "Show" );
    });
});