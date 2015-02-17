<?php
if (!INCLUDED) exit;

// Don't post membership form if the reason we are submitting is because we are entering edit modes
if ( ! isset ( $_POST['edit_details'] ) )
{
   // Setup new post array
    $post = array(
        'post_title'    => $_POST['firstname'].' '.$_POST['surname'].' '.date('Y'),
        'post_type'     => 'membership_form',
        'post_status'   => 'publish',
    );
    
    if ( current_user_can ('committee_perms') )
    {
        $post['post_author'] = $_POST['form_belongs_to'];
    }
    
        // If a form ID has been submitted as part of the form data, then we must just be editing, if not, create a new one
        $post = $_POST['form_id'] ? $_POST['form_id'] : wp_insert_post( $post );
        
      // If there is no form_id, therefore it is a newly submitted form
      if ( ! $_POST['form_id'] )
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
                        'state'            => $post . "+DD",
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
                        'state'            => $post . "+SP",                       
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

                $olddobday = get_post_meta($post, 'dob-day', true);
                $olddobmonth = get_post_meta($post, 'dob-month', true ); 
                $olddobyear = get_post_meta($post, 'dob-year', true );
                
                if( $_POST['dob-day'] != $olddobday
                 || $_POST['dob-month'] != $olddobmonth
                 || $_POST['dob-year'] != $olddobyear )
                {

                    $infotable .= "<tr><th>Date of Birth</th>";
                    if ( $_POST['form_id'] ) $infotable .= "<td>$olddobday/$olddobmonth/$olddobyear</td>";
                    $infotable .= '<td>'.$_POST['dob-day'].'/'.$_POST['dob-month'].'/'.$_POST['dob-year'].'</td>';
                    $infotable .= "</tr>";
                    update_post_meta($post, 'dob-day', $_POST['dob-day']);
                    update_post_meta($post, 'dob-month', $_POST['dob-month']);
                    update_post_meta($post, 'dob-year', $_POST['dob-year']);
                }                        
                break;
                
                
                default:

                if ( $_POST[$fieldname] != ($oldfield = get_post_meta($post, $fieldname, true) ) ) 
                {
                    
                    if ( $label == $email_addy) wp_update_user( array ('user_email' => $_POST['email_addy'] ) );
                    $infotable .= "<tr><th>$label</th>";
                    if ( $_POST['form_id'] ) $infotable .= "<td>".str_replace("\n", "<br />", $oldfield)."</td>";
                    $infotable .= '<td>'.str_replace("\n", "<br />", $_POST[$fieldname]).'</td>';
                    $infotable .= "</tr>";
                    update_post_meta($post, $fieldname, $_POST[$fieldname]);
                }
            }
        }
           
        if ( $_POST['fainting'] != get_post_meta($post, 'fainting', true) ||
             $_POST['dizzyturns'] != get_post_meta($post, 'dizzyturns', true) ||
             $_POST['breathlessness'] != get_post_meta($post, 'breathlessness', true) ||
             $_POST['bloodpressure'] != get_post_meta($post, 'bloodpressure', true) ||
             $_POST['diabetes'] != get_post_meta($post, 'diabetes', true) ||
             $_POST['palpitations'] != get_post_meta($post, 'palpitations', true) ||
             $_POST['chestpain'] != get_post_meta($post, 'chestpain', true) ||
             $_POST['suddendeath'] != get_post_meta($post, 'suddendeath', true) ||
             $_POST['smoking'] != get_post_meta($post, 'suddendeath', true) )
        {
            $conditions = array();
            
            
            if ( get_post_meta($post, 'fainting', true) == 'on') $oldconditions[] = 'Fainting';
            update_post_meta($post, 'fainting', $_POST['fainting']);
            if ( $_POST['fainting'] == 'on' ) $conditions[] = 'Fainting';
            
            if ( get_post_meta($post, 'fainting', true) == 'on') $oldconditions[] = 'Dizzy Turns';
            update_post_meta($post, 'dizzyturns', $_POST['dizzyturns']);
            if ( $_POST['dizzyturns'] == 'on' ) $conditions[] = 'Dizzy Turns';

            if ( get_post_meta($post, 'fainting', true) == 'on') $oldconditions[] = 'Breathlessness or being more easily tired than teammates';
            update_post_meta($post, 'breathlessness', $_POST['breathlessness']);
            if ( $_POST['breathlessness'] == 'on' ) $conditions[] = 'Breathlessness or being more easily tired than teammates';

            if ( get_post_meta($post, 'fainting', true) == 'on') $oldconditions[] = 'History of high blood pressure';
            update_post_meta($post, 'bloodpressure', $_POST['bloodpressure']);
            if ( $_POST['bloodpressure'] == 'on' ) $conditions[] = 'History of high blood pressure';

            if ( get_post_meta($post, 'fainting', true) == 'on') $oldconditions[] = 'Diabetes';
            update_post_meta($post, 'diabetes', $_POST['diabetes']);
            if ( $_POST['diabetes'] == 'on' ) $conditions[] = 'Diabetes';

            if ( get_post_meta($post, 'fainting', true) == 'on') $oldconditions[] = 'Palpatations';
            update_post_meta($post, 'palpitations', $_POST['palpitations']);
            if ( $_POST['palpitations'] == 'on' ) $conditions[] = 'Palpatations';

            if ( get_post_meta($post, 'fainting', true) == 'on') $oldconditions[] = 'Chest pain or tightness';
            update_post_meta($post, 'chestpain', $_POST['chestpain']);
            if ( $_POST['chestpain'] == 'on' ) $conditions[] = 'Chest Pain';

            if ( get_post_meta($post, 'fainting', true) == 'on') $oldconditions[] = 'Sudden death in immediate family of anyone under 50';
            update_post_meta($post, 'suddendeath', $_POST['suddendeath']);
            if ( $_POST['suddendeath'] == 'on' ) $conditions[] = 'suddendeath';

            if ( get_post_meta($post, 'fainting', true) == 'on') $oldconditions[] = 'Smoking';
            update_post_meta($post, 'smoking', $_POST['smoking']);
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
            if ( $_POST['condsdisablities_name_row' . $i] != get_post_meta($post, 'condsdisablities_name_row' . $i, true) ||
                 $_POST['condsdisablities_drugname_row' . $i] != get_post_meta($post, 'condsdisablities_drugname_row' . $i, true) ||
                 $_POST['condsdisablities_drugdose_freq_row' . $i] != get_post_meta($post, 'condsdisablities_drugdose_freq_row' . $i, true) ) 
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
                    update_post_meta($post, 'condsdisablities_name_row' . $realcount, $_POST['condsdisablities_name_row' . $i]);
                    update_post_meta($post, 'condsdisablities_drugname_row' . $realcount, $_POST['condsdisablities_drugname_row' . $i]);
                    update_post_meta($post, 'condsdisablities_drugdose_freq_row' . $realcount, $_POST['condsdisablities_drugdose_freq_row' . $i]);
                    update_post_meta($post, 'condsdisablities_rowcount', $realcount);                
                    $newinfotable .= $newinfotable ? "<tr><td>".get_post_meta($post, 'condsdisablities_name_row' . $realcount, true)."</td><td>".get_post_meta($post, 'condsdisablities_drugname_row' . $realcount, true)."</td><td>".get_post_meta($post, 'condsdisablities_drugdose_freq_row' . $realcount, true)."</td></tr>": null;                 
                    $realcount++;
                }
                $i++;  
            }
        }
        $infotable .= $newinfotable ? $newinfotable.'</tbody></table>' : null ;
       
  
        for ( $i = 1; isset( $_POST['allergies_name_row' . $i] ); $i++ )
        {
            if ( $_POST['allergies_name_row' . $i] != get_post_meta($post, 'allergies_name_row' . $i, true) ||
                 $_POST['allergies_drugname_row' . $i] != get_post_meta($post, 'allergies_drugname_row' . $i, true) ||
                 $_POST['allergies_drugdose_freq_row' . $i] != get_post_meta($post, 'allergies_drugdose_freq_row' . $i, true) ) $newinfotable2 = "<h2>Allergies</h2><table><thead><tr><td>Allergy</td><td>Medication</td><td>Dose</td></tr></thead><tbody>";
         }
        
        if ($_POST['allergiesyesno'] == "Yes")
        {
            $i = 1;
            $realcount = 1;
            while ( isset( $_POST['allergies_name_row' . $i] ) )
            {
                if ( $_POST['allergies_name_row' . $i] != '' )
                {
                    update_post_meta($post, 'allergies_name_row' . $realcount, $_POST['allergies_name_row' . $i]);
                    update_post_meta($post, 'allergies_drugname_row' . $realcount, $_POST['allergies_drugname_row' . $i]);
                    update_post_meta($post, 'allergies_drugdose_freq_row' . $realcount, $_POST['allergies_drugdose_freq_row' . $i]);
                    update_post_meta($post, 'allergies_rowcount', $realcount);
                    $newinfotable2 .= $newinfotable2 ? "<tr><td>".get_post_meta($post, 'allergies_name_row' . $realcount, true)."</td><td>".get_post_meta($post, 'allergies_drugname_row' . $realcount, true)."</td><td>".get_post_meta($post, 'allergies_drugdose_freq_row' . $realcount, true)."</td></tr>": null;
                    $realcount++;
                }
                $i++;
            }
        }
        
        $infotable .= $newinfotable2 ? $newinfotable2.'</tbody></table>' : null;
        
        for ( $i = 1; isset( $_POST['injuries_name_row' . $i] ); $i++ )
        {
            if ( $_POST['injuries_name_row' . $i] != get_post_meta($post, 'injuries_name_row' . $i, true) ||
                 $_POST['injuries_when_row' . $i] != get_post_meta($post, 'injuries_when_row' . $i, true) ||
                 $_POST['injuries_treatmentreceived_row' . $i] != get_post_meta($post, 'injuries_treatmentreceived_row' . $i, true) || 
                 $_POST['injuries_who_row' . $i] != get_post_meta($post, 'injuries_who_row' . $i, true) ||
                 $_POST['injuries_status_row' . $i] != get_post_meta($post, 'injuries_status_row' . $i, true) ) $newinfotable3 = "<h2>Injuries</h2><table><thead><tr><td>Injury</td><td>When</td><td>What treatment</td><td>Who treated</td><td>Injury status</td></tr></thead><tbody>";
        }
         
        if ($_POST['injuredyesno'] == "Yes")
        {
            $i = 1;
            $realcount = 1; 
            while( isset( $_POST['injuries_name_row' . $i] ) )
            {
                if ( $_POST['injuries_name_row' . $i] != '' )
                {
                    update_post_meta($post, 'injuries_name_row' . $realcount, $_POST['injuries_name_row' . $i]);
                    update_post_meta($post, 'injuries_when_row' . $realcount, $_POST['injuries_when_row' . $i]);
                    update_post_meta($post, 'injuries_treatmentreceived_row' . $realcount, $_POST['injuries_treatmentreceived_row' . $i]);
                    update_post_meta($post, 'injuries_who_row' . $realcount, $_POST['injuries_who_row' . $i]);
                    update_post_meta($post, 'injuries_status_row' . $realcount, $_POST['injuries_status_row' . $i]);
                    update_post_meta($post, 'injuries_rowcount', $realcount);
                    $newinfotable3 .= $newinfotable3 ? "<tr><td>".get_post_meta($post, 'injuries_name_row' . $realcount, true)."</td><td>".get_post_meta($post, 'injuries_when_row' . $realcount, true)."</td><td>".get_post_meta($post, 'injuries_treatmentreceived_row' . $i, true)."</td><td>".get_post_meta($post, 'injuries_who_row' . $realcount, true)."</td><td>".get_post_meta($post, 'injuries_status_row' . $realcount, true)."</td></tr>": null;
                    $realcount++;
                }
                $i++;
            }

            for ( $i = 1; isset( $_POST['injuries_name_row' . $i] ) && $_POST['injuries_name_row' . $i] != ''; $i++ )
            {
                update_post_meta($post, 'injuries_name_row' . $i, $_POST['injuries_name_row' . $i]);
                update_post_meta($post, 'injuries_when_row' . $i, $_POST['injuries_when_row' . $i]);
                update_post_meta($post, 'injuries_treatmentreceived_row' . $i, $_POST['injuries_treatmentreceived_row' . $i]);
                update_post_meta($post, 'injuries_who_row' . $i, $_POST['injuries_who_row' . $i]);
                update_post_meta($post, 'injuries_status_row' . $i, $_POST['injuries_status_row' . $i]);
                update_post_meta($post, 'injuries_rowcount', $i);
                $newinfotable3 .= $newinfotable3 ? "<tr><td>".get_post_meta($post, 'injuries_name_row' . $i, true)."</td><td>".get_post_meta($post, 'injuries_when_row' . $i, true)."</td><td>".get_post_meta($post, 'injuries_treatmentreceived_row' . $i, true)."</td><td>".get_post_meta($post, 'injuries_who_row' . $i, true)."</td><td>".get_post_meta($post, 'injuries_status_row' . $i, true)."</td></tr>": null;
            }
        }
        $infotable .= $newinfotable3 ? $newinfotable3.'</tbody></table>' : null;
        

        
        update_post_meta($post, 'current', 'true');
        update_post_meta($post, 'memtype', $_POST['memtype']);
  
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
            $content = preg_replace("/(.*)@@name@@(.*)/", "$1".get_post_meta($post, 'firstname', true).' '.get_post_meta($post, 'surname', true)."$2", $content);       
            $content = preg_replace("/(.*)@@updatetable@@(.*)/", "$1$infotable$2", $content);

            send_bison_mail( false, $subject, $content, false, $email_options['member-email-send-to-text'] );
        }   
}