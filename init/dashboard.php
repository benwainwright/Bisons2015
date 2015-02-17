<?php 
function add_dashboard_widgets()
{
    wp_add_dashboard_widget(
        'bisons-info-widget',
        'Bisons Custom Theme',
        'display_bisons_info'
    );
    /*
    wp_add_dashboard_widget(
        'bisons-fixtures-widget',
        'Fixtures',
        'display_fictures_widget'
    ); */
}
add_action( 'wp_dashboard_setup', 'add_dashboard_widgets');

function display_bisons_info()
{ ?>
    <p>This website runs on a purpose built theme with quite a lot of customisation. For more details about how to use various aspects of the site, checkout the <a href='/committee-area/'>committee area</a>.</p>
 
<?php }

$taxonomy = get_terms ( array ( 'seasons' ) );
foreach ($taxonomy as $tax) 
      
	{
      	if ( is_object ( $tax ) )
      	$taxeslight[] = $tax->name;
	}

function display_fictures_widget()
{ 
    $fixtures = new WP_Query(array(
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
    )));
    
?>
<div class="main">
    <ul>
    <?php while ( $fixtures->have_posts() ) : $fixtures->the_post(); ?>
        <li><span><?php echo get_post_meta( get_the_id(), 'fixture-opposing-team', true ) ?></span><?php echo get_post_meta( get_the_id(), 'fixture-opposing-team', true ) ?></li>
    <?php endwhile; ?>
    </ul>
</div>
<?php }


