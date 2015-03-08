<?php wp_enqueue_script('formvalidation'); ?>
<?php if(! isset ( $_GET['parent_post'] ) && $_SERVER['PHP_SELF'] == '/wp-admin/post-new.php') { ?>

    <p>Error: You cannot create a new match result from here - please use the button within the fixture editing screen.</p>

<?php } else {
    
    ?>
	<?php include_once(__DIR__ . '/../snippets/remove_blank_post_body_box.php');
	
		$parentpost = isset ( $_GET['parent_post'] ) ?  $_GET['parent_post'] : get_post_meta( $_GET['post'] , 'parent-fixture', true);
    	$oppteam = get_post_meta( $parentpost, 'fixture-opposing-team', true );
?>
    <div id='custom-form'>
        <table class="form-table">
            <tbody>
            <tr>
                <th class='team'><label>Bristol Bisons RFC</label></th>
                <td><input class='resultsField notempty' type='text' name='our-score' value='<?php echo get_post_meta( $post->ID, 'our-score', true) ?>' /></td>
            </tr>
            <tr>
                <th class='team'><label><?php echo $oppteam ?></label></th>
                <td><input class='resultsField notempty' type='text' name='their-score' value='<?php echo get_post_meta( $post->ID, 'their-score', true) ?>' /></td>
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
                   <span class="description">If you tick this box, this result will only appear on the 'Fixtures' page and not on the blog.</span>
                </td>
            </tr>
                <?php if( !current_user_can( 'advanced_posting_layout' ) ) : ?> 
        	<tr>
			<td class='formButtonCell' colspan='2'><input type="submit" name="publish" id="publish" class="button button-primary button-large resultsButton" value="Publish" accesskey="p"></div></td>
			</tr>
			<?php endif ?>


        </table>
    </div>
    <input type="hidden" name="parent-fixture" value="<?php echo $_GET['parent_post'] ? $_GET['parent_post'] : get_post_meta( $post->ID, 'parent-fixture', true); ?>" />
    <div class='clear'></div>
<?php } ?>
