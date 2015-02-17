<?php wp_enqueue_script('formvalidation');  ?>
<div id='custom-form'>
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="fee-name">Fee Name</label></th>
                <td>
                    <input class='notempty' type='text' name='fee-name' value='<?php echo get_post_meta( $post->ID, 'fee-name', true) ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="initial-payment">Initial Payment</label></th>
                <td>
                    <input class="small-text" type='text' name='initial-payment' value='<?php echo get_post_meta( $post->ID, 'initial-payment', true) ?>' />
                    <span class="description">This value should be in <strong>pence</strong>, so 7000 = £70 etc. Can be left blank, in which case the initial payment will be the same as the later payments.</span>
                </td>
            </tr>
            <tr>
                <th><label for="fee-amount">Fee Amount</label></th>
                <td>
                    <input class="small-text" type='text' name='fee-amount' value='<?php echo get_post_meta( $post->ID, 'fee-amount', true) ?>' />
                    <span class="description">This value should be in <strong>pence</strong>, so 7000 = £70 etc...</span>
                </td>
            </tr>
            <tr>
                <th><label for="fee-type">Payment Method</label></th>
                <td>
                    <select name="fee-type">
                        <option></option>
                        <option<?php if ( get_post_meta( get_the_id(), 'fee-type', true) == "Single Payment" ) echo ' selected="selected" '; ?>>Single Payment</option>
                        <option<?php if ( get_post_meta( get_the_id(), 'fee-type', true) == "Monthly Direct Debit" ) echo ' selected="selected" '; ?>>Monthly Direct Debit</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="supporter-player">Supporter or Player</label></th>
                <td>
                    <select name="supporter-player">
                        <option></option>
                        <option<?php if ( get_post_meta( get_the_id(), 'supporter-player', true) == "Player" ) echo ' selected="selected" '; ?>>Player</option>
                        <option<?php if ( get_post_meta( get_the_id(), 'supporter-player', true) == "Supporter" ) echo ' selected="selected" '; ?>>Supporter</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="fee-description">Description</label></th>
                <td>
                    <textarea class="address-input small notempty" name='fee-description'><?php echo get_post_meta( get_the_id(), 'fee-description', true) ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="fee-order">Order</label></th>
                <td>
                    <input class="small-text" type='text' name='fee-order' value='<?php echo get_post_meta( $post->ID, 'fee-order', true) ?>' />
                </td>
            </tr>
            <tr>
                <th scope="row">Approval</th>
                <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>Requires Approval by Committee Member</span></legend>
                    <label for="requires-approval">
<input name="requires-approval" type="checkbox" id="requires-approval" value="true" <?php if( get_post_meta( $post->ID, 'requires-approval', true)) echo "checked='checked'"; ?>>
Requires Approval by Committee Member</label>
                </fieldset>
                   <span class="description">If you tick this box, Players will not be set up on this payment scheme without approval from a committee member.</span>
                </td>
            </tr>
            <tr>
                <th scope="row">Fees tables</th>
                <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>Fees tables</span></legend>
                    <label for="fees-tables">
<input name="fees-tables" type="checkbox" id="fees-tables" value="true" <?php if( get_post_meta( $post->ID, 'fees-tables', true)) echo "checked='checked'"; ?>>
Hide from fees tables</label>
                </fieldset>
                   <span class="description">Fees are automatically displayed on public pages and also on emails sent to members. To avoid cluttering these tables with every slight membership variation, if you tick this box, the fee will only be displayed on the actual membership form.</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
