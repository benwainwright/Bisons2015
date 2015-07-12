jQuery(document).ready(function(){
   jQuery('span.delete a').click(function(){
       return confirm('Are you sure you want to delete this item?');
   });
});