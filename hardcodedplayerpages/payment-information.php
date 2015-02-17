<?php 

// Get inserted data from query
$data = $wp_query->query['bisons_data'];
$form_id = $data['form_details']['form_id'];
$date = $data['form_details']['date'];
$disabled = $data['form_details']['disabled'];


// Enqueue form Javascript
wp_enqueue_script('dynamicforms');
wp_enqueue_script('formvalidation');
?>
<header>
<h2>Payment Information</h2>
</header>
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


<?php if ( $data['has_gcl_subscription'] ) :  ?>
    <?php switch ( $data['gcl_resource']->status )  
    {
        case 'active': ?><p>Details of your current Direct Debit subscription can be found below. If you want to cancel your membership, you can use the button at the bottom of this page.</p><?php break;
        case 'cancelled': ?><p><strong>It looks like your Direct Debit subscription has been cancelled</strong>, either by yourself or by a member of the committee. If you would like to setup a new one, please scroll to the bottom of the page and select your new membership type.</p><?php break;
        case 'pending': case 'paid': ?><p>Details of your payment can be found below. If the payment has not yet been submitted to your bank, there will be a button below that you can use to cancel it if you wish.</p><?php break;
    } ?>
<table class='center'>
    <tbody>
        <tr>
            <th>Type</th>
            <td class='large-cell'><?php echo $data['gcl_resource']->name ?></td>
        </tr>

        <tr>
            <th>Description</th>
            <td><?php echo $data['gcl_resource']->description ?></td>
        </tr>

        <?php if ( $data['gcl_resource_type'] == 'subscription') : ?>
        <tr>
            <th>Payment Method</th>
            <td class='large-cell'>Direct Debit</td>
        </tr>
        <tr>
            <th>Setup Fee</th>
            <td class='large-cell'><?php echo '£'.number_format ( (int) $data['gcl_resource']->setup_fee, 2 ) ?></td>
        </tr>
        <tr>
            <th>Monthly Payment</th>
            <td class='large-cell'><?php echo '£'.number_format ( (int) $data['gcl_resource']->amount, 2 ) ?></td>
        </tr>
        <tr>
            <th>Subscription Status</th>
            <td>
                <?php if ($data['gcl_resource']->status == 'active') : ?>
                    <form action='<?php echo remove_query_arg( array ('nonce', 'flash' ) ) ?>' method='post' id="#cancel">
                    <input type='hidden' name='nonce' value='<?php echo wp_create_nonce('cancel_resource_' . $data['gcl_resource']->id) ?>' />
                    <input type='hidden' name='resource_type' value='sub' />
                    <?php if ( $_POST['cancel_membership'] == 'first_click' ) : ?>
                        <pclass='info'>Are you sure you want to cancel your Direct Debit?</p>
                        <button type='submit' name='cancel_membership' value='confirmed'>Yes</button>
                        <button type='submit' name='cancel_membership' value='no'>No</button>
                    <?php else : ?>
                        <button type='submit' name='cancel_membership' value='first_click'>Active (Click to cancel)</button>
                    <?php endif ?>
                    </form>
                <?php else : ?>
                    Cancelled
                <?php endif ?>
            </td>
        </tr>
        <?php else : ?>
        <tr>
            <th>Payment Method</th>
            <td class='large-cell'>Single Payment</td>
        </tr>
        <tr>
            <th>Payment Amount</th>
            <td class='large-cell'><?php echo '£'.number_format ( (int) $data['gcl_resource']->amount, 2 ) ?></td>
        </tr>
        <tr>
            <th>Payment Status</th>
            <td class='large-cell'>
                <?php if ($data['gcl_resource']->status == 'pending' && $data['gcl_resource']->can_be_cancelled == TRUE) : ?>
                    <form action='<?php echo remove_query_arg( array ('nonce', 'flash' ) ) ?>' method='post' id="#cancel">
                    <input type='hidden' name='nonce' value='<?php echo wp_create_nonce('cancel_resource_' . $data['gcl_resource']->id) ?>' />
                    <input type='hidden' name='resource_type' value='bill' />
                    <?php if ( $_POST['cancel_membership'] == 'first_click' ) : ?>
                        <pclass='info'>Are you sure you want to cancel this payment?</p>
                        <button type='submit' name='cancel_membership' value='confirmed'>Yes</button>
                        <button type='submit' name='cancel_membership' value='no'>No</button>
                    <?php else : ?>
                        <button type='submit' name='cancel_membership' value='first_click'>Pending (Click to cancel)</button>
                    <?php endif ?>
                    </form>
                <?php else : ?>
                    <?php echo ucfirst ( $data['gcl_resource']->status ) ?>
                <?php endif ?>                
            </td>
        </tr>
                
        <?php endif ?>
    </tbody>
</table>    
<?php endif ?>

