jQuery(document).ready(function() {
	jQuery('form').validate({
	    onfocusout: function(element) {
	        jQuery(element).valid();
	    },
	    groups: {
	        DateofBirth: "dob-day dob-month dob-year"
	        },
	   errorPlacement: function(error, element) {
	       if (element.attr("name") == "dob-day" || element.attr("name") == "dob-month" || element.attr("name") == "dob-year") 
	        error.insertAfter("#dob-year");
	        
	       else if (element.hasClass('tableInputs') );
			// Nothing - remove error label
	       else 
	        error.insertAfter(element);
	        
	       
   		}	
   });
	
	jQuery.validator.addClassRules("min2chars", { minlength:2 });
	jQuery.validator.addClassRules("postcode", { postalCode:true });


	jQuery("input[type='tel']").each(function() {
    	jQuery(this).rules("add", { phoneUK:true });
	});
});
