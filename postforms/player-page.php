<div id='custom-form'>
    <table class="form-table">
        <tbody>
        <tr>

            <td>
                <input type='text' name='link' value='<?php echo get_post_meta( $post->ID, 'link', true) ?>' />
                <span class="description">If you put a URL into this box, the player's area will link directly here instead of to the content above.</span>
            </td>
        </tr>
        <tr>

            <td>
                <textarea name='description'><?php echo get_post_meta( $post->ID, 'description', true) ?></textarea>
                <span class="description">Write a short description of this page and it will be included in the committee area index page.</span>
            </td>
        </tr>
        </tbody>
    </table>
</div>