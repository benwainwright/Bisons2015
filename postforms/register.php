<?php wp_enqueue_script('formvalidation'); ?>
<?php wp_enqueue_script('dynamicforms'); ?>

<div id='custom-form'>
	<p>Use the form below to record attendance for this training session. </p>
    <table class="form-table">
        <tbody>
            <tr class='smallFormRow'>
                <td>
                    <input type='date' class="required" name='reg-date' value='<?php echo get_post_meta( $post->ID, 'reg-date', true) ? date('Y-m-d', get_post_meta( $post->ID, 'reg-date', true) ) : date('Y-m-d');     ?>' />
                </td>
            </tr>

			<?php 
			$index = 0; 
			while ( get_post_meta ( $post->ID, 'register_entry_player_' . $index  , true ) ) { ?>
				
			<tr class='smallFormRow'>

			<td>
			    <select  class='adminNotBlankaddNew' name="register_entry_player_<?php echo $index ?>">
			        <option></option>
					<?php $users = get_users(); foreach ($users as $user) : ?>
					<option <?php if ( get_post_meta($post->ID, 'register_entry_player_' . $index, true) == $user->data->ID ) echo 'selected="selected" ' ?>value='<?php echo $user->data->ID?>'><?php echo $user->data->display_name ?></option>
			        <?php endforeach; ?>
			    </select>
			    <select name="register_entry_status_<?php echo $index ?>">
			        <option></option>
			        <?php global $register_statuses;  foreach ( $register_statuses as $key => $status ) : ?>       
			        <option <?php if ( get_post_meta($post->ID, 'register_entry_status_' . $index, true) == $key ) echo 'selected="selected" ' ?>value="<?php echo $key ?>"><?php echo $status ?></option>
			        <?php endforeach; ?>
			    </select>
			</td>
			</tr>
			<?php 
			$index++;
			}  ?>
			            <tr class='smallFormRow'>
			            	
			<td>
			    <select class='adminNotBlankaddNew' name="register_entry_player_<?php echo $index ?>">
			        <option value="">Choose...</option>
			        <option value="new">New...</option>
					<?php $users = get_users(); foreach ($users as $user) : ?>
					<option <?php if ( get_post_meta($post->ID, 'register_entry_player_' . $index, true) == $user->data->ID ) echo 'selected="selected" ' ?>value='<?php echo $user->data->ID?>'><?php echo $user->data->display_name ?></option>
			        <?php endforeach; ?>
			    </select>
			    <select name="register_entry_status_<?php echo $index ?>">
			        <option></option>
			        <?php global $register_statuses;  foreach ( $register_statuses as $key => $status ) : ?>       
			        <option <?php if ( get_post_meta($post->ID, 'register_entry_status_' . $index, true) == $key ) echo 'selected="selected" ' ?>value="<?php echo $key ?>"><?php echo $status ?></option>
			        <?php endforeach; ?>
			    </select>			</td>
			</tr>
            <?php if( !current_user_can( 'advanced_posting_layout' ) ) : ?> 
        	<tr>
			<td class='formButtonCell' colspan='2'><input type="submit" name="publish" id="publish" class="button button-primary button-large resultsButton" value="Publish" accesskey="p"></div></td>
			</tr>
			<?php endif ?>

        </table>
    </div>