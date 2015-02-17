<?php
if( isset( $_POST['verify'] ) )
{
    update_post_meta($_POST['verify'], 'paymentstatus', 2);
    
    $user = get_post_field ( 'post_author', $_POST['verify']);
    
    if ( check_user_role( 'guest_player',  $user  ) )
    {
        $user = new WP_User ( $user );
        $user->remove_role ( 'guest_player' );
        $user->add_role ( 'player' );
    }
} 
wp_enqueue_script( 'manage_players_js' );

    ?>
    <div class="wrap">

    <style type="text/css">
        td.noformcol {
            color: red;
            font-weight: bold;
            text-align:center; 
            font-size:1.2em;
        }
    </style>
<?php switch ($_GET['filter']) {
    case "fitnessmedical" : ?>
    <style type="text/css">
        th, td { display:none }
        th.medicalfitness, td.medicalfitness{ display: table-cell; }
        td.noformcol { display:table-cell; }
        td.noform.namecol { display:table-cell; }
        td.namecol, th.namecol { display:table-cell;}
        
    </style>
    <?php break; 
    case "admin" : ?>
    <style type="text/css">
        th, td { display:none }
        th.admin, td.admin{ display: table-cell; }
        td.noformcol { display:table-cell; }
        td.noform.namecol { display:table-cell;}
        td.namecol, th.namecol { display:table-cell;}

    </style>
    <?php break;
    case "all" : ?>
    <style type="text/css">
        td.namecol, th.namecol { display:none;}
        td.noform.namecol { display:table-cell;}
    </style>
    <?php 
    break;
    case "personaldetails" : 
    default: ?>
    <style type="text/css">
        th, td { display:none }
        th.personaldetails, td.personaldetails{ display: table-cell; }
        td.noformcol { display:table-cell; }
        td.noform.namecol { display:table-cell;}
    </style>
    <?php break; 
 } ?>

