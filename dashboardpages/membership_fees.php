<div class="wrap">
    <h2>Membership Fees<a href="post-new.php?post_type=membership_fee" class="add-new-h2">Add New</a></h2>
    <?php $fees = new WP_Query ( array( 'post_type' => 'membership_fee', 'posts_per_page' => -1 )); ?>
    <table class="widefat">
        <thead>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Payment Method</th>
                    <th>Player/Supporter</th>
                    <th>Initial Payment</th>
                    <th>Monthly Payment</th>
                    <th>Description</th>
                    <th>Requires Approval</th>
                </tr>
            </thead>
        </thead>
        <tbody>
    <?php $i = 0; if ( $fees->have_posts() ) : while ( $fees->have_posts() ) : $fees->the_post(); ?>
        <tr<?php if ($i % 2) { echo " class='alternate'"; } ?>>
            <td><a href="<?php echo get_edit_post_link() ?>"><?php echo get_post_meta( get_the_id(), 'fee-name', true) ?></a></td>
            <td><?php echo get_post_meta( get_the_id(), 'fee-type', true) ?></td>
            <td><?php echo get_post_meta( get_the_id(), 'supporter-player', true) ?></td>
            <td><?php echo pence_to_pounds ( get_post_meta( get_the_id(), 'initial-payment', true) ) ?></td>
            <td><?php echo pence_to_pounds ( get_post_meta( get_the_id(), 'fee-amount', true) ) ?></td>
            <td><?php echo ($description = get_post_meta( get_the_id(), 'fee-description', true)) ? $description : "<em>None</em>"?></td>
            <td><?php echo get_post_meta( get_the_id(), 'requires-approval', true) ? "Yes" : "No" ?></td>
        </tr>
    <?php $i++; endwhile; endif; ?> 
        </tbody>
    </table>
</div>