<?php if ( ! $data['has_gcl_subscription'] || $data['gcl_resource']->status == 'cancelled' ) : ?>
<form method='post'>
    <?php if ( $data['gcl_resource']->status == 'cancelled' ) : ?>
    <p>To setup a new Direct Debit, choose a payment type from the box below followed by a membership type. <strong>When you click OK, you will be redirected to the GoCardless website to finish setting up your Direct Debit. Don&apos;t worry, they'll send you back to us afterward!</strong>
    <?php else : ?> 
    <p>It looks like you have submitted a membership form but have not yet finished setting up your Direct Debit. To complete this step, choose a payment type from the box below followed by a membership type. <strong>When you clik OK, you will be redirected to the GoCardless website to finish setting up your Direct Debit. Don&apos;t worry, they'll send you back to us afterward!</strong></p>
    <?php endif ?>
    

    <fieldset>
        <legend>Payment</legend>
        <p class='info'>Once you choose a payment type, the membership type box will appear.</p>
        <div>
            <label class="smalllabel" for="paymethod">Payment Type</label>
            <select class="mustselect" name="paymethod" id="paymethod">
                <option></option>
                <option>Monthly Direct Debit</option>
                <option>Single Payment</option>
            </select>
        </div>
      <?php if ( get_post_meta ( $form_id, 'joiningas', true ) == 'Player' ) : ?>
      <div id="playerfees" class='playersonly'>
        <div id="playermempaymonthly" style="display:none" >
            <label class="smalllabel" for="playermembershiptypemonthly">Membership Type</label>
            <select class="mustselect" name="playermembershiptypemonthly" id="playermembershiptypemonthly">
                <option></option>
            <?php foreach ($data['playerfees'][ 'direct_debits' ] as $fee) : ?>
                <option value="<?php echo $fee['id'] ?>"><?php echo $fee['name'] ?></option>
            <?php endforeach ?>
            </select>
             <ul class='feeslist'>
            <?php foreach ($data['playerfees'][ 'direct_debits' ] as $fee) : ?><li><strong><?php echo $fee['name'] ?></strong><br />An initial payment of <?php echo pence_to_pounds ( $fee['initial-payment'] ) ?> and monthly payments of <?php echo pence_to_pounds ( $fee['amount'] ) ?>. <?php echo $fee['description'] ?></li><?php endforeach ?>
             </ul>
        </div>
        
        <div id="playermempaysingle" style="display:none" >
            <label class="smalllabel" for="playermembershiptypesingle">Membership Type</label>
            <select class="mustselect" name="playermembershiptypesingle" id="playermembershiptypesingle">
                <option></option>
            <?php foreach ($data['playerfees'][ 'single_payments' ] as $fee) : ?>
                <option value="<?php echo $fee['id'] ?>"><?php echo $fee['name'] ?></option>
            <?php endforeach ?>
            </select>
           <ul class='feeslist'>
            <?php foreach ($data['playerfees'][ 'single_payments' ] as $fee) : ?><li><strong><?php echo $fee['name'] ?></strong><br />A single payment of <?php echo pence_to_pounds ( $fee['initial-payment'] ) ?>. <?php echo $fee['description'] ?></li><?php endforeach ?>
             </ul>
        </div>
    </div>
    <?php else :
    
     ?>
      <div id="supporterfees" class='supportersonly'>
        <div id="supportermempaymonthly" style="display:none" >
            <label class="smalllabel" for="supportermembershiptypemonthly">Membership Type</label>
            <select class="mustselect" name="supportermembershiptypemonthly" id="supportermembershiptypemonthly">
                <option></option>
            <?php foreach ($data['supporterfees'][ 'direct_debits' ] as $fee) : ?>
                <option value="<?php echo $fee['id'] ?>"><?php echo $fee['name'] ?></option>
            <?php endforeach ?>
            </select>
            <ul class='feeslist'>
            <?php foreach ($data['supporterfees'][ 'direct_debits' ] as $fee) : ?><li><strong><?php echo $fee['name'] ?></strong><br />An initial payment of <?php echo pence_to_pounds ( $fee['initial-payment'] ) ?> and monthly payments of <?php echo pence_to_pounds ( $fee['amount'] ) ?>. <?php echo $fee['description'] ?></li><?php endforeach ?>
             </ul>
        </div>
        <div id="supportermempaysingle" style="display:none" >
            <label class="smalllabel" for="supportermembershiptypesingle">Membership Type</label>
            <select class="mustselect" name="supportermembershiptypesingle" id="supportermembershiptypesingle">
                <option></option>
            <?php foreach ($data['supporterfees'][ 'single_payments' ] as $fee) : ?>
                <option value="<?php echo $fee['id'] ?>"><?php echo $fee['name'] ?></option>
            <?php endforeach ?>
            </select>
            <ul class='feeslist'>
                  <?php foreach ($data['supporterfees'][ 'single_payments' ] as $fee) : ?><li><strong><?php echo $fee['name'] ?></strong><br />A single payment of <?php echo pence_to_pounds ( $fee['initial-payment'] ) ?>. <?php echo $fee['description'] ?></li><?php endforeach ?>
                   </ul>
              </div>
        </div>
    <?php endif ?>
    <button type='submit'>OK</button>
    </fieldset>
    <input type='hidden' name='nonce' value='<?php echo wp_create_nonce( 'bisons_submit_new_dd_form_'.$form_id) ?>' />
</form>
<?php endif;