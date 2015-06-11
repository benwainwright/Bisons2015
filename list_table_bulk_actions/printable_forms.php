<?php if ( isset ( $_POST['user_id'] ) ) : ?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Bristol Bisons RFC - Membership Information</title>
    <link rel='stylesheet' type='text/css' media='all' href='<?php echo bloginfo('template_directory') . '/stylesheets/printableforms.css?version=1.0.1' ?>' />
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,700' rel='stylesheet' type='text/css'>
    </head>
<body>
    <?php $_POST['confirm_action'] = 'true'; ?>
    <?php foreach ( $_POST['user_id'] as $user ) : ?>
        <section>
        <h2>Membership Information for <?php echo get_user_meta($user, 'firstname', true). ' '.get_user_meta($user, 'surname', true) ?> (<?php echo get_user_meta($user, 'joiningas', true) ?>)</h2>
        <fieldset>
            <legend>Personal Information</legend>
        <table>
            <tbody>
            <tr>
                <th>Name</th>
                <td><?php echo get_user_meta($user, 'firstname', true). ' '.get_user_meta($user, 'surname', true) ?></td>
            </tr>
            <?php if ( get_user_meta($user, 'gender', true) ) : ?>
            <tr>
                <th>Gender</th>
                <td><?php echo get_user_meta($user, 'gender', true) ?></td>
            </tr>
            <?php endif ?>
            <?php if ( get_user_meta($user, 'othergender', true) ) : ?>
            <tr>
                <th>Gender Details</th>
                <td><?php echo get_user_meta($user, 'othergender', true) ?></td>
            </tr>
            <?php endif ?>
            <tr>
                <th>DOB</th>
                <?php $dobTimestamp = mktime ( 0, 0, 1, get_user_meta($user, 'dob-month', true), get_user_meta($user, 'dob-day', true), get_user_meta($user, 'dob-year', true) ); ?>
                
                <td><?php echo date ('jS \o\f F, Y', $dobTimestamp ) ?> (<?php echo getage ( date ( 'n/j/Y', $dobTimestamp) ) ?>)</td>
            </tr>

            <?php if ( get_user_meta($user, 'email_addy', true) ) : ?>
            <tr>
                <th>Email</th>
                <td><?php echo get_user_meta($user, 'email_addy', true) ?></td>
            </tr>
            <?php endif ?>
            <?php if ( get_user_meta($user, 'contact_number', true) ) : ?>
            <tr>
                <th>Contact Number</th>
                <td><?php echo get_user_meta($user, 'contact_number', true) ?></td>
            </tr>
            <?php endif ?>
            </tbody>
        </table>
        </fieldset>
        
        <fieldset>
            <legend>Home Address</legend>
            <table>
                <tbody>
                    <?php if ( get_user_meta($user, 'streetaddyl1', true) ) : ?>
                    <tr>
                        <th>Line 1</th>
                        <td><?php echo get_user_meta($user, 'streetaddyl1', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_user_meta($user, 'streetaddyl2', true) ) : ?>
                    <tr>
                        <th>Line 2</th>
                        <td><?php echo get_user_meta($user, 'streetaddyl2', true) ?></td>
                    </tr>
                    <?php endif ?>
                   <?php if ( get_user_meta($user, 'streetaddytown', true) ) : ?>
                    <tr>
                        <th>Town</th>
                        <td><?php echo get_user_meta($user, 'streetaddytown', true) ?></td>
                    </tr>
                    <?php endif ?>
                   <?php if ( get_user_meta($user, 'postcode', true) ) : ?>
                    <tr>
                        <th>Postcode</th>
                        <td><?php echo get_user_meta($user, 'postcode', true) ?></td>
                    </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </fieldset>
        <fieldset>
            <legend>Next of Kin</legend>
            <table>
                <tbody>
                    <?php if ( get_user_meta($user, 'nokfirstname', true) ) : ?>
                    <tr>
                        <th>First Name</th>
                        <td><?php echo get_user_meta($user, 'nokfirstname', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_user_meta($user, 'noksurname', true) ) : ?>
                    <tr>
                        <th>Surname</th>
                        <td><?php echo get_user_meta($user, 'noksurname', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_user_meta($user, 'nokrelationship', true) ) : ?>
                    <tr>
                        <th>Relationship</th>
                        <td><?php echo get_user_meta($user, 'nokrelationship', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_user_meta($user, 'nokcontactnumber', true) ) : ?>
                    <tr>
                        <th>Contact Number</th>
                        <td><?php echo get_user_meta($user, 'nokcontactnumber', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_user_meta($user, 'sameaddress', true) == 'Yes') : ?>
                    <tr>
                        <th>Address</th>
                        <td>Lives at same address</td>
                    </tr>
                    <?php else : ?>
                        <?php if ( get_user_meta($user, 'nokstreetaddy', true)) : ?>

                        <tr>
                            <th>Address</th>
                            <td><?php echo get_user_meta($user, 'nokstreetaddy', true) ?></td>
                        </tr>
                        <?php endif ?>
                        <?php if ( get_user_meta($user, 'nokpostcode', true)) : ?>
                        <tr>
                            <th>Postcode</th>
                            <td><?php echo get_user_meta($user, 'nokpostcode', true) ?></td>
                        </tr>
                        <?php endif ?>
                    <?php endif ?>
                </tbody>
            </table>
        </fieldset>
        <?php if (get_user_meta($user, 'joiningas', true) == 'Player' ) : ?>
        <fieldset>
            <legend>Medical Declaration</legend>
            <table>
                <tbody>
                    <?php if ( get_user_meta($user, 'medconsdisabyesno', true) ) : ?>
                    <tr>
                        <th>Do you have any current medical conditions or disabilities?</th>
                        <td><?php echo get_user_meta($user, 'medconsdisabyesno', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_user_meta($user, 'allergiesyesno', true) ) : ?>
                    <tr>
                        <th>Do you have any allergies?</th>
                        <td><?php echo get_user_meta($user, 'allergiesyesno', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_user_meta($user, 'injuredyesno', true) ) : ?>
                    <tr>
                        <th>Have you ever been injured?</th>
                        <td><?php echo get_user_meta($user, 'injuredyesno', true) ?></td>
                    </tr>
                    <?php endif ?>      
                </tbody>
            </table>
        </fieldset>
        <?php if ( get_user_meta($user, 'medconsdisabyesno', true) == 'Yes') : ?>
            <fieldset>
                <legend>Medical Conditions/Disabilities</legend>
                <table>
                    <thead>
                        <tr>
                            <th>Condition or Disability</th>
                            <th>Medication</th>
                            <th>Dose and Frequency</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php for ( $i = 1; get_user_meta($user, 'condsdisablities_name_row' . $i, true); $i++ ) : ?>
                        <tr>
                            <td><?php echo get_user_meta($user, 'condsdisablities_name_row' . $i, true) ?></td>
                            <td><?php echo get_user_meta($user, 'condsdisablities_drugname_row' . $i, true) ?></td>
                            <td><?php echo get_user_meta($user, 'condsdisablities_drugdose_freq_row' . $i, true) ?></td>
                        </tr>
                    <?php endfor ?>
                    </tbody>
                </table>
            </fieldset>
        <?php endif ?>
        <?php if ( get_user_meta($user, 'allergiesyesno', true) == 'Yes') : ?>
            <fieldset>
                <legend>Allergies</legend>
                <table>
                    <thead>
                        <tr>
                            <th>Allergy</th>
                            <th>Medication</th>
                            <th>Dose and Frequency</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php for ( $i = 1; get_user_meta($user, 'allergies_name_row' . $i, true); $i++ ) : ?>
                        <tr>
                            <td><?php echo get_user_meta($user, 'allergies_name_row' . $i, true) ?></td>
                            <td><?php echo get_user_meta($user, 'allergies_drugname_row' . $i, true) ?></td>
                            <td><?php echo get_user_meta($user, 'allergies_drugdose_freq_row' . $i, true) ?></td>
                        </tr>
                    <?php endfor ?>
                    </tbody>
                </table>
            </fieldset>
        <?php endif ?>
        <?php if ( get_user_meta($user, 'injuredyesno', true) == 'Yes') : ?>
            <fieldset>
                <legend>Injuries</legend>
                <table>
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
                    <?php for ( $i = 1; get_user_meta($user, 'injuries_name_row' . $i, true); $i++ ) : ?>
                        <tr>
                            <td><?php echo get_user_meta($user, 'injuries_name_row' . $i, true) ?></td>
                            <td><?php echo get_user_meta($user, 'injuries_when_row' . $i, true) ?></td>
                            <td><?php echo get_user_meta($user, 'injuries_treatmentreceived_row' . $i, true) ?></td>
                            <td><?php echo get_user_meta($user, 'injuries_who_row' . $i, true) ?></td>
                            <td><?php echo get_user_meta($user, 'injuries_status_row' . $i, true) ?></td>
                        </tr>
                    <?php endfor ?>
                    </tbody>
                </table>
            </fieldset>
        <?php endif ?> 
        <fieldset>
            <legend>Health and Fitness Assessment</legend>
            <table>
                <tbody>
                    <?php if ( get_user_meta($user, 'othersports', true) ) : ?>
                    <tr>
                        <th>In which other sports or physical activities are you involved?</th>
                        <td><?php echo get_user_meta($user, 'othersports', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_user_meta($user, 'hoursaweektrain', true) ) : ?>
                    <tr>
                        <th>How many hours a week do you train?</th>
                        <td><?php echo get_user_meta($user, 'hoursaweektrain', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_user_meta($user, 'playedbefore', true) == "Yes" ) : ?>
                    <tr>
                        <th>Have you played rugby before?</th>
                        <td>Yes</td>
                    </tr>
                        <?php if ( get_user_meta($user, 'whereandseasons', true) ) : ?>
                        <tr>
                            <th>Where did you play and for how many seasons?</th>
                            <td><?php echo get_user_meta($user, 'whereandseasons', true) ?></td>
                        </tr>
                        <?php endif ?>
                    <?php else : ?>
                    <tr>
                        <th>Have you played rugby before?</th>
                        <td>No</td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_user_meta($user, 'height', true) ) : ?>
                    <tr>
                        <th>Height?</th>
                        <td><?php echo get_user_meta($user, 'height', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_user_meta($user, 'weight', true) ) : ?>
                    <tr>
                        <th>Weight?</th>
                        <td><?php echo get_user_meta($user, 'weight', true) ?></td>
                    </tr>
                    <?php endif ?> 
        
                </tbody>
            </table>
        </fieldset>      
            <?php
                    $conditions = array(); 
                    if ( get_user_meta($user, 'fainting' , true) == 'on' ) $conditions[] = 'Fainting';
                    if ( get_user_meta($user, 'dizzyturns' , true) == 'on' ) $conditions[] = 'Dizzy turns';
                    if ( get_user_meta($user, 'breathlessness' , true) == 'on' ) $conditions[] = 'Breathlessness or more easily tired than team-mates';
                    if ( get_user_meta($user, 'bloodpressure' , true) == 'on' ) $conditions[] = 'History of high blood pressure';
                    if ( get_user_meta($user, 'diabetes' , true) == 'on' ) $conditions[] = 'Diabetes';
                    if ( get_user_meta($user, 'palpitations' , true) == 'on' ) $conditions[] = 'Palpitations';
                    if ( get_user_meta($user, 'chestpain' , true) == 'on' ) $conditions[] = 'Chest pain or tightness';
                    if ( get_user_meta($user, 'suddendeath' , true) == 'on' ) $conditions[] = 'Sudden death in immediate family of anyone under fifty';
                    if ( get_user_meta($user, 'smoking' , true) == 'on' ) $conditions[] = 'Smoker';
                    $conditions = implode(', ', $conditions);
                    $conditions = $conditions ? $conditions : "No boxes ticked"; 
                    ?>
                    <fieldset>
                        <legend>Cardiac Questionnaire</legend>
                        <table>
                            <tbody>
                                <tr>
                                    <th>Which of the following apply to you?</th>
                                    <td><?php echo $conditions ?></td>
                                </tr>
                                <?php if ( get_user_meta($user, 'smoking' , true) == 'on' ) : ?>
                                <tr>
                                    <th>How many cigarettes do you smoke per day?</th>
                                    <td><?php echo get_user_meta($user, 'howmanycigsperday' , true) ?></td>
                                </tr>
                                <?php endif ?>
                        </table>
                    </fieldset> 
                <?php endif ?>
                    <fieldset>
                        <legend>Other</legend>
                        <table>
                            <tbody>
                               <?php if ( get_user_meta($user, 'howdidyouhear', true) ) : ?>
                                <tr>
                                    <th>How did you hear about The Bisons?</th>
                                    <td><?php echo get_user_meta($user, 'howdidyouhear', true) ?></td>
                                </tr>
                                <?php endif ?>
                               <?php if ( get_user_meta($user, 'whatcanyoubring', true) ) : ?>
                                <tr>
                                    <th>What can you bring to the Bisons?</th>
                                    <td><?php echo get_user_meta($user, 'whatcanyoubring', true) ?></td>
                                </tr>
                                <?php endif ?>
                               <?php if ( get_user_meta($user, 'topsize', true) ) : ?>
                                <tr>
                                    <th>Top Size</th>
                                    <td><?php echo get_user_meta($user, 'topsize', true) ?></td>
                                </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </fieldset>       
        </section>
    <?php endforeach ?>

</body>
</html>
<?php exit; endif ?>