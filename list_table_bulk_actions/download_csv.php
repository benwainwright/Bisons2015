<?php
if ( isset ( $_POST['user_id'] ) )
{
    $_POST['confirm_action'] = 'true';
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="membership_data.csv"');

    
    $table = array();
    
    
    $count = 0;
    $i = 0;
    foreach ( $_POST['user_id'] as $user )
    {


	    $user = get_userdata( $_POST['user_id'] );

        while ($user_form->have_posts()) 
        {
            $table['Player/Supporter'][$i] = get_user_meta($_POST['user_id'], 'joiningas', true);
            $table['Name'][$i] =  $user->user_firstname . ' ' . $user->user_lastname;
            $table['Gender'][$i] = get_user_meta($_POST['user_id'], 'gender', true);
            $table['Other Gender Details'][$i] = get_user_meta($_POST['user_id'], 'othergender', true);
            $table['Contact Number'][$i] = get_user_meta($_POST['user_id'], 'contact_number', true);
            $table['Email Address'][$i] = $user->user_email;
            $table['Street Address 1'][$i] = get_user_meta($_POST['user_id'], 'streetaddyl1', true);
            $table['Street Address 2'][$i] = get_user_meta($_POST['user_id'], 'streetaddyl2', true);
            $table['Town'][$i] = get_user_meta($_POST['user_id'], 'streetaddytown', true);
            $table['Postcode'][$i] = get_user_meta($_POST['user_id'], 'postcode', true);
            
            $table['Medical Conditions or Disabilities'][$i] = get_user_meta($_POST['user_id'], 'medconsdisabyesno', true);
            if ( $table['Medical Conditions or Disabilities'][$i] == 'Yes')
            {
                for ( $ii = 1; get_user_meta($_POST['user_id'], 'condsdisablities_name_row' . $ii, true); $ii++ )
                {
                    $table["Condition ($ii)"][$i] = get_user_meta($_POST['user_id'], 'condsdisablities_name_row' . $ii, true);
                    $table["Medication ($ii)"][$i] = get_user_meta($_POST['user_id'], 'condsdisablities_drugname_row' . $ii, true);
                    $table["Medication Dose/Freq ($ii)"][$i] = get_user_meta($_POST['user_id'], 'condsdisablities_drugdose_freq_row' . $ii, true);
                }
            }
            $table['Allergies'][$i] = get_user_meta($_POST['user_id'], 'allergiesyesno', true);
            if( $table['Allergies'][$i] == 'Yes')
            {
                for ( $ii = 1; get_user_meta($_POST['user_id'], 'allergies_name_row' . $ii, true); $ii++ )
                {
                    $table["Allergy ($ii)"][$i] = get_user_meta($_POST['user_id'], 'allergies_name_row' . $ii, true);
                    $table["Allergy Medication ($ii)"][$i] = get_user_meta($_POST['user_id'], 'allergies_drugname_row' . $ii, true);
                    $table["Allergy Medication Dose/Freq ($ii)"][$i] = get_user_meta($_POST['user_id'], 'allergies_drugdose_freq_row' . $ii, true);
                }
            }
            
            $table['Injuries'][$i] = get_user_meta($_POST['user_id'], 'injuredyesno', true);
            if ( $table['Injuries'][$i] == 'Yes')
            {
                for ( $ii = 1; get_user_meta($_POST['user_id'], 'injuries_name_row' . $ii, true); $ii++ )
                {
                    $table["Injury ($ii)"][$i] = get_user_meta($_POST['user_id'], 'injuries_name_row' . $ii, true);
                    $table["When Injured ($ii)"][$i] = get_user_meta($_POST['user_id'], 'injuries_when_row' . $ii, true);
                    $table["Treatment Received ($ii)"][$i] = get_user_meta($_POST['user_id'], 'injuries_treatmentreceived_row' . $ii, true);
                    $table["Who Treated ($ii)"][$i] = get_user_meta($_POST['user_id'], 'injuries_who_row' . $ii, true);
                    $table["Injury Status ($ii)"][$i] = get_user_meta($_POST['user_id'], 'injuries_status_row' . $ii, true);
                }
            }

        }
        $i++;
 
    } 
    echo implode (',', array_keys($table))."\n";
    for ( $ii = 0; $ii < $i; $ii++ )
    {
        $this_column = array();
        foreach ($table as $column)
        {
            if ( strstr($column[$ii], ',') 
            || strstr($column[$ii], "\n") 
            || strstr($column[$ii], "\r") 
            || strstr($column[$ii], "\""))
            {
                $value = str_replace('"', '""', $column[$ii]);
                $this_column[] = "\"$value\"";
            }
            else
            {
                $this_column[] = $column[$ii];
            }
        }
        
        echo implode (',', $this_column);
        echo "\n";
    } 
    
    // Stop the rest of Wordpress from displaying\
    
    exit();
}
else 
{
    function download_csv_error_notice() {
        echo '<div class="error">';
        echo '<p>You didn\'t select anyone</p>';
        echo '</div>';
    }
    add_action('admin_notices', 'download_csv_error_notice');
}