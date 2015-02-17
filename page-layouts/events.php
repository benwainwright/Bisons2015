<header class='header'>
    <h2><?php the_title(); ?></h2>
    <ul class='pageMenu'>
    <?php if ( current_user_can('edit_post') ) { ?>
        <li><a class='fa fa-plus-square fa-lg' href='<?php echo $GLOBALS['blog_info']['url']; ?>/wp-admin/post-new.php?post_type=fixture'>Add</a></li>
    <?php } ?>
        <li><a class='fa fa-rss-square fa-lg' href='<?php echo  str_replace ( 'http://', 'webcal://', site_url('/calendar.ics?of=events'))?>'>iCal (events)<a/></li>
        <li><a  class='fa fa-rss-square fa-lg' href='<?php echo  str_replace ( 'http://', 'webcal://', site_url('/calendar.ics')) ?>'>iCal (all)</a></li>
    </ul>
</header>

<?php
// Create the new WP_Query
$events_query = new WP_Query(array(
    'post_type' => 'event',
    'posts_per_page' => -1,
    'orderby'   => 'meta_value',
    'meta_key'  => 'date',
    'order'     => 'ASC',
    'meta_query' => array (
    array(
        'key'       =>  'ical_only',
        'compare'   =>  'NOT EXISTS'
    ) ),
));

// Loop through query results, save first event into $first_event, then put the rest in an array called $events
while( $events_query->have_posts( ) ):
    $events_query->the_post( );

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
        'permalink'     =>  get_permalink(),
        'time'          =>  $time,
        'endtime'	    =>  get_post_meta( get_the_id(), 'endtime', true ),
        'fb-event'      =>  get_post_meta( get_the_id(), 'facebook-event', true ),
        'description'   =>  wpautop ( get_the_content() ),
        'address'       =>  get_post_meta( get_the_id(), 'address', true ),
        'image_src'     =>  wp_get_attachment_url( get_post_meta( get_the_id(), 'image_id', true) )
    );
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
<section id="nextevent">
    <h3>Next Event</h3>
    <div id="mainevent">
          
      <ul class="metalist">
        <li class='listimage'><a href="<?php echo $first_event['image_src']; ?>"><img class='left' src="<?php echo $first_event['image_src']; ?>" /></a></li>
        <li><strong><?php echo $first_event['title']; ?></strong><?php if(get_edit_post_link( $first_event['id']) ) { ?> - <a href='<?php echo get_edit_post_link( $first_event['id']) ?>'>Edit</a><?php } ?></li>
        <li><strong><?php echo datetime_string ( $first_event['date'], $first_event['enddate'], $first_event['time'], $first_event['endtime'], false ) ?></strong></li>
        <li><?php echo $first_event['description']; ?></li>
        <?php if($future_event['address']) : ?><li class="address"><?php echo $first_event['address']; ?></li><?php endif; ?>


    </ul> 
    <div class="clear"></div>
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
            <tr>
                 <th><a href="<?php echo $future_event['permalink']; ?>"><?php echo $future_event['title']; ?></a></th>
                 <td><?php echo datetime_string ( $future_event['date'], $future_event['enddate'], $future_event['time'], $future_event['endtime'] ) ?></td>
            </tr>
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
            <?php foreach( $past_events  as $past_event ) : ?>
                <tr>
                    <th><a href="<?php echo $past_event['permalink']; ?>"><?php echo $past_event['title']; ?></a>
                        
                    <?php if ( isset ( $past_event['linked_posts'] ) ) : ?>
                        <ul class='postlist'>
                            <?php foreach ( $past_event['linked_posts'] as $post ): ?>
                            <li><a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a></li>
                            <?php endforeach ?>        
                        </ul>
                    <?php endif ?>
                    </th>
                    <td><?php echo datetime_string ( $past_event['date'], $past_event['enddate'], $past_event['time'], $past_event['endtime'] ) ?></td>
                </tr>
            <?php endforeach; ?>
            </body>
        </table>
    </section>
    <?php endif; ?>
