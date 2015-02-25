<?php get_header(); ?>
<div id="wrapper">
    <div id="pagecol" class='ajaxcol'>
        <div class='page'>   
 
<?php if ( isset ( $GLOBALS['bisons_flash_message'] ) ) : ?>
        <p id="flashmessage"><?php echo $GLOBALS['bisons_flash_message'] ?></p>
    <?php endif ?>   
<header class='header'>
    <h2>Events</h2>
    <ul class='pageMenu'>
    <?php if ( current_user_can('edit_post', get_the_ID() ) ) { ?>
        <li><a class='fa fa-plus-square fa-lg' href='<?php echo $GLOBALS['blog_info']['url']; ?>/wp-admin/post-new.php?post_type=fixture'>Add</a></li>
    <?php } ?>
        <li><a class='fa fa-rss-square fa-lg' href='<?php echo  str_replace ( 'http://', 'webcal://', site_url('/calendar.ics?of=events'))?>'>iCal (events)<a/></li>
        <li><a  class='fa fa-rss-square fa-lg' href='<?php echo  str_replace ( 'http://', 'webcal://', site_url('/calendar.ics')) ?>'>iCal (all)</a></li>
    </ul>
</header>

<?php
// Loop through query results, save first event into $first_event, then put the rest in an array called $events
while( have_posts( ) ): the_post( );

    // Reformat date and convert the date and time combined into a unix time
    $unixdate = get_post_meta( get_the_id(), 'date', true );
    $printdate = date( 'l \t\h\e jS \o\f F Y' , $unixdate );
    $time = get_post_meta( get_the_id(), 'time', true );
    $datetime = date( 'Y:m:d' , $unixdate ). ' '. ( $time ? $time : '23:59' ).':00';
    $datetimeunix = strtotime($datetime);

    // Prepare the event array
    $event = array(
        'id'            => get_the_id(),
        'title'         =>  get_the_title(),
        'date'          =>  $printdate,
        'enddate'       =>  get_post_meta( get_the_id(), 'enddate', true ) ? date( 'l \t\h\e jS \o\f F Y' , (int) get_post_meta( get_the_id(), 'enddate', true ) ) : null,
        'isodate' 		=>  date('c', get_post_meta(get_the_id(), 'date', true )),
        'permalink'     =>  get_permalink(),
        'time'          =>  $time,
        'endtime'	    =>  get_post_meta( get_the_id(), 'endtime', true ),
        'fb-event'      =>  get_post_meta( get_the_id(), 'facebook-event', true ),
        'description'   =>  wpautop ( get_the_content() ),
        'address'       =>  get_post_meta( get_the_id(), 'address', true ),
    );
	
  	if ( has_post_thumbnail() ) {
   
        $thumbnailAtributes = array(
	              'itemprop'  => 'photo',
	          'class'     => 'alignright'
	    );
	    $event['img_src'] = get_the_post_thumbnail(get_the_id(), "full");
 	} 

    // If the date and time of the event is greater than the current date and time, push the array into the $future_events array
    if( $datetimeunix > time() ) $future_events[] = $event;

    // Otherwise push it into the $past_events array
    else $past_events[] = $event;
endwhile;

// Move the next event from the $future_events array into its own variable
$first_event = $future_events[0];
unset($future_events[0]);
if( count($future_events) > 0 )  $future_events = array_values($future_events);

