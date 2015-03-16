<div id='custom-form'>
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="website">Website</label></th>
                <td>
                    <input class='regular-text'  type='text' name='website' value='<?php echo get_post_meta( $post->ID, 'website', true) ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="homeaddress">Normal Home</label></th>
                <td>
                    <textarea name='homeaddress'><?php echo get_post_meta( $post->ID, 'homeaddress', true) ?></textarea>
                </td>
            </tr>    

		</tbody>
	</table>
</div>