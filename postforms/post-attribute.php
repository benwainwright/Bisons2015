<?php 
$users = get_users();
if ($users) : ?>
<div id='custom-form'>
    <p>Use the box below to link this post to attribute this post to a different author.</p>
    <table class="form-table">
        <tbody>
            <tr>
                <th><label for="attr_user">Fixture</label></th>
                <td>
                    <select name="attr_user">
                        <?php foreach ($users as $user) : ?>
                        <option <?php if ( $post->post_author == $user->ID ) echo 'selected="selected"'; ?> value="<?php echo $user->ID; ?>"><?php echo $user->data->user_nicename ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type='hidden' name='current_author' value='<?php echo $post->post_author ?>' />
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php else : ?>
    <p>There doesn't seem to be any users? That can't be right...</p>
<?php endif;