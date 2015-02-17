<?php
$fixtures = new WP_Query(array(
    'post_type' => 'fixture',
    'nopaging' => 'true',
    'orderby'   => 'meta_value',
    'meta_key'  => 'fixture-date',
    'order'     => 'ASC'
));

$events = new WP_Query(array(
    'post_type' => 'event',
    'nopaging' => 'true',
    'orderby'   => 'meta_value',
    'meta_key'  => 'date',
    'order'     => 'ASC'
));

$fixture_id = get_post_meta( $post->ID, 'fixture_id', true);
$event_id = get_post_meta( $post->ID, 'event_id', true); ?>

<div id='custom-form'>
    <p>Use the box below to link this post to a specific fixture/event.</p>
    <table class="form-table">
        <tbody>
            <tr>
		<?php if ( !$fixtures->have_posts() ) : ?>
            <td colspan="2">No fixtures have been entered</td>
            <?php else : ?>
            <th><label for="fixture_link">Fixture</label></th>
            <td>
                <select name="fixture_link">
                    <option value="0">Unlinked</option>
                    <?php while ( $fixtures->have_posts() ) : $fixtures->the_post() ?>
       
                    <option <?php if ( get_the_id() == $fixture_id ) echo 'selected="selected"'; ?> value="<?php echo get_the_id(); ?>"><?php echo date( 'jS \o\f F Y' , get_post_meta( get_the_id(), 'fixture-date', true ) );  ?> - <?php echo get_post_meta( get_the_id(), 'fixture-opposing-team', true ); ?></option>
                    <?php endwhile; ?>
                </select>
            </td>
            <?php endif ?>
            </tr>
            <tr>
		<?php if ( !$events->have_posts() ) : ?>
            <td colspan="2">No events have been entered</td>
            <?php else : ?>
            <th><label for="event_link">Event</label></th>
            <td>
                <select name="event_link">
                    <option value="0">Unlinked</option>
                    <?php while ( $events->have_posts() ) : $events->the_post() ?>       
                    <option <?php if ( get_the_id() == $event_id ) echo 'selected="selected"'; ?> value="<?php echo get_the_id() ?>"><?php the_title() ?></option>
                    <?php endwhile; ?>
                </select>
            </td>
            <?php endif ?>
            </tr>
        </tbody>
    </table>
</div>