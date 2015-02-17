<div class="wrap">
    <h1>GoCardless Events</h1>
<?php $hooks = new WP_Query ( array ( 'post_type' => 'webhook', 'posts_per_page' => 30 ) ); ?>

<?php if ( $hooks->have_posts() ) : ?>
    
    
<table class='widefat'>
    <thead>
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>ID</th>
            <th>Type</th>
            <th>Source ID</th>
            <th>Source Type</th>
            <th>Status</th>
            <th>Amount</th>
            <th>Amount minus fees</th>
        </tr>
    </thead>
    <tbody>
        
<?php $i = 0; while ( $hooks->have_posts() ) : $hooks->the_post(); $resource = get_post_meta(get_the_id(), 'resource', true ); ?>

    <tr<?php if ($i % 2 ) echo ' class="alternate" ' ?>>
        <td><?php echo get_the_date('jS \o\f M Y') ?></td>
        <td><?php echo get_the_date('H:i:s') ?></td>
        <td><?php echo $resource['resource_content']['id'] ?></td>
        <td><?php echo $resource['resource_type'] ?></td>
        <td><?php echo $resource['resource_content']['source_id'] ? $resource['resource_content']['source_id'] : '<em>N/A</em>' ?></td>
        <td><?php echo $resource['resource_content']['source_type'] ? $resource['resource_content']['source_type'] : '<em>N/A</em>' ?></td>
        <td><?php echo $resource['resource_content']['status'] ?></td>
        <td><?php echo $resource['resource_content']['amount'] ? $resource['resource_content']['amount'] : '<em>N/A</em>'  ?></td>
        <td><?php echo $resource['resource_content']['amount_minus_fees'] ? $resource['resource_content']['amount_minus_fees'] : '<em>N/A</em>'  ?></td>
    </tr>
    
<?php $i++; endwhile; ?> 
    
    </tbody>

<?php else : ?>

    <p>No GoCardless webhooks recorded...</p>

<?php endif ?>

</div>