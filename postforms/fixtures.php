<?php wp_enqueue_script('formvalidation'); ?>
<div id='custom-form'>
    <?php// wp_nonce_field( 'save_fixture_' . $_GET['post'] ) ?>
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="fixture-date">Date of fixture</label></th>
                <td>
                    <input type='date' class='notempty' name='fixture-date' value='<?php echo get_post_meta( $post->ID, 'fixture-date', true) ? date('Y-m-d', get_post_meta( $post->ID, 'fixture-date', true) ) : '';     ?>' />
                    <span class="description">What day is the fixture on? This needs to be entered in AMERICAN date format (this is a restriction of Wordpress, unfortunately.), so YYYY-MM-DD.</span>
                </td>
            </tr>
            <tr>
                <th><label for="text-date">Text date</label></th>
                <td>
                    <input type='text' name='text-date' value='<?php echo get_post_meta( $post->ID, 'text-date', true) ? date('Y-m-d', get_post_meta( $post->ID, 'text-date', true) ) : '';     ?>' />
                    <span class="description">You can use this field to fill in approximate dates (such as 'The start of July'). If you fill in this field, you will still need to fill in an exact date above so that the fixture can be ordered correctly, however it will not be displayed.</span>
                </td>
            </tr>
            <tr>
                <th><label for="fixture-kickoff-time">Kickoff (24 hour clock)</label></th>
                <td>
                    <input type='time' class='notempty' name='fixture-kickoff-time' value='<?php echo get_post_meta( $post->ID, 'fixture-kickoff-time', true) ? get_post_meta( $post->ID, 'fixture-kickoff-time', true) : "2:30 pm"; ?>' />
                    <span class="description">What time is the match kicking off? Please enter this in 24 hour format.</span>
                </td>
            </tr>
            <tr>
                <th><label for="fixture-player-arrival-time">Players to arrive (24 hour clock)</label></th>
                <td>
                   <input type='time' class='notempty' name='fixture-player-arrival-time' value='<?php echo get_post_meta( $post->ID, 'fixture-player-arrival-time', true) ? get_post_meta( $post->ID, 'fixture-player-arrival-time', true) : "12:30 pm"; ?>' />
                    <span class="description">What time should Bisons players arrive on site? Again, enter in 24 hour format.</span>
                </td>
            </tr>   
            <tr>
                <th><label for="fixture-opposing-team">Opposing team</label></th>
                <td>
                    <input type='text' class='notempty' class="regular-text" name='fixture-opposing-team' value='<?php echo get_post_meta( $post->ID, 'fixture-opposing-team', true) ?>' />
                    <span class="description">What team are we playing against?</span>
                </td>
            </tr>
            <tr>
                <th><label for="fixture-opposing-team-website-url">Team website</label></th>
                <td>
                    <input type='text'  class="regular-text" name='fixture-opposing-team-website-url' value='<?php echo get_post_meta( $post->ID, 'fixture-opposing-team-website-url', true) ?>' />
                    <span class="description">Does that team have a website? If so add it and it will show up on fixture listings and related blog posts. If not, just leave it blank.</span>
                </td>
            </tr>
            <tr>
                <th><label for="fixture-home-away">Home or Away</label></th>
                <td>
                    <label><input type='radio' name='fixture-home-away' value="Home"<?php if( get_post_meta( $post->ID, 'fixture-home-away', true) == "Home") echo " checked='checked'"; ?> />Home</label>                
                    <label><input type='radio' name='fixture-home-away' value="Away"<?php if( get_post_meta( $post->ID, 'fixture-home-away', true) == "Away") echo " checked='checked'"; ?> />Away</label>
                </td>
            </tr>
            <tr>
                <th><label for="fixture-facebook-event">Facebook event</label></th>
                <td>
                    <input type='text' class="regular-text"  name='fixture-facebook-event' value='<?php echo get_post_meta( $post->ID, 'fixture-facebook-event', true) ?>' />                
                    <span class="description">If you have created a Facebook page for this fixture, paste the <abbr title="Uniform Resource Locator (The web address)">url</abbr> into this box and a link will be included in fixture listings and relevant posts. If not, just leave it blank.</span>
                </td>
            </tr>
            <tr>
                <th><label for="fixture-address">Venue address</label></th>
                <td>
                    <textarea class="address-input small notempty" name='fixture-address'><?php echo get_post_meta( $post->ID, 'fixture-address', true) ?></textarea>
                    <span class="description">What is the address of the pitch where the fixture will be taking place?</span>
                </td>
            </tr>
            <tr class="map-row">
                <th><label>Map</label></th>
                <td>
                </td>
            </tr>
            <tr>
                <th scope="row">Visible</th>
                <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>Hide from blog</span></legend>
                    <label for="hide_from_blog">
<input name="hide_from_blog" type="checkbox" id="hide_from_blog" value="true" <?php if( get_post_meta( $post->ID, 'hide_from_blog', true)) echo "checked='checked'"; ?>>
Hide from blog</label>
                </fieldset>
                   <span class="description">If you tick this box, this fixture will only appear on the 'Fixtures' page and not on the blog.</span>
                </td>
            </tr>
            <tr>
                <th scope="row">Email Members</th>
                <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>Email</span></legend>
                    <label for="email_players">
<input name="email_players" type="checkbox" id="email_players" value="true" <?php if( get_post_meta( $post->ID, 'email_players', true) == "yes" || get_post_meta( $post->ID, 'email_players', true) == "" ) echo "checked='checked'"; ?>>
Email Players</label>
                </fieldset>
                   <span class="description">Check this box to send an email to all players letting them know about the new fixture. Note that this email will not be sent to those who have not yet filled in a membership form.</span>
                </td>
            </tr>

        </tbody>
    </table>
    


    <?php
            if( get_current_screen()->action !=  'add' ) { ?>

    <?php
            $is_there_results_query = new WP_Query(array(
                                            'post_type' => 'results',
                                            'posts_per_page' => -1,
                                            'meta_key' => 'parent-fixture',
                                            'meta_value' => $_GET['post']
                                            ));

            while( $is_there_results_query->have_posts()) : $is_there_results_query->the_post();
                    $result_id = get_the_id();
            endwhile;
            $results_button_label = $result_id 
                    ? "You have already recorded the result of this match in the database. Click on this button to edit it."
                    : "Click on this button to record the results of this match. Results entered via this button will be posted on both the blog and the fixtures page."; ?>

            <div class='bottom-buttons'>
            <p class='submit'>
                <input type='button' class='button button-large button-primary' id='match-result-button' value='<?php echo $result_id ? 'Edit match results' : 'Record match results'; ?>' />
                <span class="description"><?php echo $results_button_label; ?>
                <div class='spacer'></div>
             </p> 
                <input type="hidden" id="postid" value="<?php echo $post->ID; ?>" />
            </div>

    <?php } ?>
</div>