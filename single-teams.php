<?php get_header(); ?>
<div id="wrapper">

    <div id="pagecol">
        <div class='page'> 
<?php if ( isset ( $GLOBALS['bisons_flash_message'] ) ) : ?>
        <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
    <?php endif ?>      

        <?php if ( have_posts() ) : ?>
            <?php while (have_posts() ) : the_post(); ?>
                <header>
                    <h2><?php the_title(); ?></h2>
                </header>
                <?php 
			      if ( has_post_thumbnail() ) {         
			            the_post_thumbnail('large');
			      } 
				?>
				<h3>Details</h3>
				<h4 class='fa fa-info'>Description</h4><?php the_content() ?></li>
				<h4 class='fa  fa-home'>Address</h4><p><?php echo wpautop( get_post_meta( get_the_id(), 'homeaddress', true) ) ?></p>
				<p class='fa fa-link'><a href='<?php echo get_post_meta( get_the_id(), 'website', true) ?>'>Website</a></p>
				<section class='clearsection'>
					
					<?php 
		            	$fixtures_query = new WP_Query ( array(
						'post_type'	=> 'fixtures',
						'nopaging' => 'true',
						'meta_key' => 'fixture_team',
						'meta_value' => get_the_id() 
						) );
						
						$future_fixtures = array();
						$past_fixtures = array();
						
						while ( $fixtures_query->have_posts() ) {
							$fixtures_query->the_post();
						    // Reformat date and convert the date and time combined into a unix time
						    $unixdate = get_post_meta( get_the_id(), 'fixture-date', true );
						
						    $printdate = date( 'jS \o\f F Y' , $unixdate );
						    $time = get_post_meta( get_the_id(), 'fixture-kickoff-time', true );
						    $datetime = date( 'Y:m:d' , $unixdate ). ' '.$time.':00';
						    $datetimeunix = strtotime($datetime);
						    // Prepare fixtures array
						    $fixture = array(
						    	'opposing' => get_the_title(),
						        'id' => get_the_id(),
						        'date' => get_post_meta( get_the_id(), 'fixture-date', true ) ? $printdate : 'Date TBC',
						        'textdate' => get_post_meta( get_the_id(), 'text-date', true ),
						        'page' => get_permalink(),
						        'edit_link' => '<a class="editsmall" href="'.get_edit_post_link( get_the_id() ).'">Edit fixture</a>',
						        'homeaway' => get_post_meta(get_the_id(), 'fixture-home-away', true)
						    );
							if( $datetimeunix > time() ) $future_fixtures[] = $fixture;
    						else $past_fixtures[] = $fixture;
							
						}
						?>
						
						
					<?php if ( ( sizeof ( $future_fixtures) > 0 ) ||  ( sizeof ( $past_fixtures) > 0 ) ) : ?>

					<h3>Fixtures</h3>
					<p>All the fixtures which involve this team, including the fixture results, are listed below. If you think any of the information is innaccurate, please get in touch.</p>
					<?php if ( sizeof ( $future_fixtures) > 0 ) : ?>
					<h4>Upcoming</h4>
					<table class='center fixturestable'>
						<tbody>
						    <?php foreach($future_fixtures as $future_fixture) : ?>
    	
			    			<tr>
			    				<td class="datecol"><a href="<?php echo $future_fixture['page']; ?>"><?php echo $future_fixture['textdate'] ? $future_fixture['textdate'] : $future_fixture['date'] ?></a></td>
			    				<td class="homeawaycol"><?php echo $future_fixture['homeaway'] ?></td>
			    			</tr>
						</tbody>

    <?php endforeach; ?>

					</table>
					<?php endif ?>
					
					<?php if ( sizeof ( $past_fixtures) > 0 ) : 
					    // Create match results query
					    $getresultsquery = new WP_Query(array(
					    'post_type' => 'results',
						'nopaging' => 'true'
    					));
						
					    // Loop over results, store in an array
					    while($getresultsquery->have_posts()) : $getresultsquery->the_post();
					        $results[] = array(
					                        'parent-fixture' => get_post_meta(get_the_id(), 'parent-fixture', true),
					                        'their-score'    => get_post_meta(get_the_id(), 'their-score', true),
					                        'our-score'      => get_post_meta(get_the_id(), 'our-score', true),
					                        'edit-result-link'      => "<a class='editsmall' href='".get_edit_post_link( get_the_id() )."'>Edit result</a>"
					        );
					    endwhile;

					    // Create match reports query;
					    $linked_posts_query = new WP_Query(array(
					        'post_type' => array ( 'post', 'photos' ),
					        'nopaging' => 'true', 'meta_query' => array ( 
					            'relation' => 'AND',
					            array(
					                'key' => 'fixture_id',
					                'compare' => 'EXISTS' ),
					            array(
					                'key' => 'fixture_id',
					                'compare' => '!=' ,
					                'value' => '0') )
					    ));
	
					    // Loop over linked posts, store in an array
					    while($linked_posts_query->have_posts()) : $linked_posts_query->the_post();
					        $linked_posts[] = array(
					            'id' => get_the_id(),
					            'parent-fixture' => get_post_meta(get_the_id(), 'fixture_id', true),
					            'link' => get_permalink(get_the_id()),
					            'title' => get_the_title(get_the_id()),
								'class' => ( get_post_type( get_the_id() ) == 'photos' ) ? 'fa fa-picture-o' : 'fa fa-file'
					        );
					    endwhile;



				    /* Loop through fixtures array and change the unix times back to a date string.
				     * Also if any of the results or reports parent_fixture matches the id, insert results/reports into the details of the old fixture
				     */
				    $match_report_col_on = false;
				    $edit_col_on = false; 
					$linked_post_on = false;
				
				    foreach($past_fixtures as &$past_fixture) :
				
				        foreach($results as $result) :
				            if($past_fixture['id'] == $result['parent-fixture']) :
				                $past_fixture['their-score'] = $result['their-score'];
				                $past_fixture['our-score'] = $result['our-score'];
				                $past_fixture['edit-result-link'] = $result['edit-result-link'];
				            endif;
				        endforeach;
				
				        foreach($linked_posts as $linked_post) :
				            if($past_fixture['id'] == $linked_post['parent-fixture']) :
				                $past_fixture['linked_posts'][] = $linked_post;
								$linked_post_on = true;
				            endif;
				        endforeach;
				        if(get_edit_post_link( $past_fixture['id'] ) ) $edit_col_on = true; 
				    endforeach;
				        
				    $past_fixtures = array_reverse( $past_fixtures );
						
						?>
					<h4>Previous</h4>
					<table class='resultsTable center'>
						<tbody>
							<?php foreach($past_fixtures as $past_fixture_print) : 
							        $fixdate = $past_fixture_print['date'];
        							$opposing = $past_fixture_print['opposing'];
         $past_fixture_print['edit-result-link'] = isset ( $past_fixture_print['edit-result-link'] ) 
                ? $past_fixture_print['edit-result-link'] 
                : "<a href='/wp-admin/post-new.php?post_type=results&parent_post=".$past_fixture_print['id']."'>Result</a>";

        ?>
        <tr>
        	<td class="datecol"><?php echo  $fixdate; ?></td>
        	<td><?php echo ($past_fixture_print['homeaway'] == "Home") ? "Bristol Bisons RFC" :  $opposing ?></td>
        	<?php if (isset ( $past_fixture_print['our-score'] ) && isset ( $past_fixture_print['their-score'] ) ) : ?>
        	<td class='resultsCell'><?php echo ($past_fixture_print['homeaway'] == "Home") ? $past_fixture_print['our-score'] : $past_fixture_print['their-score'] ?></td>
        	<td class='resultsCell'><?php echo ($past_fixture_print['homeaway'] == "Home") ? $past_fixture_print['their-score'] : $past_fixture_print['our-score'] ?></td>
        	<?php else : ?>
        		<?php if ( current_user_can('edit_post', get_the_id() ) ) : ?>
    			<td colspan="2"><span class='fa fa-plus-square'><?php echo $past_fixture_print['edit-result-link'] ?></span></td>
    			<?php else : ?>
    			<td colspan="2">TBC</td>
    			<?php endif ?>
    		<?php endif ?>
        	<td><?php echo ($past_fixture_print['homeaway'] == "Home") ? $opposing : "Bristol Bisons RFC" ?></td>
			
			<?php if ($linked_post_on) : ?>
        	<td<?php if ( ! isset ( $past_fixture_print['linked_posts'] ) ) echo " class='emptycell' " ?>>
        		<?php if ( isset ( $past_fixture_print['linked_posts'] ) ) : ?>
        		<ul>
                    <?php foreach ($past_fixture_print['linked_posts'] as $post ) : ?>
                    <li class='linked-posts-col'><span class='<?php echo $post['class'] ?>'><a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a></span></li>
                    <?php endforeach; ?>
        		</ul>
        		<?php endif ?>
        	</td>
        	<?php endif ?>
        	
        </tr>							<?php endforeach ?>
						</tbody>
					</table>
					<?php endif ?>
				</section>
        <?php endif ?>
        <?php endwhile ?>
        <?php endif ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
