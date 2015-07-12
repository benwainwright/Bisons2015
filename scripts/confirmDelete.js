jQuery(document).ready(function(){
   jQuery('span.delete a').click(function(){
       return confirm('Are you sure you want to delete this item?');
   });


    jQuery('a.markInactive').click(function(){
        return confirm('Marking this user inactive will not permenantly delete them from the Wordpress database, but will mean that they cannot be selected from many of the administration screens. Are you sure you want to do this?');
    });
});