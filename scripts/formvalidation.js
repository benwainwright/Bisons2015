
// Declare validator c;ass 
function FormValidator( form, validation_patterns )
{
    // So that callbacks can access parent class
    var parent = this;

    // Default validation regexes. These can be passed in however
    this.validation_patterns = typeof validation_patterns === 'undefined' ? [

        {
            name  : 'mustselect',
            regex : /^.+$/,
            error : 'You must make a selection'
            
        },
        
        {
            name  : 'notempty',
            regex : /^(?=\s*\S).*$/m,
            error : 'Field cannot be left empty'
        },
        
        {
            name  : 'needemail',
            regex : /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
            error : 'Not a valid email address'
        },
        
        {
            name  : 'needphonenum',
            regex : /^\+?[0-9\s\(\)]+$/,
            error : 'Not a valid phone number'
        },
        
        {
            name  : 'needpostcode',
            regex : /^[A-Za-z]{1,2}[0-9]{1,2}\s?[0-9]{1}[a-zA-Z]{2}$/,
            error : 'Not a valid UK postcode'
        }
        
    ] : validation_patterns;
        
    // If a form isn't passed in, use every FORM element on the page
    this.form = typeof form === 'undefined' ? jQuery('form') : form;
    this.submitText = this.form.find('input[type=submit]').attr('value') 
            ? this.form.find('input[type=submit]').attr('value') : this.form.find('button[type=submit]').text();

    this.fields = this.form.find('input, textarea, select');
    

    this.validationErrors = new Array();
    
    this.getIndex = function ( object )
    {
    	parent.fields.each ( function ( index )
		{
    		if ( jQuery(this) == object )
    			return index;
		});
    };
    
    this.remove_error = function ( object )
    {
        delete parent.validationErrors[ this.getIndex( object ) ];
        object.siblings('.formerror').remove();
    };
   
    
    this.validate_fields = function ( )
    {
        this.fields.each(function( index ) {
            element = jQuery(this);
            if ( element.attr('class') )
            {
                classList = element.attr('class').split(/\s+/);
                classList.forEach(function( theClass )
                {
                    parent.validate_field( element, index );    
                }); 
            }
        });
    };
    
    this.validate_field = function( object, formIndex ) 
    {
        if (object.parents(':hidden').length > 0)
        {
            delete parent.validationErrors[formIndex];
            object.siblings('.formerror').remove();
            return;
        }
        
        if ( object.attr('class') )
        {
            classList = object.attr('class').split(/\s+/);
            classList.forEach(function( theClass )
            {
                
                value = object.val();
                parent.validation_patterns.forEach( function(pattern_array, index, array) {
                    
                    if ( pattern_array.name == theClass )
                    {
                        if ( pattern_array.regex.test ( value ) )
                        {
                            delete parent.validationErrors[formIndex];
                            object.siblings('.formerror').remove();
                        }
                        else
                        {   
                            object.siblings('.formerror').remove();
                            parent.validationErrors[formIndex] = pattern_array.error;
                            jQuery('<p class="formerror">' + pattern_array.error + '</p>').insertAfter(object);
                        }
                    }
                }); 
            });  
        }
    };


    this.recount_errors = function( )
    {
        
        var count = 0;
        for ( var i = 0; i < this.validationErrors.length; i++)
        {
            if (this.validationErrors[i])
            {
                count++;
            }
        }
        
                
        if (count > 0) 
        {
            this.form.find('input[type=submit]').attr('disabled', 'disabled');
            this.form.find('button[type=submit]').attr('disabled', 'disabled');
            this.form.find('input[type=submit]').attr('value', this.submitText + ' (Disabled - check for errors)');
            this.form.find('button[type=submit]').text(this.submitText + ' (Disabled - check for errors)');
        }

        // If not, re-enable form
        else 
        {
            this.form.find('input[type=submit]').removeAttr('disabled');
            this.form.find('button[type=submit]').removeAttr('disabled');
            this.form.find('button[type=submit]').text(this.submitText);
            this.form.find('input[type=submit]').attr('value', this.submitText);
        }
        return count;
    };
    
    this.fieldsize = function()
    {
        var count = 0;
        this.fields.forEach(function()
        {
            count++;
        });  
        return count; 
    };
    
    
    
    this.fields.each ( function( arrayIndex ) {
        switch ( jQuery(this).prop('tagName').toLowerCase() )
        { 
            case "input": case "textarea":
                jQuery(this).focusout(function() {
                    parent.validate_field( jQuery(this), arrayIndex);
                    parent.recount_errors(); 
                });
                
                jQuery(this).keyup(function(event) {
                    var code = event.keyCode || event.which;
                    if (code != '9')
                    {
                        parent.validate_field( jQuery(this), arrayIndex);
                        parent.recount_errors();
                    }
                });
                
            break;
            
            case "select": 
                jQuery(this).focusout(function() {
                    parent.validate_field( jQuery(this), arrayIndex);
                    parent.recount_errors();
                });
                
                jQuery(this).change(function() {
                    parent.validate_field( jQuery(this), arrayIndex);
                    parent.recount_errors();
                });
            break;
        }  
    });
    
    
    this.validationErrors.firstErrorIndex = function()
    {
        var count = 0;
        for ( var i = 0; i < parent.validationErrors.length; i++)
        {
            if (parent.validationErrors[i])
            {
                console.log(i);
                return i;
            }
        }
        return false;
    };
    
    this.form.submit(function(e) 
    {
        parent.validate_fields();

        if ( parent.recount_errors() > 0 )
        {  
            e.preventDefault();
        }
    });
}

var validator = new FormValidator();