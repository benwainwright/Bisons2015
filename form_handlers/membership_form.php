<?php
if (!INCLUDED) exit;

// Don't post membership form if the reason we are submitting is because we are entering edit modes
if ( ! isset ( $_POST['edit_details'] ) )
{


	$form_user = ( isset ( $_POST['form_belongs_to'] ) && current_user_can ('committee_perms') )
		? $_POST['form_belongs_to'] : get_current_user_id();

        
      // No GCL sub so create one
      if ( ! get_user_meta($form_user, 'gcl_sub_id') )
      {
          
          $user = array(
            'first_name'            => $_POST['firstname'],
            'last_name'             => $_POST['surname'],
            'email'                 => $_POST['email_addy'],
            'billing_address1'      => $_POST['streetaddyl1'],
            'billing_address2'      => $_POST['streetaddyl2'],
            'billing_town'          => $_POST['streetaddytown'],
            'billing_postcode'      => $_POST['postcode'],
          );
            
         
          $return_addy = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
          switch (  $_POST['paymethod']  ) 
          {


               case "Monthly Direct Debit": 
                    $feeid = ( $_POST['playermembershiptypemonthly'] != '' ) 
                        ? $_POST['playermembershiptypemonthly'] 
                        : $_POST['supportermembershiptypemonthly'];
                  
                  $amount = get_post_meta( $feeid, 'fee-amount', true );
                  $amount_in_pounds = pence_to_pounds ( $amount, false );               
                  $setup_fee = pence_to_pounds ( get_post_meta( $feeid, 'initial-payment', true ), false );
                  $subscription_details = array(
                        'amount'           => $amount_in_pounds,
                        'name'             => get_post_meta( $feeid, 'fee-name', true ),
                        'interval_length'  => 1,
                        'interval_unit'    => 'month',
                        'currency'         => 'GBP',
                        'user'             => $user,
                        'state'            => "DD",
                    ); 
                   
                   if ( $description = get_post_meta( $feeid, 'fee-description', true ) ) 
                        $subscription_details['description'] = $description;
                        
                   if ( $setup_fee > 0 ) 
                   {
                       $subscription_details['setup_fee'] = $setup_fee;
                       $subscription_details['description'] .= ' Note that your first payment will be debited as a separate payment on the same date as the one off fee' ; 
                   }
                   $gocardless_url = GoCardless::new_subscription_url($subscription_details);

                   
                  break;
               
               case "Single Payment":
                $feeid = ( $_POST['playermembershiptypesingle'] != '' ) 
                            ? $_POST['playermembershiptypesingle'] 
                            : $_POST['supportermembershiptypesingle'];
                  
                  
                  $subscription_details = array(
                        'amount'           => pence_to_pounds ( get_post_meta( $feeid, 'initial-payment', true ), false ),
                        'name'             => get_post_meta( $feeid, 'fee-name', true ),
                        'currency'         => 'GBP',
                        'user'             => $user, 
                        'state'            => "SP",
                  );
                  
                  if ( $description = get_post_meta( $feeid, 'fee-description', true ) ) 
                        $subscription_details['description'] = $description;
                  
                  
                   $gocardless_url = GoCardless::new_bill_url($subscription_details);

                  break;
            }
            
            ;
            
            
      }
      
      $errors = array();
              
        $singlelinefields = array(
            'Joining as' => 'joiningas',
            'First Name' => 'firstname',
            'Surname' => 'surname',
            'Gender' => 'gender',
            'Other Gender Details'  => 'othergender',
            'Date of Birth' => array(
                'dob-day', 'dob-month', 'dob-year'
            ),
            'Email Address' => 'email_addy',
            'Contact Number' => 'contact_number',
            'Line 1 (Address)' => 'streetaddyl1',
            'Line 2 (Address)' => 'streetaddyl2',
            'Town (Address)' => 'streetaddytown',
            'Postcode' => 'postcode',
            'Medical Conditions or Disabilities?' => 'medconsdisabyesno',
            'Allergies?' => 'allergiesyesno',
            'Injuries?' => 'injuredyesno',
            'First Name (Next of Kin)' => 'nokfirstname',
            'Surname (Next of Kin)'    => 'noksurname',
            'Relationship (Next of Kin)' => 'nokrelationship',
            'Contact Number (Next of Kin)' => 'nokcontactnumber',
            'Next of Kin Lives at Same Address?' => 'sameaddress',
            'Street Address (Next of Kin)' => 'nokstreetaddy',
            'Postcode (Next of Kin)' => 'nokpostcode',
            'Other Sports and Fitness?' => 'othersports',
            'Training hours a week?' => 'hoursaweektrain',
            'Played Before?' => 'playedbefore',
            'Where and for how many seasons?' => 'whereandseasons',
            'Height' => 'height',
            'Weight' => 'weight',
            'How many cigarettes per day?' => 'howmanycigsperday',
            'How did you hear about the Bisons?' => 'howdidyouhear',
            'What can you bring to the Bisons' => 'whatcanyoubring',
            'Top Size'  => 'topsize'

        );
        

        foreach ( $singlelinefields as $label => $fieldname)
        {
            switch ($label)
            {
                case "Date of Birth":

                $olddobday = get_user_meta($form_user, 'dob-day', true);
                $olddobmonth = get_user_meta($form_user, 'dob-month', true ); 
                $olddobyear = get_user_meta($form_user, 'dob-year', true );
                
                if( $_POST['dob-day'] != $olddobday
                 || $_POST['dob-month'] != $olddobmonth
                 || $_POST['dob-year'] != $olddobyear )
                {

                    $infotable .= "<tr><th>Date of Birth</th>";
                    if ( $_POST['form_id'] ) $infotable .= "<td>$olddobday/$olddobmonth/$olddobyear</td>";
                    $infotable .= '<td>'.$_POST['dob-day'].'/'.$_POST['dob-month'].'/'.$_POST['dob-year'].'</td>';
                    $infotable .= "</tr>";
                    update_user_meta($form_user, 'dob-day', $_POST['dob-day']);
                    update_user_meta($form_user, 'dob-month', $_POST['dob-month']);
                    update_user_meta($form_user, 'dob-year', $_POST['dob-year']);
                }                        
                break;
                
                
                default:

                if ( $_POST[$fieldname] != ($oldfield = get_user_meta($form_user, $fieldname, true) ) ) 
                {
                    
                    if ( $label == $email_addy) wp_update_user( array ('user_email' => $_POST['email_addy'] ) );
                    $infotable .= "<tr><th>$label</th>";
                    if ( $_POST['form_id'] ) $infotable .= "<td>".str_replace("\n", "<br />", $oldfield)."</td>";
                    $infotable .= '<td>'.str_replace("\n", "<br />", $_POST[$fieldname]).'</td>';
                    $infotable .= "</tr>";
                    update_user_meta($form_user, $fieldname, $_POST[$fieldname]);
                }
            }
        }
           
        if ( $_POST['fainting'] != get_user_meta($form_user, 'fainting', true) ||
             $_POST['dizzyturns'] != get_user_meta($form_user, 'dizzyturns', true) ||
             $_POST['breathlessness'] != get_user_meta($form_user, 'breathlessness', true) ||
             $_POST['bloodpressure'] != get_user_meta($form_user, 'bloodpressure', true) ||
             $_POST['diabetes'] != get_user_meta($form_user, 'diabetes', true) ||
             $_POST['palpitations'] != get_user_meta($form_user, 'palpitations', true) ||
             $_POST['chestpain'] != get_user_meta($form_user, 'chestpain', true) ||
             $_POST['suddendeath'] != get_user_meta($form_user, 'suddendeath', true) ||
             $_POST['smoking'] != get_user_meta($form_user, 'suddendeath', true) )
        {
            $conditions = array();
            
            
            if ( get_user_meta($form_user, 'fainting', true) == 'on') $oldconditions[] = 'Fainting';
            update_user_meta($form_user, 'fainting', $_POST['fainting']);
            if ( $_POST['fainting'] == 'on' ) $conditions[] = 'Fainting';
            
            if ( get_user_meta($form_user, 'fainting', true) == 'on') $oldconditions[] = 'Dizzy Turns';
            update_user_meta($form_user, 'dizzyturns', $_POST['dizzyturns']);
            if ( $_POST['dizzyturns'] == 'on' ) $conditions[] = 'Dizzy Turns';

            if ( get_user_meta($form_user, 'fainting', true) == 'on') $oldconditions[] = 'Breathlessness or being more easily tired than teammates';
            update_user_meta($form_user, 'breathlessness', $_POST['breathlessness']);
            if ( $_POST['breathlessness'] == 'on' ) $conditions[] = 'Breathlessness or being more easily tired than teammates';

            if ( get_user_meta($form_user, 'fainting', true) == 'on') $oldconditions[] = 'History of high blood pressure';
            update_user_meta($form_user, 'bloodpressure', $_POST['bloodpressure']);
            if ( $_POST['bloodpressure'] == 'on' ) $conditions[] = 'History of high blood pressure';

            if ( get_user_meta($form_user, 'fainting', true) == 'on') $oldconditions[] = 'Diabetes';
            update_user_meta($form_user, 'diabetes', $_POST['diabetes']);
            if ( $_POST['diabetes'] == 'on' ) $conditions[] = 'Diabetes';

            if ( get_user_meta($form_user, 'fainting', true) == 'on') $oldconditions[] = 'Palpatations';
            update_user_meta($form_user, 'palpitations', $_POST['palpitations']);
            if ( $_POST['palpitations'] == 'on' ) $conditions[] = 'Palpatations';

            if ( get_user_meta($form_user, 'fainting', true) == 'on') $oldconditions[] = 'Chest pain or tightness';
            update_user_meta($form_user, 'chestpain', $_POST['chestpain']);
            if ( $_POST['chestpain'] == 'on' ) $conditions[] = 'Chest Pain';

            if ( get_user_meta($form_user, 'fainting', true) == 'on') $oldconditions[] = 'Sudden death in immediate family of anyone under 50';
            update_user_meta($form_user, 'suddendeath', $_POST['suddendeath']);
            if ( $_POST['suddendeath'] == 'on' ) $conditions[] = 'suddendeath';

            if ( get_user_meta($form_user, 'fainting', true) == 'on') $oldconditions[] = 'Smoking';
            update_user_meta($form_user, 'smoking', $_POST['smoking']);
            if ( $_POST['smoking'] == 'on' ) $conditions[] = 'Smoking';

            $conditionsstring = "";
            for ( $ii = 0; $conditions[$ii]; $ii++ ) $conditionsstring .= ( $ii ? ', ' : null ).$conditions[$ii];
            
            $oldconditionstring = "";
            for ( $ii = 0; $oldconditions[$ii]; $ii++ ) $oldconditionstring .= ( $ii ? ', ' : null ).$oldconditions[$ii];


            $infotable .= '<tr><th>Cardiac Questionnaire</th><td>';
            $infotable .=  $oldconditionstring ? $oldconditionstring : "None";
            $infotable .= '</td><td>';
            $infotable .=  $conditionsstring ? $conditionsstring : "None";
            $infotable .= '</td></tr>';
       }
        
      

        $infotable .= "</tbody></table>";

        for ( $i = 1; isset( $_POST['condsdisablities_name_row' . $i] ); $i++ )
        {
            if ( $_POST['condsdisablities_name_row' . $i] != get_user_meta($form_user, 'condsdisablities_name_row' . $i, true) ||
                 $_POST['condsdisablities_drugname_row' . $i] != get_user_meta($form_user, 'condsdisablities_drugname_row' . $i, true) ||
                 $_POST['condsdisablities_drugdose_freq_row' . $i] != get_user_meta($form_user, 'condsdisablities_drugdose_freq_row' . $i, true) ) 
                 $newinfotable = "<h2>Conditions or Disabilities</h2><table><thead><tr><td>Condition</td><td>Medication</td><td>Dose</td></tr></thead><tbody>";
         }

        if ($_POST['medconsdisabyesno'] == "Yes")
        {   
            $i = 1;
            $realcount = 1;
            while ( isset( $_POST['condsdisablities_name_row' . $i] ) )
            {
                
                if ( $_POST['condsdisablities_name_row' . $i] != '' )
                {
                    update_user_meta($form_user, 'condsdisablities_name_row' . $realcount, $_POST['condsdisablities_name_row' . $i]);
                    update_user_meta($form_user, 'condsdisablities_drugname_row' . $realcount, $_POST['condsdisablities_drugname_row' . $i]);
                    update_user_meta($form_user, 'condsdisablities_drugdose_freq_row' . $realcount, $_POST['condsdisablities_drugdose_freq_row' . $i]);
                    update_user_meta($form_user, 'condsdisablities_rowcount', $realcount);                
                    $newinfotable .= $newinfotable ? "<tr><td>".get_user_meta($form_user, 'condsdisablities_name_row' . $realcount, true)."</td><td>".get_user_meta($form_user, 'condsdisablities_drugname_row' . $realcount, true)."</td><td>".get_user_meta($form_user, 'condsdisablities_drugdose_freq_row' . $realcount, true)."</td></tr>": null;                 
                    $realcount++;
                }
                $i++;  
            }
        }
        $infotable .= $newinfotable ? $newinfotable.'</tbody></table>' : null ;
       
  
        for ( $i = 1; isset( $_POST['allergies_name_row' . $i] ); $i++ )
        {
            if ( $_POST['allergies_name_row' . $i] != get_user_meta($form_user, 'allergies_name_row' . $i, true) ||
                 $_POST['allergies_drugname_row' . $i] != get_user_meta($form_user, 'allergies_drugname_row' . $i, true) ||
                 $_POST['allergies_drugdose_freq_row' . $i] != get_user_meta($form_user, 'allergies_drugdose_freq_row' . $i, true) ) $newinfotable2 = "<h2>Allergies</h2><table><thead><tr><td>Allergy</td><td>Medication</td><td>Dose</td></tr></thead><tbody>";
         }
        
        if ($_POST['allergiesyesno'] == "Yes")
        {
            $i = 1;
            $realcount = 1;
            while ( isset( $_POST['allergies_name_row' . $i] ) )
            {
                if ( $_POST['allergies_name_row' . $i] != '' )
                {
                    update_user_meta($form_user, 'allergies_name_row' . $realcount, $_POST['allergies_name_row' . $i]);
                    update_user_meta($form_user, 'allergies_drugname_row' . $realcount, $_POST['allergies_drugname_row' . $i]);
                    update_user_meta($form_user, 'allergies_drugdose_freq_row' . $realcount, $_POST['allergies_drugdose_freq_row' . $i]);
                    update_user_meta($form_user, 'allergies_rowcount', $realcount);
                    $newinfotable2 .= $newinfotable2 ? "<tr><td>".get_user_meta($form_user, 'allergies_name_row' . $realcount, true)."</td><td>".get_user_meta($form_user, 'allergies_drugname_row' . $realcount, true)."</td><td>".get_user_meta($form_user, 'allergies_drugdose_freq_row' . $realcount, true)."</td></tr>": null;
                    $realcount++;
                }
                $i++;
            }
        }
        
        $infotable .= $newinfotable2 ? $newinfotable2.'</tbody></table>' : null;
        
        for ( $i = 1; isset( $_POST['injuries_name_row' . $i] ); $i++ )
        {
            if ( $_POST['injuries_name_row' . $i] != get_user_meta($form_user, 'injuries_name_row' . $i, true) ||
                 $_POST['injuries_when_row' . $i] != get_user_meta($form_user, 'injuries_when_row' . $i, true) ||
                 $_POST['injuries_treatmentreceived_row' . $i] != get_user_meta($form_user, 'injuries_treatmentreceived_row' . $i, true) || 
                 $_POST['injuries_who_row' . $i] != get_user_meta($form_user, 'injuries_who_row' . $i, true) ||
                 $_POST['injuries_status_row' . $i] != get_user_meta($form_user, 'injuries_status_row' . $i, true) ) $newinfotable3 = "<h2>Injuries</h2><table><thead><tr><td>Injury</td><td>When</td><td>What treatment</td><td>Who treated</td><td>Injury status</td></tr></thead><tbody>";
        }
         
        if ($_POST['injuredyesno'] == "Yes")
        {
            $i = 1;
            $realcount = 1; 
            while( isset( $_POST['injuries_name_row' . $i] ) )
            {
                if ( $_POST['injuries_name_row' . $i] != '' )
                {
                    update_user_meta($form_user, 'injuries_name_row' . $realcount, $_POST['injuries_name_row' . $i]);
                    update_user_meta($form_user, 'injuries_when_row' . $realcount, $_POST['injuries_when_row' . $i]);
                    update_user_meta($form_user, 'injuries_treatmentreceived_row' . $realcount, $_POST['injuries_treatmentreceived_row' . $i]);
                    update_user_meta($form_user, 'injuries_who_row' . $realcount, $_POST['injuries_who_row' . $i]);
                    update_user_meta($form_user, 'injuries_status_row' . $realcount, $_POST['injuries_status_row' . $i]);
                    update_user_meta($form_user, 'injuries_rowcount', $realcount);
                    $newinfotable3 .= $newinfotable3 ? "<tr><td>".get_user_meta($form_user, 'injuries_name_row' . $realcount, true)."</td><td>".get_user_meta($form_user, 'injuries_when_row' . $realcount, true)."</td><td>".get_user_meta($form_user, 'injuries_treatmentreceived_row' . $i, true)."</td><td>".get_user_meta($form_user, 'injuries_who_row' . $realcount, true)."</td><td>".get_user_meta($form_user, 'injuries_status_row' . $realcount, true)."</td></tr>": null;
                    $realcount++;
                }
                $i++;
            }

            for ( $i = 1; isset( $_POST['injuries_name_row' . $i] ) && $_POST['injuries_name_row' . $i] != ''; $i++ )
            {
                update_user_meta($form_user, 'injuries_name_row' . $i, $_POST['injuries_name_row' . $i]);
                update_user_meta($form_user, 'injuries_when_row' . $i, $_POST['injuries_when_row' . $i]);
                update_user_meta($form_user, 'injuries_treatmentreceived_row' . $i, $_POST['injuries_treatmentreceived_row' . $i]);
                update_user_meta($form_user, 'injuries_who_row' . $i, $_POST['injuries_who_row' . $i]);
                update_user_meta($form_user, 'injuries_status_row' . $i, $_POST['injuries_status_row' . $i]);
                update_user_meta($form_user, 'injuries_rowcount', $i);
                $newinfotable3 .= $newinfotable3 ? "<tr><td>".get_user_meta($form_user, 'injuries_name_row' . $i, true)."</td><td>".get_user_meta($form_user, 'injuries_when_row' . $i, true)."</td><td>".get_user_meta($form_user, 'injuries_treatmentreceived_row' . $i, true)."</td><td>".get_user_meta($form_user, 'injuries_who_row' . $i, true)."</td><td>".get_user_meta($form_user, 'injuries_status_row' . $i, true)."</td></tr>": null;
            }
        }
        $infotable .= $newinfotable3 ? $newinfotable3.'</tbody></table>' : null;
        

        
        update_user_meta($form_user, 'current', 'true');
        update_user_meta($form_user, 'memtype', $_POST['memtype']);
  
        if ( $infotable ) 
        {
            // Construct info table for email
            $infotablestart = "<table>";
            if ( $_POST['form_id'] ) $infotablestart .= "<thead><tr><th>&nbsp;</th><th>Previous</th><th>Updated</th></tr></thead><tbody>";    
            else $infotablestart .= "<tbody>";

            $infotable = $infotablestart.$infotable;
            $email_options = get_option('email-settings-page');
            $subject = $email_options['newmember-information-email-subject '];
            $content = wpautop ( $email_options['member-information-email-content'] );
            $content = preg_replace("/(.*)@@name@@(.*)/", "$1".get_user_meta($form_user, 'firstname', true).' '.get_user_meta($form_user, 'surname', true)."$2", $content);       
            $content = preg_replace("/(.*)@@updatetable@@(.*)/", "$1$infotable$2", $content);

            send_bison_mail( false, $subject, $content, false, $email_options['member-email-send-to-text'] );
        }   
}