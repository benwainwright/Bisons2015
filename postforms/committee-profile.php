<?php wp_enqueue_script('formvalidation'); 
$players = new WP_Query (array(
    'post_type' => 'playerprofile',
    'nopaging' => 'true',
));

$player_id = get_post_meta ( $post->ID, 'incumbent', true);



?>


<div id='custom-form'>
    <table class="form-table">
        <tbody>
            <?php if ($players->have_posts()) : ?>
            <tr>
                <th><label for="Incumbent">Incumbent</label></th>
                <td>
                    <select name="incumbent">
                        <option value="0">None</option>
                        <?php while ( $players->have_posts() ) : $players->the_post() ?>
                        <option <?php if ( get_the_id() == $player_id ) echo 'selected="selected"'; ?> value="<?php echo get_the_id(); ?>"><?php echo get_post_meta ( get_the_id(), 'name', true) ?> (<?php echo substr(get_post_meta ( get_the_id(), 'nickname', true), 0, 30) ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </td>

            </tr>
            <?php endif; ?>
            <tr>
                <th><label for="posname">Position Name</label></th>
                <td>
                    <input class='regular-text notempty'  type='text' name='posname' value='<?php echo get_post_meta( $post->ID, 'posname', true) ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="askme">Ask me about</label></th>
                <td>
                    <textarea name='askme' class='small notempty'><?php echo get_post_meta( $post->ID, 'askme', true) ?></textarea>
                </td>
            </tr>    
            <tr>
                <th><label for="summary">Summary</label></th>
                <td>
                    <textarea class='notempty' name='summary'><?php echo get_post_meta( $post->ID, 'summary', true) ?></textarea>
                </td>
            </tr>
            <tr>
                <th><label for="skills">Skills Required</label></th>
                <td>
                    <textarea class='notempty' name='skills'><?php echo get_post_meta( $post->ID, 'skills', true) ?></textarea>
                </td>
            </tr>      
            <tr>
                <th><label for="posresp">What you'll do</label></th>
                <td>
                    <textarea class='notempty' name='posresp'><?php echo get_post_meta( $post->ID, 'posresp', true) ?></textarea>
                </td>
            </tr>        
            <tr>
                <th><label for="posemail">Email</label></th>
                <td>
                    <input class='regular-text'  type='text' name='posemail' value='<?php echo get_post_meta( $post->ID, 'posemail', true) ?>' />
                </td>
            </tr>
            <tr>
                <th><label for="posphone">Phone</label></th>
                <td>
                    <input class='regular-text'  type='text' name='posphone' value='<?php echo get_post_meta( $post->ID, 'posphone', true) ?>' />
                </td>
            </tr>

        </tbody>
    </table>
</div>
            