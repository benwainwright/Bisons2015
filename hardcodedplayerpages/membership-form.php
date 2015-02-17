<?php
    wp_enqueue_script('dynamicforms');


$form_user = ( isset ( $_GET['player_id'] ) && current_user_can ('committee_perms') ) 
                ? $_GET['player_id'] : get_current_user_id();

// Load the previous form data if their is one
$current_form = new WP_Query ( array (
    'post_type' => 'membership_form',
    'posts_per_page' => 1,
    'orderby'   => 'date',
    'order'     => 'ASC',
    'author'    => $form_user
));

$userdata = get_userdata ( $form_user );
    
while ( $current_form->have_posts() ) 
{
        $current_form->the_post();
        $date = get_the_date();
        $form_id = get_the_id();
        $disabled = isset( $_POST['edit_details']) ? false : true; 
} 

// If there is a resource_id in the querystring, it must returning from Gocardless, so confirm the payment and then save the resource information if it confirms properly
if ( isset ( $_GET['resource_id'] ) )
{   
    $confirm_params = array(
      'resource_id'    => $_GET['resource_id'],
      'resource_type'  => $_GET['resource_type'],
      'resource_uri'   => $_GET['resource_uri'],
      'signature'      => $_GET['signature']
    );
    
    if (isset($_GET['state'])) {
      $confirm_params['state'] = $_GET['state'];
    }
    
    try { 
        $confirmed_resource = GoCardless::confirm_resource($confirm_params);
    }
    catch ( Exception  $error )
    {
        echo "GoCardless Error: $e->getMessage()";
    }
        
    if ( $confirmed_resource )
    {
        
        $state = explode ('+', $_GET['state']);
        $the_post = $state[0];
        $type = $state[1];
        $post_author = get_post_field ( 'post_author', $the_post );
        
        switch ( $type )
        {
            
            case "DD": 
                
                update_post_meta($the_post, 'payment_type', "Direct Debit" ); 
                $resource = GoCardless_Subscription::find($_GET['resource_id']);
                update_post_meta($the_post, 'payment_status', 7 );  // DD created, not yet taken payments
                
            break;
            
            case "SP": 
                update_post_meta($the_post, 'payment_type', "Single Payment" );
                $resource = GoCardless_Bill::find($_GET['resource_id']);
                update_post_meta($the_post, 'payment_status', 2 );  // Single payment pending         
            break;
            
        }
        
        // If user is a guest player, upgrade them
        if ( check_user_role( 'guest_player' ) )
        {
            $user = new WP_User($post_author);
            $user->remove_role( 'guest_player');
            $user->add_role( 'player');
        }
        update_post_meta($the_post, 'gcl_sub_id', $_GET['resource_id'] );
        update_post_meta($the_post, 'gcl_sub_uri', $_GET['resource_uri'] );
        update_post_meta($the_post, 'mem_name', $resource->name );
        update_post_meta($the_post, 'mem_status', 'Active' );
    }
    
}
  

if ( ! isset ( $disabled ) )
	$disabled = false;

if ( ! isset ( $form_id ) )
      $form_id = NULL;

if ( ! $disabled ) 
    wp_enqueue_script('formvalidation');

?>

<header>
<h2>Bristol Bisons RFC Membership Form </h2>
</header>

<?php global $gocardless_url; if ( isset ( $gocardless_url ) ) : ?>
<p class="flashmessage">In a moment, you will be redirected to a direct debit mandate form at GoCardless. Once you have finished setting up your payment information, you will be returned to this site. See you in a bit!</p>
<script type='text/javascript'> setTimeout(function(){ document.location = '<?php echo $gocardless_url ?>'; }, 3000); </script>

<?php elseif ($current_form->have_posts() && ! get_post_meta($form_id, 'gcl_sub_id', true ) ) : ?>
<p class="flashmessage">It looks like you submitted a membership form but were interrupted before you could setup a Direct Debit. Click <a href='<?php echo home_url('players-area/payment-information/') ?>'>here</a> to set one up.</p>

<?php endif ?>
<?php if ( isset ( $confirmed_resource ) ) : ?>
<p class="flashmessage">Congratulations! Your direct debit (or full payment) has now been setup - you should receive an email from GoCardless (our payment processor) very shortly. 
<?php endif ?>           
<?php if ( $current_form->have_posts() ) : ?>
<p><strong>Please note that it is your responsibility to ensure that the information supplied below (particularly medical information) remains up to date</strong>. You can return to this form and make changes at any time; to do so, scroll down to the bottom and click 'Edit Details'. When you have finished, click 'Save Changes' and the committee will be notified of any changes you have made.</p>
<?php else: ?>
<p>Please take a moment to fill out the form below. Note that all the information supplied will remain completely <strong>confidential</strong>. Should you have any questions about anything on this form, please contact the <strong>membership secretary</strong> using the contact details at the top of the <a href='<?php echo home_url ('/players-area/') ?>'>players area</a>...</p>
<?php endif; ?>
<ul class='invalidformerrors'>
    <?php foreach ( $errors as $error ) : ?>
    <li><?php echo $error ?></li>
    <?php endforeach ?>
