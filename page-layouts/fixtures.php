
<header>
    <h2><?php the_title(); ?></h2>
    <ul class='pageMenu'>
    <?php if ( current_user_can('edit_post') ) { ?>
        <li><a class='fa fa-plus-square fa-lg' href='<?php echo $GLOBALS['blog_info']['url']; ?>/wp-admin/post-new.php?post_type=fixture'>Add</a></li>
    <?php } ?>
        <li><a class='fa fa-rss-square fa-lg' href='<?php echo  str_replace ( 'http://', 'webcal://', site_url('/calendar.ics?of=fixtures'))?>'>iCal (fixtures)<a/></li>
        <li><a  class='fa fa-rss-square fa-lg' href='<?php echo  str_replace ( 'http://', 'webcal://', site_url('/calendar.ics')) ?>'>iCal (all)</a></li>
    </ul>
    </header>
<?php 



 $cuid = get_current_user_id();

$taxonomy = get_terms ( array ( 'seasons' ) );

foreach ($taxonomy as $tax) $taxeslight[] = $tax->slug;

$query_array = array(
'post_type' => 'fixture',
'nopaging' => 'true',
'orderby'   => 'meta_value',
'meta_key'  => 'fixture-date',
'order'     => 'ASC',
'tax_query' => array(
    array(
        'taxonomy' => 'seasons',
        'field'    => 'slug',
        'terms'    => $taxeslight,
        'operator' => 'NOT IN'
    )
));


$getfixturequery = new WP_Query($query_array);
$fixtures = array();
$past_fixtures = array();
$future_fixtures = array();
$results = array();
// Handle a lack of fixtures

if(!$getfixturequery->have_posts()) : ?>
    <p>Normally this page contains the details of all the upcoming fixtures for this season. It looks like the committee haven't uploaded them yet, try back later. Alternatively, check the <a href="#">fixture archive</a>.</p>
<?php endif;

