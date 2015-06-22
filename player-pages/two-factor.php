<header>
	<h2>Two Factor Authentication</h2>
	<?php get_template_part( 'snippets/playerPage', 'menu' ) ?>
</header>
<?php get_template_part( 'snippets/playerPage', 'flashMessages' ) ?>
<p>Authentication has been setup successfully. To reset it, click the button below. Resetting will mean that your
	current code generator will no longer work.</p>
<form method="POST">
	<button type="submit">Reset</button>
	<input type='hidden' name='nonce' value='<?php echo wp_create_nonce( 'wordpress_form_submit' ) ?>'/>
	<input type='hidden' name='wp_form_id' value='reset_two_factor'/>
</form>