<?php
    wp_enqueue_script('dynamicforms');
    wp_enqueue_script('formvalidation-membership');




$formUser = ( isset ( $_GET['player_id'] ) && current_user_can ('committee_perms') )
                ? $_GET['player_id'] : get_current_user_id();

$userData = get_userdata ( $formUser );

// If there is a resource_id in the querystring, it must returning from Gocardless, so confirm the payment and then save the resource information if it confirms properly
if ( isset ( $_GET['resource_id'] ) ) {
	global $bisonsMembership;
	$bisonsMembership->confirmPreauth($_GET, $formUser);
}

if ( ! isset ( $form_id ) )
	$form_id = NULL;


    wp_enqueue_script('formvalidation');

?>

<header>
<h2>Membership Form</h2>
	<?php get_template_part( 'snippets/playerPage', 'menu' ) ?>

</header>
<?php get_template_part( 'snippets/playerPage', 'flashMessages' ) ?>
<?php global $gocardless_url; if ( isset ( $gocardless_url ) ) : ?>
<script type='text/javascript'> setTimeout(function(){ document.location = '<?php echo $gocardless_url ?>'; }, 3000); </script>
<?php endif ?>

<?php if ( get_user_meta($formUser, 'joined', true ) == true ) : ?>
<p class='important'><i class='fa fa-exclamation-circle'></i>Please note that it is your responsibility to ensure that the information supplied below (particularly medical information) remains up to date. You can return to this form and make changes at any time. </p>
<?php else: ?>
<p class='important'><i class='fa fa-exclamation-circle'></i>Please take a moment to fill out the form below. Note that all the information supplied will remain completely <strong>confidential</strong>. Should you have any questions about anything on this form, please contact the <strong>membership secretary</strong> using the contact details at the top of the <a href='<?php echo home_url ('/players-area/') ?>'>players area</a>...</p>
<?php endif; ?>
<div id="statusBar">
</div>
<form id='membershipform_payment' method="post" role="form">

    <?php if  ( current_user_can ('committee_perms') ) : ?>
    <fieldset>
        <legend>Active Player</legend>
		<div>
			<label>Select</label>
        <select id='committeeSelectPlayer'>
            <option value='me'>Me</option>
        <?php $users = getBisonsUsers(); foreach ($users as $user) : ?>
            <option value='<?php echo $user->data->ID; ?>' <?php if ( isset ( $_GET['player_id' ] ) ) { if (  $_GET['player_id' ] == $user->data->ID ) { echo " selected='selected'"; } } ?>><?php echo $user->data->display_name ?></option>
        <?php endforeach ?>
        </select>
        </div>
        <?php if ( isset ( $_GET['player_id' ] ) ) : ?>
        <p class='info'>This is NOT YOUR MEMBERSHIP form. You can fill in someone else's form below or use the dropdown box below to return to your membership form.</p>

        <?php else : ?>
	        <p class='info'>This is your own membership form. As a committee member, you can use this dropdown box to select and edit the membership form of other players.</p>
        <?php endif ?>
    </fieldset>
    <?php endif ?>
    <fieldset>
        <legend>Player or Supporter</legend>
        <div>
            <label>Joining as</label>
            <select class="required" name='joiningas' id='joiningas'>
                <option value="">Choose...</option>
                <?php selectOptionFromMeta($formUser, 'joiningas', 'Player') ?>
	            <?php selectOptionFromMeta($formUser, 'joiningas', 'Supporter') ?>
            </select>
            <p class='forminfo'>Please note that a supporter membership is specifically for those that want to support the team but do not want to play any rugby. If you will be playing with us, please make sure you choose 'player' here because we will need to take some details of your medical history for you as part of our duty of care.</p>
        </div>
    </fieldset>

    <fieldset>
        <legend>Personal Details</legend>
        <div>
            <label class="smalllabel" for="firstname">First name</label>
            <input type="text" class="smalltextbox required min2chars" name="firstname" id="firstname" value='<?php echo $userData->user_firstname ?>'/>
        </div>
        <div>
            <label class="smalllabel" for="surname">Surname</label>
            <input type="text" class="smalltextbox required min2chars" name="surname" id="surname" value='<?php echo $userData->user_lastname ?>'/>
        </div>
        <div>
            <label for="gender">Gender</label>
            <select class="required" name='gender' id='gender'>
                <option value="">Choose...</option>
                <option<?php selected( get_user_meta($formUser, 'gender', true), 'Male') ?>>Male</option>
                <option<?php selected( get_user_meta($formUser, 'gender', true), 'Female') ?>>Female</option>
                <option<?php selected( get_user_meta($formUser, 'gender', true), 'Other') ?>>Other</option>
            </select>
        </div>
        <div id="othergender" <?php if (get_user_meta($formUser, 'gender', true) == "Other") { ?> style="display:block"<?php } ?>>
            <label class="smalllabel" for="othergender">Other Gender Details</label>
            <input type="text" class="smalltextbox required" name="othergender" value='<?php echo get_user_meta($formUser, 'othergender', true) ?>' />
            <p class="forminfo">As a fully inclusive rugby club, we completely recognise that a gender designation of 'male' or 'female' is far too simplistic for the real world. However, because we are a rugby team, we are bound by <a href='http://www.rfu.com/' title='RFU Website'>RFU</a> regulations which unfortunately are categorised in simple male/female terms. Please be aware therefore that only a person who self-identifies as 'male' in some way can play in 'male' rugby. Likewise, only a person who self-identifies as 'female' in some way can play in 'female' rugby.</p>
        </div>
        <div class="noBackground">
            <label class="smalllabel" for="dob">Date of Birth</label>
             <div class="inlinediv">
             <select class="norightmargin required" id="dob-day" name="dob-day">
                    <option value=""></option>
                    <option value="1"<?php selected( get_user_meta($formUser, 'dob-day', true), '1') ?>>1st</option>
                    <option value="2"<?php selected( get_user_meta($formUser, 'dob-day', true), '2') ?>>2nd</option>
                    <option value="3"<?php selected( get_user_meta($formUser, 'dob-day', true), '3') ?>>3rd</option>
                    <option value="4"<?php selected( get_user_meta($formUser, 'dob-day', true), '4') ?>>4th</option>
                    <option value="5"<?php selected( get_user_meta($formUser, 'dob-day', true), '5') ?>>5th</option>
                    <option value="6"<?php selected( get_user_meta($formUser, 'dob-day', true), '6') ?>>6th</option>
                    <option value="7"<?php selected( get_user_meta($formUser, 'dob-day', true), '7') ?>>7th</option>
                    <option value="8"<?php selected( get_user_meta($formUser, 'dob-day', true), '8') ?>>8th</option>
                    <option value="9"<?php selected( get_user_meta($formUser, 'dob-day', true), '9') ?>>9th</option>
                    <option value="10"<?php selected( get_user_meta($formUser, 'dob-day', true), '10') ?>>10th</option>
                    <option value="11"<?php selected( get_user_meta($formUser, 'dob-day', true), '11') ?>>11th</option>
                    <option value="12"<?php selected( get_user_meta($formUser, 'dob-day', true), '12') ?>>12th</option>
                    <option value="13"<?php selected( get_user_meta($formUser, 'dob-day', true), '13') ?>>13th</option>
                    <option value="14"<?php selected( get_user_meta($formUser, 'dob-day', true), '14') ?>>14th</option>
                    <option value="15"<?php selected( get_user_meta($formUser, 'dob-day', true), '15') ?>>15th</option>
                    <option value="16"<?php selected( get_user_meta($formUser, 'dob-day', true), '16') ?>>16th</option>
                    <option value="17"<?php selected( get_user_meta($formUser, 'dob-day', true), '17') ?>>17th</option>
                    <option value="18"<?php selected( get_user_meta($formUser, 'dob-day', true), '18') ?>>18th</option>
                    <option value="19"<?php selected( get_user_meta($formUser, 'dob-day', true), '19') ?>>19th</option>
                    <option value="20"<?php selected( get_user_meta($formUser, 'dob-day', true), '20') ?>>20th</option>
                    <option value="21"<?php selected( get_user_meta($formUser, 'dob-day', true), '21') ?>>21st</option>
                    <option value="22"<?php selected( get_user_meta($formUser, 'dob-day', true), '22') ?>>22nd</option>
                    <option value="23"<?php selected( get_user_meta($formUser, 'dob-day', true), '23') ?>>23rd</option>
                    <option value="24"<?php selected( get_user_meta($formUser, 'dob-day', true), '24') ?>>24th</option>
                    <option value="25"<?php selected( get_user_meta($formUser, 'dob-day', true), '25') ?>>25th</option>
                    <option value="26"<?php selected( get_user_meta($formUser, 'dob-day', true), '26') ?>>26th</option>
                    <option value="27"<?php selected( get_user_meta($formUser, 'dob-day', true), '27') ?>>27th</option>
                    <option value="28"<?php selected( get_user_meta($formUser, 'dob-day', true), '28') ?>>28th</option>
                    <option value="29"<?php selected( get_user_meta($formUser, 'dob-day', true), '29') ?>>29th</option>
                    <option value="30"<?php selected( get_user_meta($formUser, 'dob-day', true), '30') ?>>30th</option>
                    <option value="31"<?php selected( get_user_meta($formUser, 'dob-day', true), '31') ?>>31st</option>
                </select>
             <select class="norightmargin required" id="dob-month" name="dob-month">
                    <option value=""></option>
                    <option value="01"<?php selected( get_user_meta($formUser, 'dob-month', true), '01') ?>>January</option>
                    <option value="02"<?php selected( get_user_meta($formUser, 'dob-month', true), '02') ?>>February</option>
                    <option value="03"<?php selected( get_user_meta($formUser, 'dob-month', true), '03') ?>>March</option>
                    <option value="04"<?php selected( get_user_meta($formUser, 'dob-month', true), '04') ?>>April</option>
                    <option value="05"<?php selected( get_user_meta($formUser, 'dob-month', true), '05') ?>>May</option>
                    <option value="06"<?php selected( get_user_meta($formUser, 'dob-month', true), '06') ?>>June</option>
                    <option value="07"<?php selected( get_user_meta($formUser, 'dob-month', true), '07') ?>>July</option>
                    <option value="08"<?php selected( get_user_meta($formUser, 'dob-month', true), '08') ?>>August</option>
                    <option value="09"<?php selected( get_user_meta($formUser, 'dob-month', true), '09') ?>>September</option>
                    <option value="10"<?php selected( get_user_meta($formUser, 'dob-month', true), '10') ?>>October</option>
                    <option value="11"<?php selected( get_user_meta($formUser, 'dob-month', true), '11') ?>>November</option>
                    <option value="12"<?php selected( get_user_meta($formUser, 'dob-month', true), '12') ?>>December</option>
                </select>
            <select class="norightmargin required" id="dob-year" name="dob-year">
                    <option value=""></option>
                <?php for ($i = 1901; $i < 2014; $i++ ) : ?>
                <option<?php selected( get_user_meta($formUser, 'dob-year', true), $i) ?>><?php echo $i ?></option>
                <?php endfor ?>
            </select>
            </div>
        </div>
        <div>
            <label class="smalllabel" for="email_addy">Email</label>
            <input type="email" class="smalltextbox required" name="email_addy" id="email_addy" value='<?php echo $userData->user_email ?>'/>
        </div>
        <div>
            <label class="smalllabel" for="contact_number">Contact Number</label>
            <input type="tel" class="smalltextbox required" name="contact_number" id="contact_number" value='<?php echo get_user_meta($formUser, 'contact_number', true) ?>'/>
        </div>
    </fieldset>
    <fieldset>
        <legend>Home Address</legend>
        <div>
            <label  class="smalllabel" for="streetaddyl1">Line 1</label>
            <input type="text" class="smalltextbox required" name="streetaddyl1" id="streetaddyl1" value='<?php echo get_user_meta($formUser, 'streetaddyl1', true) ?>' />
        </div>
        <div>
            <label  class="smalllabel" for="streetaddyl2">Line 2</label>
            <input type="text" class="smalltextbox" name="streetaddyl2" id="streetaddyl2" value='<?php echo get_user_meta($formUser, 'streetaddyl2', true) ?>' />
        </div>
        <div>
            <label  class="smalllabel" for="streetaddytown">Town</label>
            <input type="text" class="smalltextbox" name="streetaddytown" id="streetaddytown" value='<?php echo get_user_meta($formUser, 'streetaddytown', true) ?>' />
        </div>
        <div>
            <label  class="smalllabel" for="postcode">Postcode</label>
            <input type="text" class="smalltextbox required postcode" name="postcode" id="postcode" value='<?php echo get_user_meta($formUser, 'postcode', true) ?>' />
        </div>
    </fieldset>
    <fieldset>
        <legend>Next of Kin</legend>
        <p class="info">This person will be contacted in case of emergencies.</p>
        <div>
            <label class="smalllabel" for="nokfirstname">First name</label>
            <input type="text" class="smalltextbox required" name="nokfirstname" id="nokfirstname" value='<?php echo get_user_meta($formUser, 'nokfirstname', true) ?>' />
        </div>
        <div>
            <label class="smalllabel" for="noksurname">Surname</label>
            <input type="text" class="smalltextbox required" name="noksurname" id="noksurname" value='<?php echo get_user_meta($formUser, 'noksurname', true) ?>' />
        </div>
        <div>
            <label class="smalllabel" for="nokrelationship">Relationship</label>
            <input type="text" class="smalltextbox required" name="nokrelationship" id="nokrelationship" value='<?php echo get_user_meta($formUser, 'nokrelationship', true) ?>' />
        </div>
       <div>
            <label class="smalllabel" for="nokcontactnumber">Phone Number</label>
            <input type="tel" class="smalltextbox required" name="nokcontactnumber" id="nokcontactnumber" value='<?php echo get_user_meta($formUser, 'nokcontactnumber', true) ?>' />
        </div>
        <div>
            <label>Lives at same address</label>
            <select name='sameaddress' id='sameaddress'>
                <option value="">Choose...</option>
	            <?php selectOptionFromMeta($formUser, 'sameaddress', 'Yes') ?>
	            <?php selectOptionFromMeta($formUser, 'sameaddress', 'No') ?>
            </select>
        </div>
        <div class='fieldGroup' id="nokaddygroup"<?php if ( get_user_meta($formUser, 'joined', true ) == true && get_user_meta($formUser, 'sameaddress', true) == "No") { ?> style="display:block"<?php } ?>>
            <div>
                <label for="nokstreetaddy">Street address</label>
                <textarea class='required' name="nokstreetaddy" id="nokstreetaddy"><?php if ( get_user_meta($formUser, 'joined', true ) == true ) { echo get_user_meta($formUser, 'nokstreetaddy', true); } ?></textarea>
            </div>
            <div>
                <label  class="smalllabel" for="nokpostcode">Postcode</label>
                <input type="text" class="smalltextbox required postcode" name="nokpostcode" id="nokpostcode" value='<?php echo get_user_meta($formUser, 'nokpostcode', true) ?>' />
            </div>
        </div>
    </fieldset>
    <div class="playersonly"<?php if ( get_user_meta($formUser, 'joiningas', true) == 'Supporter' ) echo ' style="display:none"' ?>>
    <fieldset>
        <legend>Medical Declaration</legend>
        <p class="info">Please answer the next few sections as accurately and honestly as you can. In the very rare event of some kind of injury occurring, this information will help insure that medical professionals are able to do their job properly. </p>
        <div>
            <label>Do you have any current medical conditions or disabilities?</label>
            <select class="required" name='medconsdisabyesno' id='medconsdisabyesno'>
                <option value="">Choose...</option>
	            <?php selectOptionFromMeta($formUser, 'medconsdisabyesno', 'Yes') ?>
	            <?php selectOptionFromMeta($formUser, 'medconsdisabyesno', 'No') ?>
            </select>
            <p class="forminfo">For example, asthma, diabetes, epilepsy, anaemia, haemophilia, viral illness, etc.</p>
        </div>

        <div>
            <label>Do you have any allergies?</label>
            <select class="required" name='allergiesyesno' id='allergiesyesno'>
                <option value="">Choose...</option>
	            <?php selectOptionFromMeta($formUser, 'allergiesyesno', 'Yes') ?>
	            <?php selectOptionFromMeta($formUser, 'allergiesyesno', 'No') ?>
            </select>
            <p class="forminfo">For example, bee-stings, peanut butter etc.</p>
        </div>
        <div>
            <label>Have you ever been injured?</label>
            <select class="required" name='injuredyesno' id='injuredyesno'>
                <option value="">Choose...</option>
	            <?php selectOptionFromMeta($formUser, 'injuredyesno', 'Yes') ?>
	            <?php selectOptionFromMeta($formUser, 'injuredyesno', 'No') ?>
            </select>
            <p class="forminfo">For example, concussion or a broken rib.</p>
        </div>
    </fieldset>

    <fieldset id="conddisablefieldset"<?php if (get_user_meta($formUser, 'medconsdisabyesno', true) == "Yes" && get_user_meta($formUser, 'joiningas', true) == "Player" ) { ?> style="display:block;"<?php } else { echo " style='display:none'"; } ?>>
        <legend>Conditions or disabilities</legend>
        <p class="info">Please enter the details of your condition or disability, and any medication (e.g. tablets, inhalers or creams) you take for each condition, making sure to give drug names.  <em>When you fill an empty row, a new one will be added.</em></p>
        <table id="conditionsdisabilitiestable" class='center autoAddRow'>
            <thead>
                <tr>
                    <th>Condition or Disability</th>
                    <th>Medication</th>
                    <th>Dose and frequency</th>
                </tr>
            </thead>
            <tbody>
                <?php for ( $i = 1; $i == 1 || $i <= get_user_meta($formUser, 'condsdisablities_rowcount', true) +1; $i++ ) : ?>
                <tr class='clonerow'>
                    <td><input placeholder="Condition or Disability" class='tableInputs' name="condsdisablities_name_row<?php echo $i; ?>" type='text' <?php if ( get_user_meta($formUser, 'joined', true ) == true ) { ?> value="<?php echo get_user_meta($formUser, 'condsdisablities_name_row' . $i, true); } ?>" /></td>
                    <td><input placeholder="Medication" class='tableInputs' name="condsdisablities_drugname_row<?php echo $i; ?>" type='text' <?php if ( get_user_meta($formUser, 'joined', true ) == true ) { ?> value="<?php echo get_user_meta($formUser, 'condsdisablities_drugname_row' . $i, true); } ?>" /></td>
                    <td><input placeholder="Dose and frequency" class='tableInputs' name="condsdisablities_drugdose_freq_row<?php echo $i; ?>" type='text' <?php if ( get_user_meta($formUser, 'joined', true ) == true ) { ?> value="<?php echo get_user_meta($formUser, 'condsdisablities_drugdose_freq_row' . $i, true); } ?>" /></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </fieldset>
    <fieldset id="allergiesfieldset"<?php if (get_user_meta($formUser, 'allergiesyesno', true) == "Yes" && get_user_meta($formUser, 'joiningas', true) == "Player" ) { ?> style="display:block;"<?php } else { echo " style='display:none'"; } ?>>
        <legend>Allergies</legend>
        <p class="info">Please enter the details of your allergy, and any medication (e.g. tablets, inhalers, creams) you use for each, making sure to give drug names.</p>
        <table id="allergiestable" class='center autoAddRow'>
            <thead>
                <tr>
                    <th>Allergy</th>
                    <th>Medication</th>
                    <th>Dose and frequency</th>
                </tr>
            </thead>
            <tbody>
                <?php for ( $i = 1; $i == 1 || $i <= get_user_meta($formUser, 'allergies_rowcount', true) + 1; $i++ ) : ?>
                <tr class='clonerow'>
                    <td><input placeholder="Allergy" class='tableInputs' name="allergies_name_row<?php echo $i; ?>" type='text' <?php if ( get_user_meta($formUser, 'joined', true ) == true ) { ?> value="<?php echo get_user_meta($formUser, 'allergies_name_row' . $i, true); } ?>" /></td>
                    <td><input placeholder="Medication" class='tableInputs' name="allergies_drugname_row<?php echo $i; ?>" type='text' <?php if ( get_user_meta($formUser, 'joined', true ) == true ) { ?> value="<?php echo get_user_meta($formUser, 'allergies_drugname_row' . $i, true); } ?>" /></td>
                    <td><input placeholder="Dose and Frequency" class='tableInputs' name="allergies_drugdose_freq_row<?php echo $i; ?>" type='text' <?php if ( get_user_meta($formUser, 'joined', true ) == true ) { ?> value="<?php echo get_user_meta($formUser, 'allergies_drugdose_freq_row' . $i, true); } ?>" /></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </fieldset>
        <fieldset id="injuriesfieldset"<?php if (get_user_meta($formUser, 'injuredyesno', true) == "Yes" && get_user_meta($formUser, 'joiningas', true) == "Player" ) { ?> style="display:block;"<?php } else { echo " style='display:none'"; } ?>>
        <legend>Injuries</legend>
        <p class="info">Please list any injuries (e.g. concussion), indicating when they happened, who treated you (e.g. your doctor) and the current status of your injuries (e.g. whether they are fully recovered or not).</p>
        <table id="injuriestable" class='center autoAddRow'>
            <thead>
                <tr>
                    <th>Injury</th>
                    <th>When</th>
                    <th>Treatment Received</th>
                    <th>Treated By</th>
                    <th>Current Status</th>
                </tr>
            </thead>
            <tbody>
                <?php for ( $i = 1; $i == 1 || $i <= get_user_meta($formUser, 'injuries_rowcount', true) +1; $i++ ) : ?>
                <tr class='clonerow'>
                    <td><input placeholder="Injury" class='tableInputs' name="injuries_name_row<?php echo $i; ?>" type='text' <?php if ( get_user_meta($formUser, 'joined', true ) == true ) { ?> value="<?php echo get_user_meta($formUser, 'injuries_name_row' . $i, true); } ?>" /></td>
                    <td><input placeholder="When" class='tableInputs' name="injuries_when_row<?php echo $i; ?>" type='text' <?php if ( get_user_meta($formUser, 'joined', true ) == true ) { ?> value="<?php echo get_user_meta($formUser, 'injuries_when_row' . $i, true); } ?>" /></td>
                    <td><input placeholder="Treament " class='tableInputs' name="injuries_treatmentreceived_row<?php echo $i; ?>" type='text' <?php if ( get_user_meta($formUser, 'joined', true ) == true ) { ?> value="<?php echo get_user_meta($formUser, 'injuries_treatmentreceived_row' . $i, true); } ?>" /></td>
                    <td><input placeholder="Treated By" class='tableInputs' name="injuries_who_row<?php echo $i; ?>" type='text' <?php if ( get_user_meta($formUser, 'joined', true ) == true ) { ?> value="<?php echo get_user_meta($formUser, 'injuries_who_row' . $i, true); } ?>" /></td>
                    <td><input placeholder="Current Status" class='tableInputs' name="injuries_status_row<?php echo $i; ?>" type='text' <?php if ( get_user_meta($formUser, 'joined', true ) == true ) { ?> value="<?php echo get_user_meta($formUser, 'injuries_status_row' . $i, true); } ?>" /></td>

                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </fieldset>

    <fieldset>
        <legend>Health and Fitness Assessment</legend>
        <div>
            <label class="smalllabel" for="othersports">In which other sports or physical activities are you involved?</label>
            <input type="text" class="smalltextbox required" name="othersports" id="othersports" value='<?php echo get_user_meta($formUser, 'othersports', true) ?>' />
        </div>
        <div>
            <label class="smalllabel" for="hoursaweektrain">How many hours a week do you train?</label>
            <input type="number" class="smalltextbox required" name="hoursaweektrain" id="hoursaweektrain" value='<?php echo get_user_meta($formUser, 'hoursaweektrain', true) ?>' />
        </div>
        <div>
            <label class="smalllabel" for="playedbefore">Have you played rugby before?</label>
            <select name='playedbefore' id='playedbefore'>
                <option value="">Choose...</option>
	            <?php selectOptionFromMeta($formUser, 'playedbefore', 'Yes') ?>
	            <?php selectOptionFromMeta($formUser, 'playedbefore', 'No') ?>
            </select>
        </div>
        <div id="howmanyseasonsgroup"<?php if ( get_user_meta($formUser, 'joined', true ) == true && get_user_meta($formUser, 'playedbefore', true) == "Yes") { ?> style="display:block"<?php } ?>>
            <label class="smalllabel" for="whereandseasons">Where did you play and for how many seasons?</label>
            <input type="text" class="smalltextbox required" name="whereandseasons" id="whereandseasons" value='<?php echo get_user_meta($formUser, 'whereandseasons', true) ?>' />
        </div>
        <div>
            <label class="smalllabel" for="height">Height</label>
            <input type="text" class="smalltextbox required" name="height" id="height" value='<?php echo get_user_meta($formUser, 'height', true) ?>' />
            <p class="forminfo">Please make sure to indicate units</p>
        </div>
        <div>
            <label class="smalllabel" for="weight">Weight</label>
            <input type="text" class="smalltextbox required" name="weight" id="weight" value='<?php echo get_user_meta($formUser, 'weight', true) ?>' />
            <p class="forminfo">Please make sure to indicate units</p>

        </div>
    </fieldset>

    <fieldset>
        <legend>Cardiac Questionairre</legend>
        <p class="info">Please tick each box that applies to you.</p>
	    <div class="checkboxesContainer">
        <label for="fainting"><input type="checkbox" name="fainting" <?php if ( get_user_meta($formUser, 'fainting', true) == "on") { ?> checked="checked"<?php } ?> />Fainting</label>
        <label for="dizzyturns"><input type="checkbox" name="dizzyturns" <?php if ( get_user_meta($formUser, 'dizzyturns', true) == "on") { ?> checked="checked"<?php } ?>  />Dizzy Turns</label>
        <label for="breathlessness"><input type="checkbox" name="breathlessness" <?php if ( get_user_meta($formUser, 'breathlessness', true) == "on") { ?> checked="checked"<?php } ?>  />Breathlessness or more easily tired than team-mates</label>
        <label for="bloodpressure"><input type="checkbox" name="bloodpressure" <?php if ( get_user_meta($formUser, 'bloodpressure', true) == "on") { ?> checked="checked"<?php } ?>  />History of high blood pressure</label>
        <label for="diabetes"><input type="checkbox" name="diabetes" <?php if ( get_user_meta($formUser, 'diabetes', true) == "on") { ?> checked="checked"<?php } ?>  />Diabetes</label>
        <label for="palpitations"><input type="checkbox" name="palpitations" <?php if ( get_user_meta($formUser, 'palpitations', true) == "on") { ?> checked="checked"<?php } ?>  />Palpitations</label>
        <label for="chestpain"><input type="checkbox" name="chestpain" <?php if ( get_user_meta($formUser, 'chestpain', true) == "on") { ?> checked="checked"<?php } ?>  />Chest Pain or Tightness</label>
        <label for="suddendeath"><input type="checkbox" name="suddendeath" <?php if ( get_user_meta($formUser, 'suddendeath', true) == "on") { ?> checked="checked"<?php } ?>  />Sudden death in immediate family of anyone under 50</label>
        <label for="smoking"><input type="checkbox" id="smoking" name="smoking" <?php if ( get_user_meta($formUser, 'smoking', true) == "on") { ?> checked="checked"<?php } ?>  />Smoking </label>
	    </div>
		    <div id="howmanycigs"<?php if ( get_user_meta($formUser, 'joined', true ) == true && get_user_meta($formUser, 'smoking', true) == "On") { ?> style="display:block"<?php } ?>>
            <label class="smalllabel" for="howmanycigsperday">How many cigarettes do you smoke per day?</label>
            <input type="number" class="smalltextbox required" name="howmanycigsperday" id="weight" value='<?php echo get_user_meta($formUser, 'howmanycigsperday', true) ?>' />
        </div>
    </fieldset>
    </div>
    <fieldset>
        <legend>Other</legend>
        <div>
            <label for="howdidyouhear">How did you hear about The Bisons?</label>
            <textarea class='required' name="howdidyouhear" id="howdidyouhear"><?php if ( get_user_meta($formUser, 'joined', true ) == true ) { echo get_user_meta($formUser, 'howdidyouhear', true); } ?></textarea>
        </div>
        <div>
            <label for="whatcanyoubring">Is there anything you can bring to the Bisons?</label>
            <textarea name="whatcanyoubring" id="whatcanyoubring"><?php if ( get_user_meta($formUser, 'joined', true ) == true ) { echo get_user_meta($formUser, 'whatcanyoubring', true); } ?></textarea>
            <p class='forminfo'><strong>Optional</strong> The Bisons is run by a team of dedicated volunteers and we are always looking for people with useful skills that could make the team even better. This doesn't have to be rugby related, for example: perhaps you are good at numbers and might be a potential treasurer, or you have some serious marketing skills to help us get the club name out there.</p>
        </div>
    </fieldset>

    <fieldset>
        <legend>Declaration and submission</legend>
        <div class='checkboxesContainer'>
            <label class='checkboxlabel' for='codeofconduct'><input class='required' type="checkbox" name="codeofconduct" id="codeofconduct"<?php if ( get_user_meta($formUser, 'joined', true ) == true ) { ?> disabled='true' checked='checked' <?php } ?>/>
I wish to become a member of the Bisons and have read and agree to abide by the club <a href='<?php echo $GLOBALS['blog_info']['url'] ?>/players-area/code-of-conduct/'>code of conduct</a>.</label>
            <label class='checkboxlabel' for='photographicpolicy'><input class='required'  type="checkbox" name="photographicpolicy" id="photographicpolicy"<?php if ( get_user_meta($formUser, 'joined', true ) == true ) { ?> disabled='true' checked='checked' <?php } ?>/>
I have read and fully understand the club <a href='<?php echo $GLOBALS['blog_info']['url'] ?>/players-area/photographic-policy/'>photographic policy</a>.</label>
            <label class='checkboxlabel' for='physicalsport'><input class='required'  type="checkbox" name="physicalsport" id="physicalsport"<?php if ( get_user_meta($formUser, 'joined', true ) == true ) { ?> disabled='true' checked='checked' <?php } ?>/>
I understand that Rugby is a contact sport, and like all contact sports, players may be exposed to the risk of physical injury. Should injury occur, I understand that the club cannot accept responsibility for any injuries which arise.</label>
        </div>
	    <button type='submit'><?php if (get_user_meta($formUser, 'joined', true ) == true ) { echo "Save Changes"; } else { echo "Submit"; } ?></button>
    </fieldset>
    <?php if (get_user_meta($formUser, 'joined', true ) == true ) { ?><input type='hidden' name='form_id' value='<?php echo $form_id ?>' /><?php } ?>
    <input type='hidden' name='wp_form_id' value='membership_form' />
    <input type='hidden' name='nonce' value='<?php echo wp_create_nonce( 'wordpress_form_submit' ) ?>' />
</form>