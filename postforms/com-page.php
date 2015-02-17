<div id='custom-form'>
    <table class="form-table">
        <tbody>
        <tr>

            <td>
                <input type='text' name='description' value='<?php echo get_post_meta( $post->ID, 'description', true) ?>' />
                <span class="description">Write a short description of this page and it will be included in the committee area index page.</span>
            </td>
        </tr>
    </table>
</div>