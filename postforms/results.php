<?php wp_enqueue_script('formvalidation'); ?>
<?php if(! isset ( $_GET['parent_post'] ) && $_SERVER['PHP_SELF'] == '/wp-admin/post-new.php') { ?>

    <p>Error: You cannot create a new match result from here - please use the button within the fixture editing screen.</p>

<?php } else {
    
    ?>
	<?php include_once(__DIR__ . '/../snippets/remove_blank_post_body_box.php');
	
		$parentpost = isset ( $_GET['parent_post'] ) ?  $_GET['parent_post'] : get_post_meta( $_GET['post'] , 'parent-fixture', true);
    	$oppteam = get_post_meta( $parentpost, 'fixture-opposing-team', true );
?>
	<style type='text/css'>
	#custom-form .resultsField 
	{
		 border: 1px solid #ddd;
  	     -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,.07);
  	     box-shadow: inset 0 1px 2px rgba(0,0,0,.07);
  		 background-color: #fff;
  		 color: #333;
  		 outline: 0;
  		 -webkit-transition: .05s border-color ease-in-out;
  		 transition: .05s border-color ease-in-out;
  		 width:80px;
  		 font-size:2em;
  		 text-align:center;
  		 font-weight:bold;
  		 margin:0 auto;
	}
	
	#custom-form .resultsButton { 
		float:right; 
		height:40px; 
		width:150px;
		margin-top:0.3em;
		font-size:1.5em;
	}
	
	.resultsButtonCell { 
		width:100%;
	}
	
	@media screen and (max-width: 782px) {
		#custom-form .resultsField, #custom-form .resultsButton  
		{	width:100%; }
		
		#custom-form .team { text-align:center;}
	}
		
	</style>
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
			<td class='resultsButtonCell' colspan='2'><input type="submit" name="publish" id="publish" class="button button-primary button-large resultsButton" value="Publish" accesskey="p"></div></td>
			</tr>
			<?php endif ?>


        </table>
    </div>
    <input type="hidden" name="parent-fixture" value="<?php echo $_GET['parent_post'] ? $_GET['parent_post'] : get_post_meta( $post->ID, 'parent-fixture', true); ?>" />
    <div class='clear'></div>
<?php } ?>
