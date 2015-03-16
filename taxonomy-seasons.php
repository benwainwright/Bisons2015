<?php get_header(); ?>
<div id="wrapper">
    <div id="pagecol" class='ajaxcol'>
        <div class='page'>   
 
<?php if ( isset ( $GLOBALS['bisons_flash_message'] ) ) : ?>
        <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
    <?php endif ?>   
<header>
    <h2>Season Archive (<?php global $wp_query; echo $wp_query->queried_object->name ?>)</h2>
    <ul class='pageMenu'>
    <?php if ( current_user_can('edit_post', get_the_id() ) ) { ?>
        <li><a class='fa fa-plus-square fa-lg' href='<?php echo $GLOBALS['blog_info']['url']; ?>/wp-admin/post-new.php?post_type=fixture'>Add</a></li>
    <?php } ?>
        <li><a class='fa fa-rss-square fa-lg' href='<?php echo  str_replace ( 'http://', 'webcal://', site_url('/calendar.ics?of=fixtures'))?>'>iCal (fixtures)<a/></li>
        <li><a  class='fa fa-rss-square fa-lg' href='<?php echo  str_replace ( 'http://', 'webcal://', site_url('/calendar.ics')) ?>'>iCal (all)</a></li>
    </ul>
    </header>
<?php 


$cuid = get_current_user_id();
$fixtures = array();
$results = array();
// Handle a lack of fixtures

if(! have_posts() ) : ?>
    <p>Normally this page contains the details of all the upcoming fixtures for this season. It looks like the committee haven't uploaded them yet, try back later. Alternatively, check the <a href="#">fixture archive</a>.</p>
<?php endif;

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
		
		$clubInfoSettings = get_option('club-info-settings-page');
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
	$fixtures[] = $fixture;
endwhile;



if( $fixtures ) : ?>
<section class="clearsection">
<h3>Fixture Results</h3>
<p>Results for this season are below. Please get in contact with us if you believe any fixture results to be wrong.</p>
    <?php


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
        'post_type' => 'post',
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

    // Loop over reports, store in an array
    while($linked_posts_query->have_posts()) : $linked_posts_query->the_post();
        $linked_posts[] = array(
            'id' => get_the_id(),
            'parent-fixture' => get_post_meta(get_the_id(), 'fixture_id', true),
            'link' => get_permalink(get_the_id()),
            'title' => get_the_title(get_the_id())
        );
    endwhile;

    /* Loop through fixtures array and change the unix times back to a date string.
     * Also if any of the results or reports parent_fixture matches the id, insert results/reports into the details of the old fixture
     */
    $match_report_col_on = false;
    $edit_col_on = false; 
	$linked_post_on = false;

    foreach($fixtures as &$fixture) :

        foreach($results as $result) :
            if($fixture['id'] == $result['parent-fixture']) :
                $fixture['their-score'] = $result['their-score'];
                $fixture['our-score'] = $result['our-score'];
                $fixture['edit-result-link'] = $result['edit-result-link'];
            endif;
        endforeach;

        foreach($linked_posts as $linked_post) :
            if($fixture['id'] == $linked_post['parent-fixture']) :
                $fixture['linked_posts'][] = $linked_post;
				$linked_post_on = true;
            endif;
        endforeach;
        if(get_edit_post_link( $fixture['id'] ) ) $edit_col_on = true; 
    endforeach;
        
    $fixtures = array_reverse( $fixtures );
    ?>
    <table class='resultsTable center'>
    	<tbody>
    	
	<?php
    foreach($fixtures as $fixture) :
        $fixdate = $fixture['date'];
		
        $opposing = $fixture['opposing'];
        $oppurl = $fixture['teamurl'];
         $fixture['edit-result-link'] = isset ( $fixture['edit-result-link'] ) 
                ? $fixture['edit-result-link'] 
                : "<a href='/wp-admin/post-new.php?post_type=results&parent_post=".$fixture['id']."'>Result</a>";

        ?>
        <tr>
        	<td class="datecol"><?php echo  $fixdate; ?></td>
        	<td><?php echo ($fixture['homeaway'] == "Home") ? "Bristol Bisons RFC" :  '<a href='.$fixture['teamurl'].'>'.$fixture['opposing'].'</a></td>' ?></td>
        	<?php if (isset ( $fixture['our-score'] ) && isset ( $fixture['their-score'] ) ) : ?>
        	<td class='resultsCell'><?php echo ($fixture['homeaway'] == "Home") ? $fixture['our-score'] : $fixture['their-score'] ?></td>
        	<td class='resultsCell'><?php echo ($fixture['homeaway'] == "Home") ? $fixture['their-score'] : $fixture['our-score'] ?></td>
        	<?php else : ?>
        		<?php if ( current_user_can('edit_post', get_the_id() ) ) : ?>
    			<td colspan="2"><span class='fa fa-plus-square'><?php echo $fixture['edit-result-link'] ?></span></td>
    			<?php else : ?>
    			<td colspan="2">TBC</td>
    			<?php endif ?>
    		<?php endif ?>
        	<td><?php echo ($fixture['homeaway'] == "Home") ?  '<a href='.$fixture['teamurl'].'>'.$fixture['opposing'].'</a></td>' : "Bristol Bisons RFC" ?></td>
			
			<?php if ($linked_post_on) : ?>
        	<td<?php if ( ! isset ( $fixture['linked_posts'] ) ) echo " class='emptycell' " ?>>
        		<?php if ( isset ( $fixture['linked_posts'] ) ) : ?>
        		<ul>
                    <?php foreach ($fixture['linked_posts'] as $post ) : ?>
                    <li class='linked-posts-col'><span class='fa fa-file'><a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a></span></li>
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
<?php endif; ?>
		</div>
    </div>

</div>
<?php get_footer(); ?>
