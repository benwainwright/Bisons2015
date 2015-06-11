<?php 
wp_enqueue_script('formvalidation');
wp_enqueue_script('chosen_init');
wp_enqueue_style('chosen_css');
?>

<div id='custom-form'>
	<p>Please <a href='<?php echo admin_url('admin.php?page=add-player') ?>'>add any new players</a> to the database before recording the register to ensure their attendance is recorded also.</p>
    <table class="form-table">
        <tbody>
            <tr class='smallFormRow'>
            	<th><label for="register_date">Date</label></th>
                <td>
                    <input type='date' class="required" name='reg-date' value='<?php echo get_post_meta( $post->ID, 'reg-date', true) ? date('Y-m-d', get_post_meta( $post->ID, 'reg-date', true) ) : date('Y-m-d');     ?>' />
	                <span class="description">The date of the training session.</span>

                </td>
            </tr>

			<tr>
            <th><label for="players_present">Present</label></th>
			<td>
			<?php $selected = get_post_meta ( $post->ID, 'players_present', false ) ?>
			
			    <select class='register_listbox' id='players_present' multiple="multiple" name="players_present[]">
					<?php $users = get_users(); foreach ($users as $user) : ?>
					<option <?php if ( array_search ($user->data->ID, $selected) !== false ) echo "selected='selected' " ?>value='<?php echo $user->data->ID?>'><?php echo $user->data->display_name ?></option>
			        <?php endforeach; ?>
			    </select>
				<span class="description">Players that attended and took part in the session.</span>
			</td>
			</tr>
			<tr>
            <th><label for="players_watching">Watching</label></th>
			<td>
				
			<?php $selected = get_post_meta ( $post->ID, 'players_watching', false ) ?>

			    <select class='register_listbox' id='players_watching' multiple="multiple" name="players_watching[]">
					<?php $users = get_users(); foreach ($users as $user) : ?>
					<option <?php if ( array_search ($user->data->ID, $selected) !== false ) echo "selected='selected' " ?>value='<?php echo $user->data->ID?>'><?php echo $user->data->display_name ?></option>
			        <?php endforeach; ?>
			    </select>
				<span class="description">Players that attended and didn't take part in the session.</span>
			</td>
			</tr>
			<tr>
            <th><label for="players_coaching">Coaching</label></th>
			<td>
				
			<?php $selected = get_post_meta ( $post->ID, 'players_coaching', false ) ?>

			    <select class='register_listbox' id='players_coaching' multiple="multiple" name="players_coaching[]">
					<?php $users = get_users(); foreach ($users as $user) : ?>
					<option <?php if ( array_search ($user->data->ID, $selected) !== false ) echo "selected='selected' " ?>value='<?php echo $user->data->ID?>'><?php echo $user->data->display_name ?></option>
			        <?php endforeach; ?>
			    </select>
				<span class="description">Players that were involved in coaching the session.</span>
			</td>
			</tr>

            <?php if( !current_user_can( 'advanced_posting_layout' ) ) : ?> 
        	<tr>
			<td class='formButtonCell' colspan='2'><input type="submit" name="publish" id="publish" class="button button-primary button-large resultsButton" value="Save" accesskey="p"></div></td>
			</tr>
			<?php endif ?>

        </table>
    </div>