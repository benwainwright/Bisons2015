<?php get_header(); ?>
<div id="wrapper">
    <div id="pagecol" class='ajaxcol'>
        <div class='page'>   
 
<?php if ( isset ( $GLOBALS['bisons_flash_message'] ) ) : ?>
        <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
    <?php endif ?>   
<header>
    <h2>Fixtures</h2>
    <ul class='pageMenu'>
    <?php if ( current_user_can('edit_post', get_the_id() ) ) { ?>
        <li><a class='fa fa-plus-square' href='<?php echo $GLOBALS['blog_info']['url']; ?>/wp-admin/post-new.php?post_type=fixture'>Add</a></li>
    <?php } ?>
        <li><a class='fa fa-rss-square' href='<?php echo  str_replace ( 'http://', 'webcal://', site_url('/calendar.ics?of=fixtures'))?>'>iCal (fixtures)<a/></li>
        <li><a  class='fa fa-rss-square' href='<?php echo  str_replace ( 'http://', 'webcal://', site_url('/calendar.ics')) ?>'>iCal (all)</a></li>
    </ul>
    </header>
<?php 



 $cuid = get_current_user_id();



$fixtures = array();
$past_fixtures = array();
$future_fixtures = array();
$results = array();
// Handle a lack of fixtures

if(! have_posts() ) : ?>
    <p>Details of this seasons fixtures have not been released yet. Check out results from previous seasons using the links below.</p>
<?php else :

// Loop over fixtures
while( have_posts()) : the_post();

    // Reformat date and convert the date and time combined into a unix time
    $unixdate = get_post_meta( get_the_id(), 'fixture-date', true );

    $printdate = date( 'jS \o\f F Y' , $unixdate );
    $time = get_post_meta( get_the_id(), 'fixture-kickoff-time', true );
    $datetime = date( 'Y:m:d' , $unixdate ). ' '.$time.':00';
    $datetimeunix = strtotime($datetime);

	if ( get_post_meta(get_the_id(), 'fixture-home-away', true) == 'Home' )
	{
		
		$clubInfoSettings = get_option('club-info-settings');
		$address = $clubInfoSettings['home-address'];
	}
	else
	{
		$address = get_post_meta( get_the_id(), 'fixture-address', true ) ? wpautop ( get_post_meta( get_the_id(), 'fixture-address', true ) ) : wpautop ( get_post_meta( get_post_meta( get_the_id(), 'fixture_team', true), 'homeaddress', true) );
	}

    // Prepare fixtures array
    $fixture = array(
        'id' => get_the_id(),
        'date' => get_post_meta( get_the_id(), 'fixture-date', true ) ? $printdate : 'Date TBC',
        'textdate' => get_post_meta( get_the_id(), 'text-date', true ),
        'kickoff' => get_post_meta( get_the_id(), 'fixture-kickoff-time', true ) ? date("g:ia", strtotime(get_post_meta( get_the_id(), 'fixture-kickoff-time', true ) )) : 'TBC',
        'playtime' => get_post_meta( get_the_id(), 'fixture-player-arrival-time', true ) ? date("g:ia", strtotime(get_post_meta( get_the_id(), 'fixture-player-arrival-time', true ))) : false,
        'address' => $address,
        'opposing' => get_post_meta( get_the_id(), 'fixture_team', true ) ? get_the_title( get_post_meta( get_the_id(), 'fixture_team', true ) ) : 'No Team' ,
        'page' => get_permalink(),
        'gmap' => get_post_meta( get_the_id(), 'fixture-gmap', true ) ? get_post_meta( get_the_id(), 'fixture-gmap', true ) : false,
        'teamurl' => get_permalink( get_post_meta( get_the_id(), 'fixture_team', true) ),
        'edit_link' => '<a class="editsmall" href="'.get_edit_post_link( get_the_id() ).'">Edit fixture</a>',
        'homeaway' => get_post_meta(get_the_id(), 'fixture-home-away', true)
    );
if( $datetimeunix > time() ) $future_fixtures[] = $fixture;
    else $past_fixtures[] = $fixture;

    // Otherwise push it into the $past_fixtures array

endwhile;

// Move the next fixture from the $future_fixtures array into its own variable
$first_fixture = $future_fixtures[0];
unset($future_fixtures[0]);
if( count($future_fixtures) > 0 ) $future_fixtures = array_values($future_fixtures);

