<div class="wrap">
      <h2>Fixtures <a href="<?php echo admin_url( 'post-new.php?post_type=fixtures') ?>" class="add-new-h2">Add New</a></h2>
      <style type='text/css'>
      	@media screen and (max-width: 782px)
      	{
      		#postDate, .postDate, .column-postDate, #season, .season, .column-season { display:none; }
      	}
      </style>
	<?php 
	$fixturesTable = new Fixtures_Table(); 
	$fixturesTable->prepare_items();
	$fixturesTable->display(); 
      ?>
</div>