</ul>
<form id='membershipform_payment' method="post" role="form">
    
    <?php if ($disabled) : ?>
    <input type="hidden" name="disabled" id="disabled" value="true" />
    <?php endif ?>
    <?php if  ( current_user_can ('committee_perms') ) : ?>
    <fieldset>
        <legend>Select Player</legend>
        <?php if ( is_numeric ( $_GET['player_id' ] ) ) : ?>
        <p class='info'>This is NOT YOUR MEMBERSHIP form. You can fill in someone else's form below or use the dropdown box below to return to your membership form.</p>
        <input type='hidden' name='form_belongs_to' value='<?php echo $_GET['player_id' ] ?>' />
        <?php else : ?>
        <p class='info'>This is your own membership form. As a committee member, you can use the dropdown box below to select and edit the membership form of another player.</p>
        <?php endif ?>
        <select id='committeeSelectPlayer'>
            <option value='me'>Me</option>
        <?php $users = get_users(); foreach ($users as $user) : ?>
            <option value='<?php echo $user->data->ID."'"; if (  $_GET['player_id' ] == $user->data->ID ) { echo " selected='selected'"; } ?>><?php echo $user->data->display_name ?></option>
        <?php endforeach ?>
        </select>
    </fieldset>
    <?php endif ?>
    <fieldset>
        <legend>Player or Supporter</legend>
        <div>
            <label>Joining as</label>
            <select class="mustselect" name='joiningas' id='joiningas' <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option></option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'joiningas', true) == "Player") { echo " selected='selected'"; } ?>>Player</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'joiningas', true) == "Supporter") { echo " selected='selected'"; } ?>>Supporter</option>
            </select>
            <p class='forminfo'>Please note that a supporter membership is specifically for those that want to support the team but do not want to play any rugby. If you will be playing with us, pleae make sure you choose 'player' here because we will need to take some details of your medical history for you as part of our duty of care.</p>
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Personal Details</legend>
        <div>
            <label class="smalllabel" for="firstname">First name</label>
            <input type="text" class="smalltextbox notempty" name="firstname" id="firstname"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'firstname', true) ?>'<?php } else { ?> value='<?php echo $userdata->user_firstname ?>'<?php } ?> />
        </div>
        <div>
            <label class="smalllabel" for="surname">Surname</label>
            <input type="text" class="smalltextbox notempty" name="surname" id="surname"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'surname', true) ?>'<?php }  else { ?> value='<?php echo $userdata->user_lastname ?>'<?php } ?> />
        </div>
        <div>
            <label>Gender</label>
            <select class="mustselect" name='gender' id='gender' <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option></option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'gender', true) == "Male") { echo " selected='selected'"; } ?>>Male</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'gender', true) == "Female") { echo " selected='selected'"; } ?>>Female</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'gender', true) == "Other") { echo " selected='selected'"; } ?>>Other</option>
            </select>
        </div>
        <div id="othergender"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'gender', true) == "Other") { ?> style="display:block"<?php } ?>>
            <label class="smalllabel" for="othergender">Other Gender Details</label>
            <input type="text" class="smalltextbox notempty" name="othergender" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'othergender', true) ?>'<?php } ?> />
            <p class="forminfo">As a fully inclusive rugby club, we completely recognise that a gender designation of 'male' or 'female' is far too simplistic for the real world. However, because we are a rugby team, we are bound by <a href='http://www.rfu.com/' title='RFU Website'>RFU</a> regulations which unfortunately are categorised in simple male/female terms. Please be aware therefore that only a person who self-identifies as 'male' in some way can play in 'male' rugby. Likewise, only a person who self-identifies as 'female' in some way can play in 'female' rugby.</p>
        </div>
        <div>
            <label class="smalllabel" for="dob">Date of Birth</label>
             <div class="inlinediv">
             <select class="norightmargin" id="dob-year" name="dob-day" <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                    <option value="0"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "0") { echo " selected='selected'"; } ?>></option>
                    <option value="1"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "1") { echo " selected='selected'"; } ?>>1st</option>
                    <option value="2"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "2") { echo " selected='selected'"; } ?>>2nd</option>
                    <option value="3"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "3") { echo " selected='selected'"; } ?>>3rd</option>
                    <option value="4"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "4") { echo " selected='selected'"; } ?>>4th</option>
                    <option value="5"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "5") { echo " selected='selected'"; } ?>>5th</option>
                    <option value="6"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "6") { echo " selected='selected'"; } ?>>6th</option>
                    <option value="7"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "7") { echo " selected='selected'"; } ?>>7th</option>
                    <option value="8"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "8") { echo " selected='selected'"; } ?>>8th</option>
                    <option value="9"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "9") { echo " selected='selected'"; } ?>>9th</option>
                    <option value="10"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "10") { echo " selected='selected'"; } ?>>10th</option>
                    <option value="11"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "11") { echo " selected='selected'"; } ?>>11th</option>
                    <option value="12"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "12") { echo " selected='selected'"; } ?>>12th</option>
                    <option value="13"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "13") { echo " selected='selected'"; } ?>>13th</option>
                    <option value="14"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "14") { echo " selected='selected'"; } ?>>14th</option>
                    <option value="15"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "15") { echo " selected='selected'"; } ?>>15th</option>
                    <option value="16"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "16") { echo " selected='selected'"; } ?>>16th</option>
                    <option value="17"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "17") { echo " selected='selected'"; } ?>>17th</option>
                    <option value="18"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "18") { echo " selected='selected'"; } ?>>18th</option>
                    <option value="19"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "19") { echo " selected='selected'"; } ?>>19th</option>
                    <option value="20"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "20") { echo " selected='selected'"; } ?>>20th</option>
                    <option value="21"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "21") { echo " selected='selected'"; } ?>>21st</option>
                    <option value="22"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "22") { echo " selected='selected'"; } ?>>22nd</option>
                    <option value="23"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "23") { echo " selected='selected'"; } ?>>23rd</option>
                    <option value="24"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "24") { echo " selected='selected'"; } ?>>24th</option>
                    <option value="25"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "25") { echo " selected='selected'"; } ?>>25th</option>
                    <option value="26"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "26") { echo " selected='selected'"; } ?>>26th</option>
                    <option value="27"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "27") { echo " selected='selected'"; } ?>>27th</option>
                    <option value="28"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "28") { echo " selected='selected'"; } ?>>28th</option>
                    <option value="29"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "29") { echo " selected='selected'"; } ?>>29th</option>
                    <option value="30"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "30") { echo " selected='selected'"; } ?>>30th</option>
                    <option value="31"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "31") { echo " selected='selected'"; } ?>>31st</option>
                </select>
             <select class="norightmargin" id="dob-year" name="dob-month" <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                    <option value="0"></option>
                    <option value="01"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "01") { echo " selected='selected'"; } ?>>January</option>
                    <option value="02"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "02") { echo " selected='selected'"; } ?>>February</option>
                    <option value="03"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "03") { echo " selected='selected'"; } ?>>March</option>
                    <option value="04"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "04") { echo " selected='selected'"; } ?>>April</option>
                    <option value="05"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "05") { echo " selected='selected'"; } ?>>May</option>
                    <option value="06"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "06") { echo " selected='selected'"; } ?>>June</option>
                    <option value="07"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "07") { echo " selected='selected'"; } ?>>July</option>
                    <option value="08"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "08") { echo " selected='selected'"; } ?>>August</option>
                    <option value="09"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "09") { echo " selected='selected'"; } ?>>September</option>
                    <option value="10"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "10") { echo " selected='selected'"; } ?>>October</option>
                    <option value="11"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "11") { echo " selected='selected'"; } ?>>November</option>
                    <option value="12"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "12") { echo " selected='selected'"; } ?>>December</option>
                </select>
            <select class="norightmargin" id="dob-year" name="dob-year" <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option value="0"></option>
                <?php for ($i = 1901; $i < 2014; $i++ ) : ?>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-year', true) == $i) { echo " selected='selected'"; } ?>><?php echo $i ?></option>
                <?php endfor ?>
            </select>
            </div>
        </div>
        <div>
            <label class="smalllabel" for="email_addy">Email</label>
            <input type="text" class="smalltextbox needemail" name="email_addy" id="email_addy"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'email_addy', true) ?>'<?php } else { ?> value='<?php echo $userdata->user_email ?>'<?php } ?> />
        </div>
        <div>
            <label class="smalllabel" for="contact_number">Contact Number</label>
            <input type="text" class="smalltextbox needphonenum" name="contact_number" id="contact_number"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'contact_number', true) ?>'<?php } ?> />
        </div>
    </fieldset>
    <fieldset>
        <legend>Home Address</legend>
        <div>
            <label  class="smalllabel" for="streetaddyl1">Line 1</label>
            <input type="text" class="smalltextbox notempty" name="streetaddyl1" id="streetaddyl1"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'streetaddyl1', true) ?>'<?php } ?> />
        </div>
        <div>
            <label  class="smalllabel" for="streetaddyl2">Line 2</label>
            <input type="text" class="smalltextbox" name="streetaddyl2" id="streetaddyl2"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'streetaddyl2', true) ?>'<?php } ?> />
        </div>
        <div>
            <label  class="smalllabel" for="streetaddytown">Town</label>
            <input type="text" class="smalltextbox" name="streetaddytown" id="streetaddytown"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'streetaddytown', true) ?>'<?php } ?> />
        </div>
        <div>
            <label  class="smalllabel" for="postcode">Postcode</label>
            <input type="text" class="smalltextbox needpostcode" name="postcode" id="postcode"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'postcode', true) ?>'<?php } ?> />
        </div>
    </fieldset>
    <fieldset>
        <legend>Next of Kin</legend>
        <p class="info">This person will be contacted in case of emergencies.</p>
        <div>
            <label class="smalllabel" for="nokfirstname">First name</label>
            <input type="text" class="smalltextbox notempty" name="nokfirstname" id="nokfirstname"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'nokfirstname', true) ?>'<?php } ?> />
        </div>
        <div>
            <label class="smalllabel" for="noksurname">Surname</label>
            <input type="text" class="smalltextbox notempty" name="noksurname" id="noksurname"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'noksurname', true) ?>'<?php } ?> />
        </div>
        <div>
            <label class="smalllabel" for="nokrelationship">Relationship</label>
            <input type="text" class="smalltextbox notempty" name="nokrelationship" id="nokrelationship"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'nokrelationship', true) ?>'<?php } ?> />
        </div>
       <div>
            <label class="smalllabel" for="nokcontactnumber">Phone Number</label>
            <input type="text" class="smalltextbox needphonenum" name="nokcontactnumber" id="nokcontactnumber"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'nokcontactnumber', true) ?>'<?php } ?> />
        </div>
        <div>
            <label>Lives at same address</label>
            <select name='sameaddress' id='sameaddress' <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option></option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'sameaddress', true) == "No") { echo " selected='selected'"; } ?>>No</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'sameaddress', true) == "Yes") { echo " selected='selected'"; } ?>>Yes</option>
            </select>
        </div>
        <div id="nokaddygroup"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'sameaddress', true) == "No") { ?> style="display:block"<?php } ?>>
            <div>
                <label for="nokstreetaddy">Street address</label>
                <textarea class='notempty' name="nokstreetaddy" id="nokstreetaddy"<?php if ( $disabled ) { ?> disabled='true'<?php } ?>><?php if ( $current_form->have_posts() ) { echo get_post_meta($form_id, 'nokstreetaddy', true); } ?></textarea>
            </div>
            <div>
                <label  class="smalllabel" for="nokpostcode">Postcode</label>
                <input type="text" class="smalltextbox needpostcode" name="nokpostcode" id="nokpostcode"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'nokpostcode', true) ?>'<?php } ?> />
            </div>
        </div>
    </fieldset>
    <div class="playersonly"<?php if ( get_post_meta($form_id, 'joiningas', true) == 'Supporter' ) echo ' style="display:none"' ?>>
    <fieldset>
        <legend>Medical Declaration</legend>
        <p class="info">Please answer the next few sections as accurately and honestly as you can. In the very rare event of some kind of injury occurring, this information will help insure that medical professionals are able to do their job properly. </p>
        <div>
            <label>Do you have any current medical conditions or disabilities?</label>
            <select class="mustselect" name='medconsdisabyesno' id='medconsdisabyesno' <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option></option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'medconsdisabyesno', true) == "No") { echo " selected='selected'"; } ?>>No</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'medconsdisabyesno', true) == "Yes") { echo " selected='selected'"; } ?>>Yes</option>
            </select>
            <p class="forminfo">For example, asthma, diabetes, epilepsy, anaemia, haemophilia, viral illness, etc.</p>
        </div>

        <div>
            <label>Do you have any allergies?</label>
            <select class="mustselect" name='allergiesyesno' id='allergiesyesno' <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option></option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'allergiesyesno', true) == "No") { echo " selected='selected'"; } ?>>No</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'allergiesyesno', true) == "Yes") { echo " selected='selected'"; } ?>>Yes</option>
            </select>
            <p class="forminfo">For example, bee-stings, peanut butter etc.</p>
        </div>
        <div>
            <label>Have you ever been injured?</label>
            <select class="mustselect" name='injuredyesno' id='injuredyesno' <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option></option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'injuredyesno', true) == "No") { echo " selected='selected'"; } ?>>No</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'injuredyesno', true) == "Yes") { echo " selected='selected'"; } ?>>Yes</option>
            </select>
            <p class="forminfo">For example, concussion or a broken rib.</p>
        </div>
    </fieldset>
    
    <fieldset id="conddisablefieldset"<?php if (get_post_meta($form_id, 'medconsdisabyesno', true) == "Yes" && get_post_meta($form_id, 'joiningas', true) == "Player" ) { ?> style="display:block;"<?php } else { echo " style='display:none'"; } ?>>
        <legend>Conditions or disabilities</legend>
        <p class="info">Please enter the details of your condition or disability, and any medication (e.g. tablets, inhalers or creams) you take for each condition, making sure to give drug names.</p>
        <table id="conditionsdisabilitiestable" class='center'>
            <thead>
                <tr>
                    <th>Condition or disability</th>
                    <th>Medication</th>
                    <th>Dose and frequency</th>
                </tr>
            </thead>
            <tbody>
                <?php for ( $i = 1; $i == 1 || $i <= get_post_meta($form_id, 'condsdisablities_rowcount', true); $i++ ) : ?>
                <tr class='clonerow'>
                    <td><input name="condsdisablities_name_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'condsdisablities_name_row' . $i, true); } ?>" /></td>
                    <td><input name="condsdisablities_drugname_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'condsdisablities_drugname_row' . $i, true); } ?>" /></td>
                    <td><input name="condsdisablities_drugdose_freq_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'condsdisablities_drugdose_freq_row' . $i, true); } ?>" /></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <?php if ( ! $disabled ) { ?>
        <button class="smallbutton removerow"<?php if ( get_post_meta($form_id, 'condsdisablities_rowcount', true) > 1 ) { ?> style='display:inline'<?php } ?>>Remove Row</button>
        <button class="smallbutton addrow">Add Row</button>
        <?php } ?>
    </fieldset>
    <fieldset id="allergiesfieldset"<?php if (get_post_meta($form_id, 'allergiesyesno', true) == "Yes" && get_post_meta($form_id, 'joiningas', true) == "Player" ) { ?> style="display:block;"<?php } else { echo " style='display:none'"; } ?>>
        <legend>Allergies</legend>
        <p class="info">Please enter the details of your allergy, and any medication (e.g. tablets, inhalers, creams) you use for each, making sure to give drug names.</p>
        <table id="allergiestable" class='center'>
            <thead>
                <tr>
                    <th>Allergy</th>
                    <th>Medication</th>
                    <th>Dose and frequency</th>
                </tr>
            </thead>
            <tbody>
                <?php for ( $i = 1; $i == 1 || $i <= get_post_meta($form_id, 'allergies_rowcount', true); $i++ ) : ?>
                <tr class='clonerow'>
                    <td><input name="allergies_name_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'allergies_name_row' . $i, true); } ?>" /></td>
                    <td><input name="allergies_drugname_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'allergies_drugname_row' . $i, true); } ?>" /></td>
                    <td><input name="allergies_drugdose_freq_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'allergies_drugdose_freq_row' . $i, true); } ?>" /></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <?php if ( ! $disabled ) { ?>
        <button class="smallbutton removerow"<?php if ( get_post_meta($form_id, 'allergies_rowcount', true) > 1 ) { ?> style='display:inline'<?php } ?>>Remove Row</button>
        <button class="smallbutton addrow">Add Row</button>
        <?php } ?>
    </fieldset>
        <fieldset id="injuriesfieldset"<?php if (get_post_meta($form_id, 'injuredyesno', true) == "Yes" && get_post_meta($form_id, 'joiningas', true) == "Player" ) { ?> style="display:block;"<?php } else { echo " style='display:none'"; } ?>>
        <legend>Injuries</legend>
        <p class="info">Please list any injuries (e.g. concussion), indicating when they happened, who treated you (e.g. your doctor) and the current status of your injuries (e.g. whether they are fully recovered or not).</p>
        <table id="injuriestable" class='center'>
            <thead>
                <tr>
                    <th>Injury</th>
                    <th>When</th>
                    <th>Treatment received</th>
                    <th>Who treated you</th>
                    <th>Current status of injury</th>
                </tr>
            </thead>
            <tbody>
                <?php for ( $i = 1; $i == 1 || $i <= get_post_meta($form_id, 'injuries_rowcount', true); $i++ ) : ?>
                <tr class='clonerow'>
                    <td><input name="injuries_name_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'injuries_name_row' . $i, true); } ?>" /></td>
                    <td><input name="injuries_when_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'injuries_when_row' . $i, true); } ?>" /></td>
                    <td><input name="injuries_treatmentreceived_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'injuries_treatmentreceived_row' . $i, true); } ?>" /></td>
                    <td><input name="injuries_who_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'injuries_who_row' . $i, true); } ?>" /></td>
                    <td><input name="injuries_status_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'injuries_status_row' . $i, true); } ?>" /></td>

                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <?php if ( ! $disabled ) { ?>            
        <button class="smallbutton removerow"<?php if ( get_post_meta($form_id, 'injuries_rowcount', true) > 1 ) { ?> style='display:inline'<?php } ?>>Remove Row</button>
        <button class="smallbutton addrow">Add Row</button>
        <?php } ?>
    </fieldset>
    
    <fieldset>
        <legend>Health and Fitness Assessment</legend>
        <div>
            <label class="smalllabel" for="othersports">In which other sports or physical activities are you involved?</label>
            <input type="text" class="smalltextbox notempty" name="othersports" id="othersports"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'othersports', true) ?>'<?php } ?>>
        </div>
        <div>
            <label class="smalllabel" for="hoursaweektrain">How many hours a week do you train?</label>
            <input type="text" class="smalltextbox notempty" name="hoursaweektrain" id="hoursaweektrain"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'hoursaweektrain', true) ?>'<?php } ?>>
        </div>
        <div>
            <label class="smalllabel" for="playedbefore">Have you played rugby before?</label>
            <select name='playedbefore' id='playedbefore' <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option></option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'playedbefore', true) == "No") { echo " selected='selected'"; } ?>>No</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'playedbefore', true) == "Yes") { echo " selected='selected'"; } ?>>Yes</option>
            </select>        
        </div>
        <div id="howmanyseasonsgroup"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'playedbefore', true) == "Yes") { ?> style="display:block"<?php } ?>>
            <label class="smalllabel" for="whereandseasons">Where did you play and for how many seasons?</label>
            <input type="text" class="smalltextbox notempty" name="whereandseasons" id="whereandseasons"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'whereandseasons', true) ?>'<?php } ?>>
        </div>
        <div>
            <label class="smalllabel" for="height">Height</label>
            <input type="text" class="smalltextbox notempty" name="height" id="height"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'height', true) ?>'<?php } ?>>
            <p class="forminfo">Please make sure to indicate units</p>
        </div>
        <div>
            <label class="smalllabel" for="weight">Weight</label>
            <input type="text" class="smalltextbox notempty" name="weight" id="weight"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'weight', true) ?>'<?php } ?>>
            <p class="forminfo">Please make sure to indicate units</p>

        </div>
    </fieldset>
    
    <fieldset>
        <legend>Cardiac Questionairre</legend>
        <p class="info">Please tick each box that applies to you.</p>
        <div>
        <label><input type="checkbox" name="fainting" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'fainting', true) == "on") { ?> checked="checked"<?php } ?> />Fainting</label>
        <label><input type="checkbox" name="dizzyturns" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'dizzyturns', true) == "on") { ?> checked="checked"<?php } ?>  />Dizzy Turns</label>
        <label><input type="checkbox" name="breathlessness" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'breathlessness', true) == "on") { ?> checked="checked"<?php } ?>  />Breathlessness or more easily tired than team-mates</label>
        <label><input type="checkbox" name="bloodpressure" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'bloodpressure', true) == "on") { ?> checked="checked"<?php } ?>  />History of high blood pressure</label>
        </div>
        <div>
        <label><input type="checkbox" name="diabetes" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'diabetes', true) == "on") { ?> checked="checked"<?php } ?>  />Diabetes</label>
        <label><input type="checkbox" name="palpitations" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'palpitations', true) == "on") { ?> checked="checked"<?php } ?>  />Palpitations</label>
        <label><input type="checkbox" name="chestpain" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'chestpain', true) == "on") { ?> checked="checked"<?php } ?>  />Chest Pain or Tightness</label>
        <label><input type="checkbox" name="suddendeath" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'suddendeath', true) == "on") { ?> checked="checked"<?php } ?>  />Sudden death in immediate family of anyone under 50</label>
        </div>
        <div>
        <label><input type="checkbox" id="smoking" name="smoking" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'smoking', true) == "on") { ?> checked="checked"<?php } ?>  />Smoking </label>
        </div>
        <div id="howmanycigs"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'smoking', true) == "On") { ?> style="display:block"<?php } ?>>
            <label class="smalllabel" for="howmanycigsperday">How many cigarettes do you smoke per day?</label>
            <input type="text" class="smalltextbox notempty" name="howmanycigsperday" id="weight"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'howmanycigsperday', true) ?>'<?php } ?> />
        </div>
    </fieldset>
    </div>
    <fieldset>
        <legend>Other</legend>
        <div>
            <label for="howdidyouhear">How did you hear about The Bisons?</label>
            <textarea class='notempty' name="howdidyouhear" id="howdidyouhear"<?php if ( $disabled ) { ?> disabled='true'<?php } ?>><?php if ( $current_form->have_posts() ) { echo get_post_meta($form_id, 'howdidyouhear', true); } ?></textarea>
        </div>
        <div>
            <label for="whatcanyoubring">Is there anything you can bring to the Bisons?</label>
            <textarea name="whatcanyoubring" id="whatcanyoubring"<?php if ( $disabled ) { ?> disabled='true'<?php } ?>><?php if ( $current_form->have_posts() ) { echo get_post_meta($form_id, 'whatcanyoubring', true); } ?></textarea>
            <p class='forminfo'><strong>Optional</strong> The Bisons is run by a team of dedicated volunteers and we are always looking for people with useful skills that could make the team even better. This doesn't have to be rugby related, for example: perhaps you are good at numbers and might be a potential treasurer, or you have some serious marketing skills to help us get the club name out there.</p>
        </div>
        <div>
            <label for="topsize">Top size</label>
            <select class='mustselect' name='topsize'<?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option></option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'topsize', true) == "Small") { echo " selected='selected'"; } ?>>Small</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'topsize', true) == "Medium") { echo " selected='selected'"; } ?>>Medium</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'topsize', true) == "Large") { echo " selected='selected'"; } ?>>Large</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'topsize', true) == "X-Large") { echo " selected='selected'"; } ?>>X-Large</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'topsize', true) == "XX-Large") { echo " selected='selected'"; } ?>>XX-Large</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'topsize', true) == "XXX-Large") { echo " selected='selected'"; } ?>>XXX-Large</option>

            </select>
            <p class='forminfo'>What size would you like your exclusive Bisons social top to be?</p>
        </div>
    </fieldset>
    <?php if ( ! $current_form->have_posts() ) : ?>
    <fieldset>
        <legend>Payment</legend>
        <p class="info">Please indicate how you will be paying your membership fees. Note that if you select either a direct debit or a single payment, saving this form will cause you to be redirected to another website in order to setup the direct debit. You will be returned here afterwards. If you have already paid, a committee member will need to manually approve your membership.</p>
        <div>
            <label class="smalllabel" for="paymethod">Payment Method</label>
            <select class="mustselect" name="paymethod" id="paymethod">
                <option></option>
                <option>Monthly Direct Debit</option>
                <option>Single Payment</option>
            </select>
        </div>
        <?php 
        $fees = new WP_Query ( array( 'post_type' => 'membership_fee', 'nopaging' => true ));
        while ( $fees->have_posts() ) 
        {
            $fees->the_post();
            
            $the_fee = array (
                'id'    => get_the_id(),
                'name' => get_post_meta( get_the_id(), 'fee-name', true),
                'initial-payment' => get_post_meta( get_the_id(), 'initial-payment', true),
                'amount' => get_post_meta( get_the_id(), 'fee-amount', true),
                'description' => get_post_meta( get_the_id(), 'fee-description', true)
            );
            
            
            if ( get_post_meta( get_the_id(), 'supporter-player', true) == 'Supporter' && get_post_meta( get_the_id(), 'fee-type', true) == "Monthly Direct Debit" )
            {
                  $supporterfees[ 'direct_debits' ] [ ] = $the_fee;
            }
            else if ( get_post_meta( get_the_id(), 'supporter-player', true) == 'Supporter' && get_post_meta( get_the_id(), 'fee-type', true) != "Monthly Direct Debit")
            {
			$supporterfees[ 'single_payments' ] [ ] = $the_fee;
            }
            else if ( get_post_meta( get_the_id(), 'supporter-player', true) == 'Player' && get_post_meta( get_the_id(), 'fee-type', true) == "Monthly Direct Debit")
            {
            	$playerfees[ 'direct_debits' ] [ ] = $the_fee;
            }
            else if ( get_post_meta( get_the_id(), 'supporter-player', true) == 'Player' && get_post_meta( get_the_id(), 'fee-type', true) != "Monthly Direct Debit")
            {
            	$playerfees[ 'single_payments' ] [ ] = $the_fee;
            }
            
        }
		?>
	  <div id="playerfees" class='playersonly'>
        <div id="playermempaymonthly" style="display:none" >
            <label class="smalllabel" for="playermembershiptypemonthly">Membership Type</label>
            <select class="mustselect" name="playermembershiptypemonthly" id="playermembershiptypemonthly">
                <option></option>
            <?php foreach ($playerfees[ 'direct_debits' ] as $fee) : ?>
                <option value="<?php echo $fee['id'] ?>"><?php echo $fee['name'] ?></option>
            <?php endforeach ?>
            </select>
             <ul class='feeslist'>
            <?php foreach ($playerfees[ 'direct_debits' ] as $fee) : ?><li><strong><?php echo $fee['name'] ?></strong><br />An initial payment of <?php echo pence_to_pounds ( $fee['initial-payment'] ) ?> and monthly payments of <?php echo pence_to_pounds ( $fee['amount'] ) ?>. <?php echo $fee['description'] ?></li><?php endforeach ?>
             </ul>
        </div>
        <div id="playermempaysingle" style="display:none" >
            <label class="smalllabel" for="playermembershiptypesingle">Membership Type</label>
            <select class="mustselect" name="playermembershiptypesingle" id="playermembershiptypesingle">
                <option></option>
            <?php foreach ($playerfees[ 'single_payments' ] as $fee) : ?>
                <option value="<?php echo $fee['id'] ?>"><?php echo $fee['name'] ?></option>
            <?php endforeach ?>
            </select>
           <ul class='feeslist'>
            <?php foreach ($playerfees[ 'single_payments' ] as $fee) : ?><li><strong><?php echo $fee['name'] ?></strong><br />A single payment of <?php echo pence_to_pounds ( $fee['initial-payment'] ) ?>. <?php echo $fee['description'] ?></li><?php endforeach ?>
             </ul>
        </div>
	</div>
          
	  <div id="supporterfees" class='supportersonly'>
        <div id="supportermempaymonthly" style="display:none" >
            <label class="smalllabel" for="supportermembershiptypemonthly">Membership Type</label>
            <select class="mustselect" name="supportermembershiptypemonthly" id="supportermembershiptypemonthly">
                <option></option>
            <?php foreach ($supporterfees[ 'direct_debits' ] as $fee) : ?>
                <option value="<?php echo $fee['id'] ?>"><?php echo $fee['name'] ?></option>
            <?php endforeach ?>
            </select>
            <ul class='feeslist'>
            <?php foreach ($supporterfees[ 'direct_debits' ] as $fee) : ?><li><strong><?php echo $fee['name'] ?></strong><br />An initial payment of <?php echo pence_to_pounds ( $fee['initial-payment'] ) ?> and monthly payments of <?php echo pence_to_pounds ( $fee['amount'] ) ?>. <?php echo $fee['description'] ?></li><?php endforeach ?>
             </ul>
        </div>
        <div id="supportermempaysingle" style="display:none" >
            <label class="smalllabel" for="supportermembershiptypesingle">Membership Type</label>
            <select class="mustselect" name="supportermembershiptypesingle" id="supportermembershiptypesingle">
                <option></option>
            <?php foreach ($supporterfees[ 'single_payments' ] as $fee) : ?>
                <option value="<?php echo $fee['id'] ?>"><?php echo $fee['name'] ?></option>
            <?php endforeach ?>
            </select>
            <ul class='feeslist'>
                  <?php foreach ($supporterfees[ 'single_payments' ] as $fee) : ?><li><strong><?php echo $fee['name'] ?></strong><br />A single payment of <?php echo pence_to_pounds ( $fee['initial-payment'] ) ?>. <?php echo $fee['description'] ?></li><?php endforeach ?>
                   </ul>
              </div>
	</div>
    </fieldset>
    <?php endif ?>
    <fieldset>
        <legend>Declaration and submission</legend>
        <div>
            <label class='checkboxlabel' for='codeofconduct'><input class='mustcheck' type="checkbox" name="codeofconduct" id="codeofconduct"<?php if ( $current_form->have_posts() ) { ?> disabled='true' checked='checked' <?php } ?>/>
