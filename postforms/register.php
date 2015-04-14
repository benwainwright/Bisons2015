<?php 
wp_enqueue_script('formvalidation');
wp_enqueue_script('chosen_init');
wp_enqueue_style('chosen_css');
?>

<div id='custom-form'>
    <table class="form-table">
        <tbody>
            <tr class='smallFormRow'>
            	<th><label for="register_date">Date</label></th>
                <td>
                    <input type='date' class="required" name='reg-date' value='<?php echo get_post_meta( $post->ID, 'reg-date', true) ? date('Y-m-d', get_post_meta( $post->ID, 'reg-date', true) ) : date('Y-m-d');     ?>' />
                </td>
            </tr>

				
			<tr>
            <th><label for="players_present">Present</label></th>
			<td>
			<?php $selected = get_post_meta ( $post->ID, 'players_present', false ) ?>
			
			    <select class='register_listbox' id='players_present' multiple="multiple" name="players_present[]">
			    	<option value='new'>New Player(s)...</option>
					<?php $users = get_users(); foreach ($users as $user) : ?>
					<option <?php if ( array_search ($user->data->ID, $selected) !== false ) echo "selected='selected' " ?>value='<?php echo $user->data->ID?>'><?php echo $user->data->display_name ?></option>
			        <?php endforeach; ?>
			    </select>
			</td>
			</tr>
			<tr>
            <th><label for="players_watching">Watching</label></th>
			<td>
				
			<?php $selected = get_post_meta ( $post->ID, 'players_watching', false ) ?>

			    <select class='register_listbox' id='players_watching' multiple="multiple" name="players_watching[]">
			    	<option value='new'>New Player(s)...</option>
					<?php $users = get_users(); foreach ($users as $user) : ?>
					<option <?php if ( array_search ($user->data->ID, $selected) !== false ) echo "selected='selected' " ?>value='<?php echo $user->data->ID?>'><?php echo $user->data->display_name ?></option>
			        <?php endforeach; ?>
			    </select>
			</td>
			</tr>
			<tr>
            <th><label for="players_coaching">Coaching</label></th>
			<td>
				
			<?php $selected = get_post_meta ( $post->ID, 'players_coaching', false ) ?>

			    <select class='register_listbox' id='players_coaching' multiple="multiple" name="players_coaching[]">
			    	<option value='new'>New Player(s)...</option>
					<?php $users = get_users(); foreach ($users as $user) : ?>
					<option <?php if ( array_search ($user->data->ID, $selected) !== false ) echo "selected='selected' " ?>value='<?php echo $user->data->ID?>'><?php echo $user->data->display_name ?></option>
			        <?php endforeach; ?>
			    </select>
			</td>
			</tr>

            <?php if( !current_user_can( 'advanced_posting_layout' ) ) : ?> 
        	<tr>
			<td class='formButtonCell' colspan='2'><input type="submit" name="publish" id="publish" class="button button-primary button-large resultsButton" value="Publish" accesskey="p"></div></td>
			</tr>
			<?php endif ?>

        </table>
    </div>