<h1>Player Management </h1>
<label>Filter Player Information</label>
<select id="filter">
<option<?php if ( $_GET['filter'] == 'personaldetails') echo ' selected="selected"'; ?>>Personal details</option>
<option<?php if ( $_GET['filter'] == 'fitnessmedical') echo ' selected="selected"'; ?>>Fitness and medical</option>
<option<?php if ( $_GET['filter'] == 'admin') echo ' selected="selected"'; ?>>Administration</option>
<option<?php if ( $_GET['filter'] == 'all') echo ' selected="selected"'; ?>>All</option>
</select>  
<p>Please find below personal details of all members gathered from membership forms submitted via the website. Note that this information is strictly <strong>confidential</strong> - please do not share with third parties without the explicit permission of the player in question.</p>
<?php
    global $wp_roles;

    $roles = $wp_roles->roles;
    foreach ( $roles as $key => $array ) {
        $users = get_users( array ( 'role' => $key ) );
        if ( sizeof ( $users ) )
        { 
            ?>
        <h2><?php echo  $array['name'] ?></h2>
        <table class='wp-list-table widefat playermanagement'>
            <thead>
                <tr>
                    <th class="namecol">Name</th>
                    <th class="personaldetails">Personal Details</th>
                    <th class="personaldetails">Contact Details</th>
                    <th class="personaldetails">Next of Kin</th>
                    <th class="medicalfitness">Conditions/Disabilities</th>
                    <th class="medicalfitness">Allergies</th>
                    <th class="medicalfitness">Injuries</th>
                    <th class="medicalfitness">Health and Fitness</th>
                    <th class="medicalfitness">Cardiac</th>
                    <th class="admin">Where did you hear about the Bisons?</th>
                    <th class="admin">Payment</th>
                    <th class="admin">Emails</th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 0; foreach ($users as $user) :
            
                      $current_form = new WP_Query ( array (
                         'post_type' => 'membership_form',
                         'posts_per_page' => 1,
                         'orderby'   => 'date',
                         'order'     => 'ASC',
                         'author'   => $user->data->ID
                         ) );
                         
                if ( ! $current_form->have_posts() ) : ?>
                <tr<?php if ($i % 2) { echo " class='alternate'"; } ?>>
                    <td class="noform namecol"><strong><?php echo $user->data->display_name ?></strong></td>
                    <td class="noformcol" colspan="10" class='noform'>No membership form submitted</td>
                    <td></td>
                </tr>
                <?php else : ?>
                        <tr<?php if ($i % 2) { echo " class='alternate'"; } ?>>

                    <?php while ( $current_form->have_posts() ) : $current_form->the_post();
                        $dob = get_post_meta(get_the_id(), 'dob-day', true).'/'.get_post_meta(get_the_id(), 'dob-month', true).'/'.get_post_meta(get_the_id(), 'dob-year', true);
                        $dobamerican = get_post_meta(get_the_id(), 'dob-month', true).'-'.get_post_meta(get_the_id(), 'dob-day', true).'-'.get_post_meta(get_the_id(), 'dob-year', true);
                     ?> 
                            <td class="namecol"><strong><?php echo get_post_meta(get_the_id(), 'firstname', true) ?> <?php echo get_post_meta(get_the_id(), 'surname', true) ?></strong></td>
                            <td class="personaldetails">
                                <ul>
                                    <li><strong>Name</strong><br /><?php echo get_post_meta(get_the_id(), 'firstname', true) ?> <?php echo get_post_meta(get_the_id(), 'surname', true) ?></strong></li>
                                    <li><strong>DOB</strong><br/><?php echo $dob ?> (<?php echo getage($dob) ?>)</li>
                                    <li><strong>Gender</strong><br /><?php echo get_post_meta(get_the_id(), 'gender', true) == "Other" ? get_post_meta(get_the_id(), 'othergender', true) : get_post_meta(get_the_id(), 'gender', true) ?></li>
                                </ul>
                            </td>
                            <td class="personaldetails">
                                <ul>
                                    <li><strong>Email</strong><br /><a href='mailto:<?php echo get_post_meta(get_the_id(), 'email_addy', true) ?>'><?php echo get_post_meta(get_the_id(), 'email_addy', true) ?></a></li>
                                    <li><strong>Telephone</strong><br /><?php echo get_post_meta(get_the_id(), 'contact_number', true) ?></li>
                                    <li><strong>Address</strong><br /><?php echo get_post_meta(get_the_id(), 'streetaddyl1', true) ?><br /><?php echo get_post_meta(get_the_id(), 'streetaddyl2', true) ?><br /><?php echo get_post_meta(get_the_id(), 'streetaddytown', true) ?><br /><?php echo get_post_meta(get_the_id(), 'postcode', true) ?></li>
                                </ul>
                            </td>
                            <td class="personaldetails">
                                <ul>
                                    <li><strong>Name</strong><br /><?php echo get_post_meta(get_the_id(), 'nokfirstname' , true) ?> <?php echo get_post_meta(get_the_id(), 'noksurname', true) ?> (<?php echo get_post_meta(get_the_id(), 'nokrelationship' , true) ?>)</li>
                                    <li><strong>Telephone</strong><br /><?php echo get_post_meta(get_the_id(), 'nokcontactnumber' , true) ?></li>
                                    <li><strong>Address</strong><br /><?php if (get_post_meta(get_the_id(), 'sameaddress' , true) ) echo "Same address"; else echo get_post_meta(get_the_id(), 'nokstreetaddy' , true).'<br />'.get_post_meta(get_the_id(), 'nokpostcode' , true)?></li>
                                </ul>
                            </td>
                            <td class="medicalfitness">
                                <?php if (get_post_meta(get_the_id(), 'medconsdisabyesno', true) == "No") { echo "<strong>None</strong>"; } else { ?> 
                                <ul>
                                <?php for ( $ii = 1; $ii == 1 || $ii <= get_post_meta(get_the_id(), 'condsdisablities_rowcount', true); $ii++ ) : ?>
                                    <li>
                                        <strong><?php echo get_post_meta(get_the_id(), 'condsdisablities_name_row' . $ii, true) ?></strong><br />
                                        <em>Medication:</em> <?php echo get_post_meta(get_the_id(), 'condsdisablities_drugname_row' . $ii, true) ?><br />
                                        <em>Dose/Frequency:</em> <?php echo get_post_meta(get_the_id(), 'condsdisablities_drugdose_freq_row' . $ii, true) ?>
                                    </li>
                                <?php endfor ?>
                                </ul>
                                <?php } ?>
                                
                            </td>
                            <td class="medicalfitness">
                            <?php if (get_post_meta(get_the_id(), 'allergiesyesno', true) == "No") { echo "<strong>None</strong>"; } else { ?>
                           <ul>
                                <?php for ( $ii = 1; $ii == 1 || $ii <= get_post_meta(get_the_id(), 'allergies_rowcount', true); $ii++ ) : ?>
                                    <li>
                                        <strong><?php echo get_post_meta(get_the_id(), 'allergies_name_row' . $ii, true) ?></strong><br />
                                        <em>Medication:</em> <?php echo get_post_meta(get_the_id(), 'allergies_drugname_row' . $ii, true) ?><br />
                                        <em>Dose/Frequency:</em> <?php echo get_post_meta(get_the_id(), 'allergies_drugdose_freq_row' . $ii, true) ?>
                                    </li>
                                <?php endfor ?>
                            </ul>
                            <?php } ?>
                            </td>
                            <td class="medicalfitness">
                            <?php if (get_post_meta(get_the_id(), 'injuredyesno', true) == "No") { echo "<strong>None</strong>"; } else { ?>
                            <ul>
                                <?php for ( $ii = 1; $ii == 1 || $ii <= get_post_meta(get_the_id(), 'injuries_rowcount', true); $ii++ ) : ?>
                                    <li>
                                        <strong><?php echo get_post_meta(get_the_id(), 'injuries_name_row' . $ii, true) ?></strong><br />
                                        <em>When:</em> <?php echo get_post_meta(get_the_id(), 'injuries_when_row' . $ii, true) ?><br />
                                        <em>Treatment received:</em> <?php echo get_post_meta(get_the_id(), 'injuries_treatmentreceived_row' . $ii, true) ?><br />
                                        <em>Who treated:</em> <?php echo get_post_meta(get_the_id(), 'injuries_who_row' . $ii, true) ?><br />
                                        <em>Status:</em> <?php echo get_post_meta(get_the_id(), 'injuries_status_row' . $ii, true) ?>
                                    </li>
                                <?php endfor ?>
                            </ul>
                            <?php } ?>
                            </td>

                            <td class="medicalfitness">
                                <ul>
                                    <li><strong>Other sports/activities</strong><br /><?php echo get_post_meta(get_the_id(), 'othersports' , true) ?></li>
                                    <li><strong>Training hours a week</strong><br /><?php echo get_post_meta(get_the_id(), 'hoursaweektrain' , true) ?></li>
                                    <li><strong>Played before</strong><br /><?php echo get_post_meta(get_the_id(), 'playedbefore' , true) ?></li>
                                    <?php if (get_post_meta(get_the_id(), 'playedbefore' , true) ) : ?>
                                    <li><strong>Where and number of seasons</strong><br /><?php echo get_post_meta(get_the_id(), 'whereandseasons' , true) ?></li>
                                    <?php endif ?>
                                    <li><strong>Height</strong><br /><?php echo get_post_meta(get_the_id(), 'height' , true) ?></li>
                                    <li><strong>Weight</strong><br /><?php echo get_post_meta(get_the_id(), 'weight' , true) ?></li>
                                </ul>
                            </td>
                            <td class="medicalfitness">
                                <?php
                                $conditions = array(); 
                                if ( get_post_meta(get_the_id(), 'fainting' , true) == 'on' ) $conditions[] = 'Fainting';
                                if ( get_post_meta(get_the_id(), 'dizzyturns' , true) == 'on' ) $conditions[] = 'Dizzy turns';
                                if ( get_post_meta(get_the_id(), 'breathlessness' , true) == 'on' ) $conditions[] = 'Breathlessness or more easily tired than team-mates';
                                if ( get_post_meta(get_the_id(), 'bloodpressure' , true) == 'on' ) $conditions[] = 'History of high blood pressure';
                                if ( get_post_meta(get_the_id(), 'diabetes' , true) == 'on' ) $conditions[] = 'Diabetes';
                                if ( get_post_meta(get_the_id(), 'palpitations' , true) == 'on' ) $conditions[] = 'Palpitations';
                                if ( get_post_meta(get_the_id(), 'chestpain' , true) == 'on' ) $conditions[] = 'Chest pain or tightness';
                                if ( get_post_meta(get_the_id(), 'suddendeath' , true) == 'on' ) $conditions[] = 'Sudden death in immediate family of anyone under fifty';
                                if ( get_post_meta(get_the_id(), 'smoking' , true) == 'on' ) $conditions[] = 'Smoker';
                                $conditionsstring = "";
                                for ( $ii = 0; $conditions[$ii]; $ii++ ) $conditionsstring .= ( $ii ? ', ' : null ).$conditions[$ii];
                                echo $conditionsstring ? $conditionsstring : "None"; 
                                ?>
                            </td>
                            <td class="admin">
                            <?php echo get_post_meta(get_the_id(), 'howdidyouhear' , true) ?>
                            </td>
                            <td class="admin">
                                <ul>
                                <?php 
                                $paymentstatus = get_post_meta(get_the_id(), 'paymentstatus', true);
                                switch ($paymentstatus) : 
                                    case 1: 
                                    ?><li><strong>Payment Method</strong><br />Manual</li><li><strong>Verified</strong><br />No</li><?php 
                                    break; 
                                    case 2: 
                                    ?><li><strong>Payment Method</strong><br />Manual</li><li><strong>Verified</strong><br />Yes</li><?php
                                    break;             
                                    case 3: 
                                    ?><li><strong>Payment Method</strong><br />Website</li><li><strong>Status</strong><br />Payment Successful</li><?php
                                    break;
                                    case 4:
                                    ?><li><strong>Payment Method</strong><br />Website</li><li><strong>Status</strong><br />Payment Error</li><?php
                                    break;
                                    
                                 endswitch; ?>
                                 <li><strong>Membership</strong> <?php
                                   switch (get_post_meta(get_the_id(), 'memtype', true)) : 
                                        case 7000: 
                                        ?><li>£70 - Full Membership</li><?php
                                        break; 
                                        case 4000: 
                                        ?><li>£40 - Concession Membership</li><?php
                                        break; 
                                     endswitch; ?>
                                 </li>
                                 <?php if ($paymentstatus == 1 || $paymentstatus == 4 ) : ?>
                                 <li><form method="post" action="/wp-admin/admin.php?page=players&filter=admin"><button name='verify' value="<?php echo get_the_id() ?>">Verify Payment</button></form></li>
                                 <?php endif ?>
                                </ul>
                            </td>
                            <td class="admin">
                                
                            </td>
                        </tr>
                    <?php endwhile ?>
                <?php endif ?>
            <?php $i++; endforeach ?>    
            </tbody>
        </table>
  <?php }
    }
?>


</div>