I wish to become a member of the Bisons and have read and agree to abide by the club <a href='<?php echo $GLOBALS['blog_info']['url'] ?>/players-area/code-of-conduct/'>code of conduct</a>.</label>
        </div>
        <div>
            <label class='checkboxlabel' for='photographicpolicy'><input class='mustcheck'  type="checkbox" name="photographicpolicy" id="photographicpolicy"<?php if ( $current_form->have_posts() ) { ?> disabled='true' checked='checked' <?php } ?>/>
I have read and fully understand the club <a href='<?php echo $GLOBALS['blog_info']['url'] ?>/players-area/photographic-policy/'>photographic policy</a>.</label>
        </div>
        <div>
            <label class='checkboxlabel' for='physicalsport'><input class='mustcheck'  type="checkbox" name="physicalsport" id="physicalsport"<?php if ( $current_form->have_posts() ) { ?> disabled='true' checked='checked' <?php } ?>/>
I understand that Rugby is a contact sport, and like all contact sports, players may be exposed to the risk of physical injury. Should injury occur, I understand that the club cannot accept responsibility for any injuries which arise.</label>
        </div>
        <div>
            <?php if ( $disabled ) : ?>
            <button type='submit' name='edit_details' /><?php if ($current_form->have_posts() ) { echo "Edit Details"; } ?>
            <?php else : ?>
            <button type='submit'><?php if ($current_form->have_posts() ) { echo "Save Changes"; } else { echo "Submit"; } ?></button>
            <?php endif ?>       
         </div>
    </fieldset>
    <?php if ($current_form->have_posts() ) { ?><input type='hidden' name='form_id' value='<?php echo $form_id ?>' /><?php } ?>
    <input type='hidden' name='wp_form_id' value='membership_form' />
    <input type='hidden' name='nonce' value='<?php echo wp_create_nonce( 'wordpress_form_submit' ) ?>' />
</form>