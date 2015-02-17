<div class="wrap">
      <h1>Fixtures</h1>
	<?php 
	$fixturesTable = new Fixtures_Table(); 
	$fixturesTable->prepare_items();
	$fixturesTable->display(); 
      ?>
</div>