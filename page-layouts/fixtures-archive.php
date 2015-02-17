<header>
    <h2>Fixtures Archive</h2>
    <?php if ( current_user_can('edit_post') ) { ?><p><a href='<?php echo $GLOBALS['blog_info']['url']; ?>/wp-admin/post-new.php?post_type=fixture'>Add new fixture</a></p><?php } ?>
</header>
<?php 

$seasons = get_terms ( array ( 'seasons' ) );
foreach ($seasons as $tax) $seasonslight[] = $tax->name;

    
$getfixturequery = new WP_Query(array(
'post_type' => 'fixture',
'nopaging' => 'true',
'orderby'   => 'meta_value',
'meta_key'  => 'fixture-date',
'order'     => 'ASC',
'tax_query' => array(
    array(
        'taxonomy' => 'seasons',
        'field'    => 'slug',
        'terms'    => $seasonslight
    )
) ) );

foreach ($seasons as $season) : 

if ( $getfixturequery->have_posts() ) : ?>
<h3><?php echo $season->name; ?></h3>
<?php while ( $getfixturequery->have_posts() ) : $getfixturequery->the_post(); ?>


<?php endwhile ?>
<?php endif ?>

<?php endforeach ?>
