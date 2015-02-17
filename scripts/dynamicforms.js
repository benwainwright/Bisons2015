jQuery(document).ready(function() {
    
    jQuery('#committeeSelectPlayer').change(function() {
        
            var pathArray = window.location.pathname.split('?');
            var baseUrl = pathArray[0];
            if ( jQuery(this).val() == 'me' )
            {
                window.location = baseUrl;
            }
            else
            {
                window.location = baseUrl + '?player_id=' + jQuery(this).val();
            }
    });
    
    jQuery('#allDay').click(function() {
        
      if( jQuery(this).prop('checked') )
      { 
          jQuery('#time').parent().parent().hide( 400 ); 
          jQuery('#time').val('');
          validator.remove_error ( jQuery ('#time') );
          jQuery('#endtime').parent().parent().hide( 400 );  
          jQuery('#endtime').val('');
          validator.remove_error ( jQuery ('#endtime') );
      } 
      else
      { 
          jQuery('#time').parent().parent().show( "blind" ); 
          jQuery('#endtime').parent().parent().show( "blind" );
      }
    });
    
    
    jQuery('input, select, textarea').focus(function() {
        jQuery(this).siblings('.forminfo').show();
        jQuery(this).addClass('focusedinput');
        jQuery(this).siblings('label').addClass('focusedinput');
    });
    
    jQuery('input, select, textarea').focusout(function() {
        jQuery(this).siblings('.forminfo').hide();
        jQuery(this).removeClass('focusedinput');
        jQuery(this).siblings('label').removeClass('focusedinput');
    });

    jQuery('#joiningas').change(function() {
        if ( jQuery(this).val() == 'Supporter' )
        { 
              jQuery('.playersonly').hide(); 
              jQuery('.supportersonly').show();
              jQuery('#playermempaymonthly').find('select').val( '' );
              jQuery('#playermempaysingle').find('select').val( '' );
        }
        else if ( jQuery(this).val() == 'Player' )
        {	
              jQuery('.playersonly').show(); 
              jQuery('.supportersonly').hide(); 
              jQuery('#supportermempaymonthly').find('select').val( '' );
              jQuery('#supportermempaysingle').find('select').val( '' );
        }
    });
        
    jQuery('#already').change(function() {
        if( jQuery(this).val() == 'Yes' )
        { jQuery('#inputcarddetails').hide(); } 
        else
        { jQuery('#inputcarddetails').show(); }
    });
    
    jQuery('#medconsdisabyesno').change(function() {
        if( jQuery(this).val() == 'Yes' )
        { jQuery('#conddisablefieldset').show(); } 
        else
        { jQuery('#conddisablefieldset').hide(); }
    });
    
    jQuery('#sameaddress').change(function() {
        if( jQuery(this).val() == 'No' )
        { jQuery('#nokaddygroup').show(); } 
        else
        { jQuery('#nokaddygroup').hide(); }
    });
    
    jQuery('#allergiesyesno').change(function() {
        if( jQuery(this).val() == 'Yes' )
        { jQuery('#allergiesfieldset').show(); } 
        else
        { jQuery('#allergiesfieldset').hide(); }
    });

    jQuery('#gender').change(function() {
        if( jQuery(this).val() == 'Other' )
        { jQuery('#othergender').show(); } 
        else
        { jQuery('#othergender').hide(); }
    });
    
    jQuery('#smoking').click(function() {
        
      if( jQuery(this).prop('checked') )
      { jQuery('#howmanycigs').show(); } 
        else
      { jQuery('#howmanycigs').hide(); }
    });
    
    jQuery('#paymethod').change(function() {
        if( jQuery(this).val() == 'Monthly Direct Debit' )
        { 
              jQuery('#playermempaymonthly').show(); 
              jQuery('#playermempaysingle').hide();
              jQuery('#playermempaysingle').find('select').val( '' );
              jQuery('#supportermempaymonthly').show(); 
              jQuery('#supportermempaysingle').hide(); 
              jQuery('#supportermempaysingle').find('select').val( '' );

        }
        else if ( jQuery(this).val() == 'Single Payment' )
        { 
              jQuery('#playermempaymonthly').hide(); 
              jQuery('#playermempaymonthly').find('select').val( '' );
              jQuery('#playermempaysingle').show();
              jQuery('#supportermempaymonthly').hide(); 
              jQuery('#supportermempaymonthly').find('select').val( '' );
              jQuery('#supportermempaysingle').show(); 
        }
        else 
        { 
              jQuery('#playermempaymonthly').hide(); 
              jQuery('#playermempaymonthly').find('select').val( '' );
              jQuery('#playermempaysingle').hide();
              jQuery('#playermempaysingle').find('select').val( '' );
              jQuery('#supportermempaymonthly').hide(); 
              jQuery('#supportermempaymonthly').find('select').val( '' );
              jQuery('#supportermempaysingle').hide();  
              jQuery('#supportermempaysingle').find('select').val( '' );

        }

    }); 

    
    jQuery('#injuredyesno').change(function() {
        if( jQuery(this).val() == 'Yes' )
        { jQuery('#injuriesfieldset').show(); } 
        else
        { jQuery('#injuriesfieldset').hide(); }
    });
    
    jQuery('#playedbefore').change(function() {
        if( jQuery(this).val() == 'Yes' )
        { jQuery('#howmanyseasonsgroup').show(); } 
        else
        { jQuery('#howmanyseasonsgroup').hide(); }
    });
    

    
    jQuery('#conddisablefieldset').find('.addrow').click(function() {
        
        var rownum = jQuery('#conddisablefieldset').find('tbody').find('tr').length + 1;
        
        var row = '<tr><td><input name="condsdisablities_name_row' + rownum + '" type=\'text\' /></td><td>'
                  +'<input name="condsdisablities_drugname_row' + rownum + '" type=\'text\' /></td><td> '
                  +'<input name="condsdisablities_drugdose_freq_row' + rownum + '" type=\'text\' /></td>'
                  +'</tr>';
        jQuery('#conddisablefieldset').find('tbody').append( row );
        
        if( jQuery('#conddisablefieldset').find('tbody').find('tr').length > 1 )
        {
            jQuery('#conddisablefieldset').find('.removerow').show();  
        }     
        
        return false; 
    });
    
    jQuery('#conddisablefieldset').find('.removerow').click(function() {
        
        
        if( jQuery('#conddisablefieldset').find('tbody').find('tr').length > 2 )
        {
            jQuery('#conddisablefieldset').find('tbody tr:last-child').remove();
        } 
        else if ( jQuery('#conddisablefieldset').find('tbody').find('tr').length == 2 )

        {
            jQuery('#conddisablefieldset').find('tbody tr:last-child').remove();
            jQuery(this).hide();     
        }
        return false; 
    });
    
    jQuery('#allergiesfieldset').find('.addrow').click(function() {
        
        var rownum = jQuery('#allergiesfieldset').find('tbody').find('tr').length + 1;
        
        var row = '<tr><td><input name="allergies_name_row' + rownum + '" type=\'text\' /></td><td>'
                  +'<input name="allergies_drugname_row' + rownum + '" type=\'text\' /></td><td> '
                  +'<input name="allergies_drugdose_freq_row' + rownum + '" type=\'text\' /></td>'
                  +'</tr>';
        jQuery('#allergiesfieldset').find('tbody').append( row );
        
        if( jQuery('#allergiesfieldset').find('tbody').find('tr').length > 1 )
        {
            jQuery('#allergiesfieldset').find('.removerow').show();  
        }    
        return false; 
         
    });
    
    jQuery('#allergiesfieldset').find('.removerow').click(function() {
        
        
        if( jQuery('#allergiesfieldset').find('tbody').find('tr').length > 2 )
        {
            jQuery('#allergiesfieldset').find('tbody tr:last-child').remove();
        } 
        else if ( jQuery('#allergiesfieldset').find('tbody').find('tr').length == 2 )

        {
            jQuery('#allergiesfieldset').find('tbody tr:last-child').remove();
            jQuery(this).hide();     
        }
        return false; 
    });
    
    jQuery('#injuriesfieldset').find('.addrow').click(function() {
        
        var rownum = jQuery('#injuriesfieldset').find('tbody').find('tr').length + 1;
        
        var row = '<tr><td><input name="injuries_name_row' + rownum + '" type=\'text\' /></td><td>'
                  +'<input name="injuries_when_row' + rownum + '" type=\'text\' /></td><td> '
                  +'<input name="injuries_treatmentreceived_row' + rownum + '" type=\'text\' /></td><td>'
                  +'<input name="injuries_who_row' + rownum + '" type=\'text\' /></td><td>'
                  +'<input name="injuries_status_row' + rownum + '" type=\'text\' /></td>';
                  +'</tr>';
        jQuery('#injuriesfieldset').find('tbody').append( row );
        
        if( jQuery('#injuriesfieldset').find('tbody').find('tr').length > 1 )
        {
            jQuery('#injuriesfieldset').find('.removerow').show();  
        } 
        return false;     
    });
    
    jQuery('#injuriesfieldset').find('.removerow').click(function() {
        
        
        if( jQuery('#injuriesfieldset').find('tbody').find('tr').length > 2 )
        {
            jQuery('#injuriesfieldset').find('tbody tr:last-child').remove();
        } 
        else if ( jQuery('#injuriesfieldset').find('tbody').find('tr').length == 2 )

        {
            jQuery('#injuriesfieldset').find('tbody tr:last-child').remove();
            jQuery(this).hide();     
        }
        return false; 
    });
    
    
    jQuery('#newpaymentbutton').click(function() {
        jQuery('#newpayment').val( 'true');
        jQuery('#membershipform_payment').submit();
    });
       
});