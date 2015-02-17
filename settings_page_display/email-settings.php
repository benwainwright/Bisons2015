<form method="post" action="admin.php?page=email-settings">
    <h2 class='title'>Email Settings</h2>
    <table class='form-table'>
        <tbody>
                <tr>
                    <th scope="row">
                        <label for='r'>Contact us notifications</label>
                    </th>
                    <td>
                        <fieldset>
        
                    <?php  
                    // Get a list of committee members and the committee admin and merge them together as one array
                    $committee_members = get_users( array('role' => 'committee_member') );
                    $committee_admin = get_users( array('role' => 'committee_admin' ) );
                    $committee = array_merge($committee_members, $committee_admin);
                    
                    // Loop through them and pou
                    for($i = 0; $committee[$i]; $i++) : 
                            $checked = get_user_meta( $committee[$i]->ID, 'get_contact_us_emails', true );
                    ?>            
                            <label for="contact-us-selection-<?php echo $i; ?>"><input type='radio' name='contact-us-selection' id='contact-us-selection-<?php echo $i; ?>'<?php if($checked === 'true') echo ' checked="checked"';  ?> value='<?php echo $committee[$i]->ID; ?>' /> <?php echo $committee[$i]->display_name; ?></label>
                            <br />    
                    <?php endfor; ?>
                        </fieldset>
                        <span class='description'>Select which committee member will receive email from the 'contact us' form on the club information page.</span>
                    </td>
         
            </tbody>
        </table>
        <p class='submit'>
            <input type='submit' name='submit' id='submit' class='button button-primary' value='Save Changes' />
        </p>
</form>