// Loop over fixtures
while($getfixturequery->have_posts()) : $getfixturequery->the_post();

    // Reformat date and convert the date and time combined into a unix time
    $unixdate = get_post_meta( get_the_id(), 'fixture-date', true );

    $printdate = date( 'l \t\h\e jS \o\f F Y' , $unixdate );
    $time = get_post_meta( get_the_id(), 'fixture-kickoff-time', true );
    $datetime = date( 'Y:m:d' , $unixdate ). ' '.$time.':00';
    $datetimeunix = strtotime($datetime);



    // Prepare fixtures array
    $fixture = array(
        'id' => get_the_id(),
        'date' => get_post_meta( get_the_id(), 'fixture-date', true ) ? $printdate : 'Date TBC',
        'textdate' => get_post_meta( get_the_id(), 'text-date', true ),
        'kickoff' => get_post_meta( get_the_id(), 'fixture-kickoff-time', true ) ? date("g:ia", strtotime(get_post_meta( get_the_id(), 'fixture-kickoff-time', true ) )) : 'TBC',
        'playtime' => get_post_meta( get_the_id(), 'fixture-player-arrival-time', true ) ? date("g:ia", strtotime(get_post_meta( get_the_id(), 'fixture-player-arrival-time', true ))) : false,
        'address' => get_post_meta( get_the_id(), 'fixture-address', true ) ? get_post_meta( get_the_id(), 'fixture-address', true ) : 'TBC',
        'opposing' => get_post_meta( get_the_id(), 'fixture-opposing-team', true ) ? get_post_meta( get_the_id(), 'fixture-opposing-team', true ) : 'TBC',
        'page' => get_permalink(),
        'gmap' => get_post_meta( get_the_id(), 'fixture-gmap', true ) ? get_post_meta( get_the_id(), 'fixture-gmap', true ) : false,
        'teamurl' => get_post_meta( get_the_id(), 'fixture-opposing-team-website-url', true ) ? get_post_meta( get_the_id(), 'fixture-opposing-team-website-url', true ) : false,
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
<section class="centeralign">

    <table class='center resultstable'>
    <tbody>
        <tr>
            <th class="date-col" colspan='4'>
                <?php echo  $first_fixture['date']; ?>
                <?php if(get_edit_post_link( $first_fixture['id'] )) : ?>
                    <ul class='edit-links'>
                        <li><?php echo $first_fixture['edit_link']; ?></li>
                    </ul>
                <?php endif ?>
            </th>
        </tr>
        <tr class='nextfixturemeta'>
            <?php if ($first_fixture['homeaway'] == "Home") : ?>
            <td class="hometeam-col"><span class="homeawaylabel">Home Team</span>Bristol Bisons RFC</td>
            <?php else : ?>
            <td class="hometeam-col"><span class="homeawaylabel">Home Team</span><?php echo team_link($first_fixture['opposing'], $first_fixture['teamurl']); ?></td>
            <?php endif ?>
            <td>
                <ul>
                    <li><strong>Players Arrive</strong><br /><?php echo $first_fixture['playtime'] ?></li>
                    <li><strong>Kickoff</strong><br /><?php echo $first_fixture['kickoff'] ?></li>
                </ul>
            </td>
            <td>
                <strong>Address</strong><br /><span class="gmap-address map-1"><?php echo str_replace ( "\n", '<br />', $first_fixture['address'] ) ?></span>
            </td>
            <?php if ($first_fixture['homeaway'] == "Away") : ?>
            <td class="hometeam-col"><span class="homeawaylabel">Away Team</span>Bristol Bisons RFC</td>
            <?php else : ?>
            <td class="hometeam-col"><span class="homeawaylabel">Away Team</span><?php echo team_link($first_fixture['opposing'], $first_fixture['teamurl']); ?></td>
            <?php endif ?>           
        </tr>
    </tbody>
    </table>
<div class="gmap-canvas" id="map-1"></div>





<?php else: ?>
    <p>It looks like there isn't any more fixtures coming up this season, or the committee have not yet updated the website - try checking back later. In the meantime, checkout the results for the fixtures we have played so far below.</p>
<?php endif; ?>
</section>
<?php if( $future_fixtures ) : ?>
    <section class="clearsection">
    <h3>Upcoming Fixtures</h3>
    <p>Below are the remaining upcoming fixtures for this season. Click on the fixture date for more information about each one.</p>
      <table class='center fixturestable'>
            <tbody>

    <?php foreach($future_fixtures as $future_fixture) : ?>
  
                <tr>
                    <td colspan='3'><h4><a href="<?php echo $future_fixture['page']; ?>"><?php echo $future_fixture['textdate'] ? $future_fixture['textdate'] : $future_fixture['date'] ?></a></h4>
                        <p><strong><?php echo $future_fixture['homeaway'] ?></strong> match against <?php echo link_if_avail($future_fixture['opposing'], $future_fixture['teamurl']); ?></p></td>
                </tr>

    <?php endforeach; ?>
        </tbody>
    </table>
    </section>
<?php endif; ?>
<?php if( $past_fixtures ) : ?>
<section class="clearsection">
<h3>Fixture Results</h3>
<p>Results for this season are below. Please get in contact with us if you believe any fixture results to be wrong.</p>
    <?php


    // Create match results query
    $getresultsquery = new WP_Query(array(
    'post_type' => 'result',
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
            endif;
        endforeach;
        if(get_edit_post_link( $past_fixture['id'] ) ) $edit_col_on = true; 
    endforeach;
        
    $past_fixtures = array_reverse( $past_fixtures ); 
    
    foreach($past_fixtures as $past_fixture_print) :
        $fixdate = $past_fixture_print['date'];
        $ourscore = $past_fixture_print['our-score'] ? $past_fixture_print['our-score'] : "TBC";
        $theirscore = isset($past_fixture_print['their-score']) ? $past_fixture_print['their-score'] : "TBC";
        $opposing = $past_fixture_print['opposing'];
        $oppurl = $past_fixture_print['teamurl'];
        $linkedposts = $past_fixture_print['linked_posts'];
         $past_fixture_print['edit-result-link'] = $past_fixture_print['edit-result-link'] 
                ? $past_fixture_print['edit-result-link'] 
                : "<a class='editsmall' href='/wp-admin/post-new.php?post_type=result&parent_post=".$past_fixture_print['id']."'>Add result</a>";

        ?>
        <table class='center resultstable'>
        <tbody>
            <tr>
                <th class="date-col" colspan='4'>
                    <?php echo  $fixdate; ?>
                    <?php if(get_edit_post_link( $past_fixture['id'] )) : ?>
                        <ul class='edit-links'>
                            <li><?php echo $past_fixture_print['edit_link']; ?></li>
                            <li><?php echo $past_fixture_print['edit-result-link']; ?></li>
                        </ul>
                    <?php endif ?>
                </th>
            </tr>
            <tr>
                <?php if ($past_fixture_print['homeaway'] == "Home") : ?>
                  
                  
                <td class="hometeam-col"><span class="homeawaylabel">Home</span>Bristol Bisons RFC</td>
                <td class="scorecell<?php if ( $theirscore == 'TBC' && $ourscore == 'TBC') echo ' tbcscore" colspan="2' ?>"><?php echo $ourscore; ?></td>
                <?php if ( $theirscore != 'TBC' && $ourscore != 'TBC') : ?>
                <td class="scorecell"><?php echo $theirscore; ?></td>
                <?php endif ?>
                <td class="oppteam-col"><span class="homeawaylabel">Away</span><?php echo team_link($opposing, $oppurl); ?></td>
                <?php else : ?>
                  
                <td class="hometeam-col"><span class="homeawaylabel">Home</span><?php echo team_link($opposing, $oppurl); ?></td>
                <td class="scorecell<?php if ( $theirscore == 'TBC' && $ourscore == 'TBC') echo ' tbcscore" colspan="2' ?>"><?php echo $theirscore; ?></td>
                <?php if ( $theirscore != 'TBC' && $ourscore != 'TBC') : ?>
                <td class="scorecell"><?php echo $ourscore; ?></td>
                <?php endif ?>
                <td class="oppteam-col"><span class="homeawaylabel">Away</span>Bristol Bisons RFC</td>
                <?php endif ?>
                  
                  
                <?php if($linkedposts) : ?>
                <tr>
                <td colspan='4' class="linkedposts">
                    <ul class='postlist'> 
                        <?php foreach ($linkedposts as $post ) : ?>
                        <li><a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a></li>
                    <?php endforeach; ?>
                    </ul>

                </td>
                </tr>
                <?php endif; ?>
            </tr>
        </tbody>
        </table>
    <?php endforeach; ?>
    </section>
<?php endif; ?>