// If there is a 'Next Event' show the relevant HTML
if( $first_fixture ) : ?>
<h3>Next Fixture</h3>
<p>Friends and family are always welcome at our matches. If there are any remaining fixtures or we have already played fixtures this season, scroll down the page to find the details and match results. </p>
      <div class='metaBox fixture'>
    
            <a itemprop="url" href="<?php echo $first_fixture['page'] ?>"><img src='<?php echo get_template_directory_uri() ?>/images/ball2.jpg' /></a>
      <div class='eventMeta'>
      		<h4><?php if ( $first_fixture['textdate'] ) : echo $first_fixture['textdate']; ?><?php else : ?><time itemProp="startDate" datetime="<?php echo $first_fixture['isodate'] ?>"><?php echo $first_fixture['date']; ?></time><?php endif ?></h4>
            <ul>
                  
                  <?php if ( $first_fixture['homeaway'] == 'Home' ) : ?>
                  <li class="fa fa-star teamName">Bristol Bisons RFC (Home)</li>
                  <li>Vs</li>
                  <li class="fa fa-star teamName"><a href='<?php echo $first_fixture['teamurl'] ?>'><?php echo $first_fixture['opposing'] ?></a> (Away)</li>
                  <?php else : ?>
                  <li class="fa fa-star teamName"><a href='<?php echo $first_fixture['teamurl'] ?>'><?php echo $first_fixture['opposing'] ?></a> (Home)</li>
                  <li>Vs</li>
                  <li class="fa fa-star teamName">Bristol Bisons RFC (Away)</li>
                  <?php endif ?>
              </ul>          
          <div class='clear'></div>
      </div>
      </div>





<?php else: ?>
    <p>It looks like there isn't any more fixtures coming up this season, or the committee have not yet updated the website - try checking back later. In the meantime, checkout the results for the fixtures we have played so far below.</p>
<?php endif; ?>
</section>
<?php if( $future_fixtures ) : ?>
    <section class="clearsection">
    <h3>Upcoming Fixtures</h3>
    <p>Below are the remaining upcoming fixtures for this season. Click on the fixture date for details of that fixture.</p>
    
      <table class='center fixturestable'>

            <tbody>

    <?php foreach($future_fixtures as $future_fixture) : ?>
    	
    			<tr>
    				<td class="datecol"><a href="<?php echo $future_fixture['page']; ?>"><?php echo $future_fixture['textdate'] ? $future_fixture['textdate'] : $future_fixture['date'] ?></a></td>
    				<td class="homeawaycol"><?php echo $future_fixture['homeaway'] ?></td>
    				<td><a href='<?php echo $future_fixture['teamurl'] ?>'><?php echo $future_fixture['opposing'] ?></a> </td>
    			</tr>


    <?php endforeach; ?>
        </tbody>
    </table>
    </section>