// If there is a 'Next Event' show the relevant HTML
if( $first_event ) : ?>
    <p>We are a friendly group of guys and in most cases (except perhaps events where people have paid in advance, such as the christmas meal), anybody is welcome to come along to social events. This can be a great opportunity to get to know us and maybe ask more questions if you are considering coming along to training.</p>
	<section class='clear'>
		<h3>Up Next</h3>
            <div class="metaBox nextEvent">
            	
            	<?php if ( isset ( $first_event['img_src'] ) ) echo $first_event['img_src'] ?>

                  <div class='eventMeta'>
      				<h4><a itemprop="url" href="<?php echo $first_event['permalink'] ?>"><span itemprop="summary"><?php echo $first_event['title'] ?></span></a></h4>

                  	<ul>
                        <?php echo datetime_string ( $first_event['date'], $first_event['enddate'], $first_event['time'], $first_event['endtime'], false, $first_event['isodate'] ) ?>

                  <li class="fa fa-map-marker">Location<span itemprop="location"><br /><a href='http://maps.google.com?q=<?php echo strip_tags($first_event['address']); ?>'><?php echo $first_event['address']; ?></a></span></li>
                  <?php if($first_event['fb-event']) : ?><li class='fa fa-facebook-square'><a href='<?php echo $first_event['fb-event']; ?>'>Facebook Link</a></li><?php endif ?>
				  <li><?php echo $first_event['description'] ?></li>
                  </ul>



            <div class='clear'></div>


            </div>

      </div>
</section>
<?php endif;

// If there is events in the future after the first_event has been separated
if( count($future_events) > 0)  : ?>
    <section class='clearsection'>
        <h3>Other upcoming events</h3>
        <p>For more details about an event, click on the event title.</p>
        
        <table class='center'>
            <tbody>
            <?php foreach($future_events as $future_event) : ?>
            	
                <div class='albumThumb eventsArchive'>
                    <a class="desktopthumb" href='<?php echo $future_event['permalink'] ?>'><?php echo $future_event['img_src'] ?></a>
                    <div class='profileMeta'>
                        <h3><a href='<?php echo $future_event['permalink'] ?>'><?php echo $future_event['title'] ?></a></h3>
                        <ul>
                    <?php echo datetime_string ( $future_event['date'], $future_event['enddate'], $future_event['time'], $future_event['endtime'], false, $future_event['isodate'] ) ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
<?php endif; ?>



<?php 
// Create linked posts query;
$linked_posts = new WP_Query(array(
    'post_type'  => 'post',
    'nopaging'   => 'true', 
    'meta_query' => array ( 
            'relation'   => 'AND',
                            array(
                            'key' => 'event_id',
                            'compare' => 'EXISTS' ),
                            array(
                            'key' => 'event_id',
                            'compare' => '!=' ,
                            'value' => '0') 
                            )
                    )
);
$past_events = array_reverse($past_events); 

while($linked_posts->have_posts()) : $linked_posts->the_post();
    $linked_posts_array[] = array(
        'id' => get_the_id(),
        'parent-event' => get_post_meta(get_the_id(), 'event_id', true),
        'link' => get_permalink(get_the_id()),
        'title' => get_the_title(get_the_id())
    );
endwhile;




    foreach($past_events as &$past_event) :

        foreach($linked_posts_array as $linked_post) :
            if($past_event['id'] == $linked_post['parent-event']) :
                $past_event['linked_posts'][] = $linked_post;
            endif;
        endforeach;
        
    endforeach;

if( count($past_events) > 0)  : ?>

    <section class='clearsection'>
        <h3>Previous Events</h3>
        <p>For more details about an event, click on the event title.</p>
        <table class='center'>
            <tbody>
            	

            <?php foreach($past_events as $past_event) : ?>
            	
                <div class='albumThumb eventsArchive'>
                    <a class="desktopthumb" href='<?php echo $past_event['permalink'] ?>'><?php echo $past_event['img_src'] ?></a>
                    <div class='profileMeta'>
                        <h3><a href='<?php echo $past_event['permalink'] ?>'><?php echo $past_event['title'] ?></a></h3>
                        <ul>
                    <?php echo datetime_string ( $past_event['date'], $past_event['enddate'], $past_event['time'], $past_event['endtime'], false, $past_event['isodate'] ) ?>
                    <?php if ( isset ( $past_event['linked_posts'] ) ) : ?>
                        <?php foreach ( $past_event['linked_posts'] as $post ): ?>
                        <li class='fa-thumb-tack fa'><a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a></li>
                        <?php endforeach ?>        
                    <?php endif ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; ?>

            </body>
        </table>
    </section>
    <?php endif; ?>
    </div>
    </div>
    </div>
