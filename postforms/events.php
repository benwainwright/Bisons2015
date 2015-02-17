<?php
$image_id = get_post_meta( $post->ID, 'image_id', true);
$image_url = wp_get_attachment_url( $image_id );
wp_enqueue_script('formvalidation');
wp_enqueue_script('dynamicforms');


?>
<div id='custom-form'>
    <table class="form-table">
        <tbody>
        <tr>
            <th scope="row">All Day</th>
            <td>
            <fieldset>
                <legend class="screen-reader-text"><span>All-Day Event</span></legend>
                <label for="allDay">
<input name="allDay" type="checkbox" id="allDay" value="true" <?php if( get_post_meta( $post->ID, 'allDay', true)) echo "checked='checked'"; ?>>
All-Day Event</label>
            </fieldset>
               <span class="description">If you tick this box, item will appear without a time on the website and as an all-day event on the iCal feed. If you do not tick this box, you <strong>must</strong>
 supply both a start and end time.</td>
        </tr>

        <tr>
            <th><label for="date">Starting Date</label></th>
            <td>
                <input id='date' type='date' name='date' class='notempty' value='<?php echo get_post_meta( $post->ID, 'date', true) ? date('Y-m-d', get_post_meta( $post->ID, 'date', true) ) : ''; ?>' />
                <span class="description">What day does the event start on?</span>
            </td>
        </tr>
        
        <tr<?php if( get_post_meta( $post->ID, 'allDay', true)) echo " style='display:none'" ?>>
            <th><label for="time">Starting Time (optional)</label></th>
            <td>
                <input class='notempty' type='time' id='time' name='time' value='<?php echo get_post_meta( $post->ID, 'time', true); ?>' />
                <span class="description">What time does the event start?</span>
            </td>
        </tr>
        <tr>
            <th><label for="enddate">End Date (optional)</label></th>
            <td>
                <input type='date' name='enddate' value='<?php echo get_post_meta( $post->ID, 'enddate', true) ? date('Y-m-d', get_post_meta( $post->ID, 'enddate', true) ) : ''; ?>' />
                <span class="description">If the event spans multiple days, what day does it finish on?</span>
            </td>
        </tr>

              
        <tr<?php if( get_post_meta( $post->ID, 'allDay', true)) echo " style='display:none'" ?>>
            <th><label for="endtime">End Time (optional)</label></th>
            <td>
                <input class='notempty' type='time' id='endtime' name='endtime' value='<?php echo get_post_meta( $post->ID, 'endtime', true); ?>' />
                <span class="description">What time does the event end?</span>
            </td>
        </tr>

        <tr>
            <th><label for="facebook-event">Facebook event (optional)</label></th>
            <td>
                <input type='text' name='facebook-event' value='<?php echo get_post_meta( $post->ID, 'facebook-event', true) ?>' />
                <span class="description">If you have created a Facebook page for this event, paste the <abbr title="Uniform Resource Locator (The web address)">url</abbr> into this box and a link will be included in event listings and relevant posts. If not, just leave it blank.</span>
            </td>
        </tr>

        <tr>
            <th><label for="address">Venue address</label></th>
            <td>
                <textarea class="address-input small notempty" name='address'><?php echo get_post_meta( $post->ID, 'address', true) ?></textarea>
                <span class="description">Where will the event be taking place? If you put an address that can be recognised by Google maps into this field, a Google map will be included in the event post</span>
            </td>
        </tr>
        <tr>
            <th><label for="photo">Image upload</label></th>
            <td>
                <input type="button" class="button button-large custom-image-upload-button" name='add-event-image' value='Upload image' />
                <input type="hidden" name="upload_image_id" id="upload_image_id" value="<?php echo $image_id; ?>" />
                <input type="button" class="button button-large custom-image-remove-button" name='remove-event-image' value='Remove image' style="<?php echo ( ! $image_id ? 'display:none;' : '' ); ?>" />
                <span class="description">Choose an image on your computer or from the Wordpress media gallery to insert into the event listing. <strong>Please use photographs only</strong>; clipart or photographs with a white background will not work well with this theme.</strong></span>
            </td>
        </tr>
        <tr>
            <th><label>Image</label></th>
            <td>
                <img id="image_canvas" src="<?php echo $image_url; ?>" />
                <span class="image_canvas_description"><?php if( ! $image_id ) echo $image_id; ?></span>
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
                   <span class="description">If you tick this box, this even will only appear on the 'Events' page and not on the blog.</span>
                </td>
            </tr>
            
            <tr>
                <th scope="row">iCal only</th>
                <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>iCal only</span></legend>
                    <label for="ical_only">
<input name="ical_only" type="checkbox" id="ical_only" value="true" <?php if( get_post_meta( $post->ID, 'ical_only', true)) echo "checked='checked'"; ?>>
iCal only</label>
                </fieldset>
                   <span class="description">If you tick this box, this event will appear in the iCal feed only and not the website.</span>
                </td>
            </tr>
            

        </tbody>
    </table>

    <div class="embed-map"></div>
</div>