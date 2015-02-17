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
        <?php $user_form = new WP_Query ( array ( 'post_type' => 'membership_form', 'posts_per_page' => 1, 'orderby' => 'date', 'order'  => 'ASC', 'author' => $user )); ?>
        <?php while ( $user_form->have_posts() ) : $user_form->the_post(); ?>
        <section>
        <h2>Membership Information for <?php echo get_post_meta(get_the_id(), 'firstname', true). ' '.get_post_meta(get_the_id(), 'surname', true) ?> (<?php echo get_post_meta(get_the_id(), 'joiningas', true) ?>)</h2>
        <fieldset>
            <legend>Personal Information</legend>
        <table>
            <tbody>
            <tr>
                <th>Name</th>
                <td><?php echo get_post_meta(get_the_id(), 'firstname', true). ' '.get_post_meta(get_the_id(), 'surname', true) ?></td>
            </tr>
            <?php if ( get_post_meta(get_the_id(), 'gender', true) ) : ?>
            <tr>
                <th>Gender</th>
                <td><?php echo get_post_meta(get_the_id(), 'gender', true) ?></td>
            </tr>
            <?php endif ?>
            <?php if ( get_post_meta(get_the_id(), 'othergender', true) ) : ?>
            <tr>
                <th>Gender Details</th>
                <td><?php echo get_post_meta(get_the_id(), 'othergender', true) ?></td>
            </tr>
            <?php endif ?>
            <tr>
                <th>DOB</th>
                <?php $dobTimestamp = mktime ( 0, 0, 1, get_post_meta(get_the_id(), 'dob-month', true), get_post_meta(get_the_id(), 'dob-day', true), get_post_meta(get_the_id(), 'dob-year', true) ); ?>
                
                <td><?php echo date ('jS \o\f F, Y', $dobTimestamp ) ?> (<?php echo getage ( date ( 'n/j/Y', $dobTimestamp) ) ?>)</td>
            </tr>

            <?php if ( get_post_meta(get_the_id(), 'email_addy', true) ) : ?>
            <tr>
                <th>Email</th>
                <td><?php echo get_post_meta(get_the_id(), 'email_addy', true) ?></td>
            </tr>
            <?php endif ?>
            <?php if ( get_post_meta(get_the_id(), 'contact_number', true) ) : ?>
            <tr>
                <th>Contact Number</th>
                <td><?php echo get_post_meta(get_the_id(), 'contact_number', true) ?></td>
            </tr>
            <?php endif ?>
            </tbody>
        </table>
        </fieldset>
        
        <fieldset>
            <legend>Home Address</legend>
            <table>
                <tbody>
                    <?php if ( get_post_meta(get_the_id(), 'streetaddyl1', true) ) : ?>
                    <tr>
                        <th>Line 1</th>
                        <td><?php echo get_post_meta(get_the_id(), 'streetaddyl1', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_post_meta(get_the_id(), 'streetaddyl2', true) ) : ?>
                    <tr>
                        <th>Line 2</th>
                        <td><?php echo get_post_meta(get_the_id(), 'streetaddyl2', true) ?></td>
                    </tr>
                    <?php endif ?>
                   <?php if ( get_post_meta(get_the_id(), 'streetaddytown', true) ) : ?>
                    <tr>
                        <th>Town</th>
                        <td><?php echo get_post_meta(get_the_id(), 'streetaddytown', true) ?></td>
                    </tr>
                    <?php endif ?>
                   <?php if ( get_post_meta(get_the_id(), 'postcode', true) ) : ?>
                    <tr>
                        <th>Postcode</th>
                        <td><?php echo get_post_meta(get_the_id(), 'postcode', true) ?></td>
                    </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </fieldset>
        <fieldset>
            <legend>Next of Kin</legend>
            <table>
                <tbody>
                    <?php if ( get_post_meta(get_the_id(), 'nokfirstname', true) ) : ?>
                    <tr>
                        <th>First Name</th>
                        <td><?php echo get_post_meta(get_the_id(), 'nokfirstname', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_post_meta(get_the_id(), 'noksurname', true) ) : ?>
                    <tr>
                        <th>Surname</th>
                        <td><?php echo get_post_meta(get_the_id(), 'noksurname', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_post_meta(get_the_id(), 'nokrelationship', true) ) : ?>
                    <tr>
                        <th>Relationship</th>
                        <td><?php echo get_post_meta(get_the_id(), 'nokrelationship', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_post_meta(get_the_id(), 'nokcontactnumber', true) ) : ?>
                    <tr>
                        <th>Contact Number</th>
                        <td><?php echo get_post_meta(get_the_id(), 'nokcontactnumber', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_post_meta(get_the_id(), 'sameaddress', true) == 'Yes') : ?>
                    <tr>
                        <th>Address</th>
                        <td>Lives at same address</td>
                    </tr>
                    <?php else : ?>
                        <?php if ( get_post_meta(get_the_id(), 'nokstreetaddy', true)) : ?>

                        <tr>
                            <th>Address</th>
                            <td><?php echo get_post_meta(get_the_id(), 'nokstreetaddy', true) ?></td>
                        </tr>
                        <?php endif ?>
                        <?php if ( get_post_meta(get_the_id(), 'nokpostcode', true)) : ?>
                        <tr>
                            <th>Postcode</th>
                            <td><?php echo get_post_meta(get_the_id(), 'nokpostcode', true) ?></td>
                        </tr>
                        <?php endif ?>
                    <?php endif ?>
                </tbody>
            </table>
        </fieldset>
        <?php if (get_post_meta(get_the_id(), 'joiningas', true) == 'Player' ) : ?>
        <fieldset>
            <legend>Medical Declaration</legend>
            <table>
                <tbody>
                    <?php if ( get_post_meta(get_the_id(), 'medconsdisabyesno', true) ) : ?>
                    <tr>
                        <th>Do you have any current medical conditions or disabilities?</th>
                        <td><?php echo get_post_meta(get_the_id(), 'medconsdisabyesno', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_post_meta(get_the_id(), 'allergiesyesno', true) ) : ?>
                    <tr>
                        <th>Do you have any allergies?</th>
                        <td><?php echo get_post_meta(get_the_id(), 'allergiesyesno', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_post_meta(get_the_id(), 'injuredyesno', true) ) : ?>
                    <tr>
                        <th>Have you ever been injured?</th>
                        <td><?php echo get_post_meta(get_the_id(), 'injuredyesno', true) ?></td>
                    </tr>
                    <?php endif ?>      
                </tbody>
            </table>
        </fieldset>
        <?php if ( get_post_meta(get_the_id(), 'medconsdisabyesno', true) == 'Yes') : ?>
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
                    <?php for ( $i = 1; get_post_meta(get_the_id(), 'condsdisablities_name_row' . $i, true); $i++ ) : ?>
                        <tr>
                            <td><?php echo get_post_meta(get_the_id(), 'condsdisablities_name_row' . $i, true) ?></td>
                            <td><?php echo get_post_meta(get_the_id(), 'condsdisablities_drugname_row' . $i, true) ?></td>
                            <td><?php echo get_post_meta(get_the_id(), 'condsdisablities_drugdose_freq_row' . $i, true) ?></td>
                        </tr>
                    <?php endfor ?>
                    </tbody>
                </table>
            </fieldset>
        <?php endif ?>
        <?php if ( get_post_meta(get_the_id(), 'allergiesyesno', true) == 'Yes') : ?>
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
                    <?php for ( $i = 1; get_post_meta(get_the_id(), 'allergies_name_row' . $i, true); $i++ ) : ?>
                        <tr>
                            <td><?php echo get_post_meta(get_the_id(), 'allergies_name_row' . $i, true) ?></td>
                            <td><?php echo get_post_meta(get_the_id(), 'allergies_drugname_row' . $i, true) ?></td>
                            <td><?php echo get_post_meta(get_the_id(), 'allergies_drugdose_freq_row' . $i, true) ?></td>
                        </tr>
                    <?php endfor ?>
                    </tbody>
                </table>
            </fieldset>
        <?php endif ?>
        <?php if ( get_post_meta(get_the_id(), 'injuredyesno', true) == 'Yes') : ?>
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
                    <?php for ( $i = 1; get_post_meta(get_the_id(), 'injuries_name_row' . $i, true); $i++ ) : ?>
                        <tr>
                            <td><?php echo get_post_meta(get_the_id(), 'injuries_name_row' . $i, true) ?></td>
                            <td><?php echo get_post_meta(get_the_id(), 'injuries_when_row' . $i, true) ?></td>
                            <td><?php echo get_post_meta(get_the_id(), 'injuries_treatmentreceived_row' . $i, true) ?></td>
                            <td><?php echo get_post_meta(get_the_id(), 'injuries_who_row' . $i, true) ?></td>
                            <td><?php echo get_post_meta(get_the_id(), 'injuries_status_row' . $i, true) ?></td>
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
                    <?php if ( get_post_meta(get_the_id(), 'othersports', true) ) : ?>
                    <tr>
                        <th>In which other sports or physical activities are you involved?</th>
                        <td><?php echo get_post_meta(get_the_id(), 'othersports', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_post_meta(get_the_id(), 'hoursaweektrain', true) ) : ?>
                    <tr>
                        <th>How many hours a week do you train?</th>
                        <td><?php echo get_post_meta(get_the_id(), 'hoursaweektrain', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_post_meta(get_the_id(), 'playedbefore', true) == "Yes" ) : ?>
                    <tr>
                        <th>Have you played rugby before?</th>
                        <td>Yes</td>
                    </tr>
                        <?php if ( get_post_meta(get_the_id(), 'whereandseasons', true) ) : ?>
                        <tr>
                            <th>Where did you play and for how many seasons?</th>
                            <td><?php echo get_post_meta(get_the_id(), 'whereandseasons', true) ?></td>
                        </tr>
                        <?php endif ?>
                    <?php else : ?>
                    <tr>
                        <th>Have you played rugby before?</th>
                        <td>No</td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_post_meta(get_the_id(), 'height', true) ) : ?>
                    <tr>
                        <th>Height?</th>
                        <td><?php echo get_post_meta(get_the_id(), 'height', true) ?></td>
                    </tr>
                    <?php endif ?>
                    <?php if ( get_post_meta(get_the_id(), 'weight', true) ) : ?>
                    <tr>
                        <th>Weight?</th>
                        <td><?php echo get_post_meta(get_the_id(), 'weight', true) ?></td>
                    </tr>
                    <?php endif ?> 
        
                </tbody>
            </table>
        </fieldset>      
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
                                <?php if ( get_post_meta(get_the_id(), 'smoking' , true) == 'on' ) : ?>
                                <tr>
                                    <th>How many cigarettes do you smoke per day?</th>
                                    <td><?php echo get_post_meta(get_the_id(), 'howmanycigsperday' , true) ?></td>
                                </tr>
                                <?php endif ?>
                        </table>
                    </fieldset> 
                <?php endif ?>
                    <fieldset>
                        <legend>Other</legend>
                        <table>
                            <tbody>
                               <?php if ( get_post_meta(get_the_id(), 'howdidyouhear', true) ) : ?>
                                <tr>
                                    <th>How did you hear about The Bisons?</th>
                                    <td><?php echo get_post_meta(get_the_id(), 'howdidyouhear', true) ?></td>
                                </tr>
                                <?php endif ?>
                               <?php if ( get_post_meta(get_the_id(), 'whatcanyoubring', true) ) : ?>
                                <tr>
                                    <th>What can you bring to the Bisons?</th>
                                    <td><?php echo get_post_meta(get_the_id(), 'whatcanyoubring', true) ?></td>
                                </tr>
                                <?php endif ?>
                               <?php if ( get_post_meta(get_the_id(), 'topsize', true) ) : ?>
                                <tr>
                                    <th>Top Size</th>
                                    <td><?php echo get_post_meta(get_the_id(), 'topsize', true) ?></td>
                                </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </fieldset>       
        </section>
        <?php endwhile ?>
    <?php endforeach ?>

</body>
</html>
<?php exit; endif ?>