<?php endif; ?>
<?php if( $past_fixtures ) : ?>
<section class="clearsection">
<h3>Fixture Results</h3>
<p>Results for this season are below. Click on the match date to be taken to the individual results page which contains more details about that match such as match statistics. Please get in contact with us if you believe any fixture results to be wrong.</p>
    <?php


    // Create match results query
    $getResultsQuery = new WP_Query(array(
    'post_type' => 'results',
'nopaging' => 'true'
    ));
    // Loop over results, store in an array
    while($getResultsQuery->have_posts()) : $getResultsQuery->the_post();
        $results[] = array(
        				'link'			=> get_the_permalink(),
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
				$past_fixture['result_link'] = $result['link'];
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
    <table class='resultsTable center'>
    	<tbody>
    	

	<?php
    foreach($past_fixtures as $past_fixture_print) :
		
        $fixdate = $past_fixture_print['date'];
        $opposing = $past_fixture_print['opposing'];
        $oppurl = $past_fixture_print['teamurl'];
         $past_fixture_print['edit-result-link'] = isset ( $past_fixture_print['edit-result-link'] ) 
                ? $past_fixture_print['edit-result-link'] 
                : "<a href='/wp-admin/post-new.php?post_type=results&parent_post=".$past_fixture_print['id']."'>Result</a>";

        ?>
        <tr>
        	<td class="datecol"><?php if ( isset ($past_fixture_print['result_link'] ) ) : ?><a href='<?php echo $past_fixture_print['result_link'] ?>'><?php echo  $fixdate; ?></a><?php else : echo $fixdate; endif; ?></td>
        	<td><?php echo ($past_fixture_print['homeaway'] == "Home") ? "Bristol Bisons RFC" :  team_link($opposing, $oppurl) ?></td>
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
        	<td><?php echo ($past_fixture_print['homeaway'] == "Home") ? team_link($opposing, $oppurl) : "Bristol Bisons RFC" ?></td>
			
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
        	
        </tr>
    <?php endforeach; ?>
      

        </tbody>
        </table>
    </tbody>
    </table>
    </section>
    <?php $tries = get_stats_chart ( 'try_scored' ) ?>
    <?php $MOTM = get_stats_chart ( 'MOTM' ) ?>
    <?php $conversions = get_stats_chart ( 'conversion' ) ?>
	<?php if ( $tries || $MOTM || $conversions ) : ?>
    <h3>Statistics - Top 10s</h3>
    <p>The following information is based directly on match event data recorded in the database. If you think you should be included below, please let me know specifically which match you scored a try/conversion/motm in and I will add you to the match result record.
    
    <?php if ( $tries ) : ?>
    <h4>Try Scorers</h4>
    <table class="center">

    	<tbody>
    <?php $position = 1; foreach ($tries as $user => $tries ) : ?>
    	<tr>
    		<td class='smallcol'><?php echo $position ?></td>
    		<td><?php if ( $url = get_profile_url ( $user ) ) echo "<a href='$url'>" ?><?php $user = get_userdata ( $user ); echo $user->display_name; ?><?php echo $url ? '</a>' : '' ?></td>
    		<td class='medcol'><?php echo $tries. ' ' . plural_word ( $tries, 'try', 'tries' ) ?></td>
    	</tr>
	<?php $position++; endforeach ?>
		</tbody>
	</table>
    <?php endif ?> 
    <?php if ( $MOTM ) : ?>
    <h4>Men of the Match</h4>
    <table class="center">

    	<tbody>
    <?php $position = 1; foreach ($MOTM as $user => $motm ) : ?>
    	<tr>
    		<td class='smallcol'><?php echo $position ?></td>
    		<td><?php if ( $url = get_profile_url ( $user ) ) echo "<a href='$url'>" ?><?php $user = get_userdata ( $user ); echo $user->display_name; ?><?php echo $url ? '</a>' : '' ?></td>
    		<td class='medcol'><?php echo $motm. ' ' . plural_word ( $motm, 'match', 'matches' ) ?></td>
    	</tr>
	<?php $position++; endforeach ?>
		</tbody>
	</table>
	<?php endif ?>
	<?php if ( $conversions ) : ?>
    <h4>Conversions</h4>
    <table class="center">

    	<tbody>
    <?php $position = 1; foreach ($conversions as $user => $conversion ) : ?>
    	<tr>
    		<td class='smallcol'><?php echo $position ?></td>
    		<td><?php if ( $url = get_profile_url ( $user ) ) echo "<a href='$url'>" ?><?php $user = get_userdata ( $user ); echo $user->display_name; ?><?php echo $url ? '</a>' : '' ?></td>
    		<td class='medcol'><?php echo $conversion. ' ' . plural_word ( $conversion, 'conversion', 'conversions' ) ?></td>
    	</tr>
	<?php $position++; endforeach ?>
		</tbody>
	</table>
	<?php endif ?>
	<?php endif ?>

<?php endif; ?>
		<?php
		endif;
		$seasons = get_terms ( array ( 'seasons' ) );
		if ( is_array( $seasons ) ) : ?>
		<h3>Previous Seasons</h3>
			<table>
			<tbody>
		<tr>
			<?php for ($i = 0; $i < count($seasons); $i++ ) : ?>
			<td><span class='fa-li fa fa-folder'></span><a href='<?php echo site_url('/seasons/' . $seasons[$i]->slug) ?>'><?php echo $seasons[$i]->name ?></a></td>
		<?php if ($i % 2 && $i != count($seasons) -1) : ?>
		</tr>
		<tr>
		<?php endif ?>
			<?php endfor ?>
		</tr>
			</tbody>
				</table>
		<?php endif ?>
		</div>
    </div>

</div>
<?php get_footer(); ?>
