jQuery(document).ready(function() {
    jQuery('#filter').change(function() {
        switch( jQuery(this).val() )
        {
            case 'Personal details': 
                jQuery('td.namecol, th.namecol').hide();
                jQuery('td.noform.namecol').show();
                jQuery('td.personaldetails, th.personaldetails').show();
                jQuery('td.medicalfitness, th.medicalfitness').hide();
                jQuery('td.admin, th.admin').hide();


            break;
            
            case 'Fitness and medical':
                jQuery('td.namecol, th.namecol').show();
                jQuery('td.personaldetails, th.personaldetails').hide();
                jQuery('td.medicalfitness, th.medicalfitness').show();
                jQuery('td.admin, th.admin').hide();
            break;
            
            case 'Administration':
                jQuery('td.namecol, th.namecol').show();
                jQuery('td.personaldetails, th.personaldetails').hide();
                jQuery('td.medicalfitness, th.medicalfitness').hide();
                jQuery('td.admin, th.admin').show();
            break;
            
            case 'All':
                jQuery('td.namecol, th.namecol').hide();
                jQuery('td.personaldetails, th.personaldetails').show();
                jQuery('td.medicalfitness, th.medicalfitness').show();
                jQuery('td.admin, th.admin').show();
                jQuery('td.noform.namecol').show();

            break;
            
        }  
    });
});

