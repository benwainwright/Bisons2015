<div class="wrap">
    <h1>Payment Information</h1>
<?php
global $wp_roles;

$roles = $wp_roles->roles;
foreach ( $roles as $key => $array ) {
    $users = get_users( array ( 'role' => $key ) );
    if ( sizeof ( $users ) )
    { 
        ?>
        <h2><?php echo  $array['name'] ?></h2>
        <table class='wp-list-table widefat playermanagement'>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Membership Type</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Paid this Season</th>
                    <th>More</th>
                </tr>
            </thead>
            <tbody>
        <?php $i = 0; foreach ($users as $user) :
            
              $current_form = new WP_Query ( array (
                 'post_type' => 'membership_form',
                 'posts_per_page' => 1,
                 'orderby'   => 'date',
                 'order'     => 'ASC',
                 'author'   => $user->data->ID
                 ) );
                         
                if ( ! $current_form->have_posts() ) : ?>
                <tr<?php if ($i % 2) { echo " class='alternate'"; } ?>>
                    <td class="noform namecol"><strong><?php echo $user->data->display_name ?></strong></td>
                    <td class="noformcol" colspan="5" class='noform'>No membership form submitted</td>
                </tr>
                <?php else :
                        while ( $current_form->have_posts() ) : $current_form->the_post();
                    ?>
                <tr>
                    <td><strong><?php echo get_post_meta(get_the_id(), 'firstname', true) ?> <?php echo get_post_meta(get_the_id(), 'surname', true) ?></strong></td>
                    <td><?php echo get_post_meta(get_the_id(), 'mem_name', true) ?></td>
                    <td><?php echo get_post_meta(get_the_id(), 'payment_type', true) ?></td>
                    <td><?php global $payment_statuses; echo $payment_statuses[ get_post_meta(get_the_id(), 'payment_status', true) ] ?></td>
                    <td></td>
                    <td><?php echo get_post_meta(get_the_id(), 'GoCardless_subscription_id', true) ?></td>
                </tr>
                <?php endwhile; endif ?>
        <?php $i++; endforeach ?>
            </tbody>
        </table>
<?php 
    }
} ?>
</div>