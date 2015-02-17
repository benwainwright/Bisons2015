<?php
function fees_direct_debit_shortcode(  )
{
   $fees = new WP_Query ( array( 'post_type' => 'membership_fee', 'order' => 'ASC', 'orderby' => 'meta_value', 'meta_key' => 'fee-order' ) ); 
   $return = "<table class='center'><tbody>";
   
   while ( $fees->have_posts() ) 
   {
       $fees->the_post();
       if ( get_post_meta( get_the_id(), 'fee-type', true) == "Monthly Direct Debit" && get_post_meta( get_the_id(), 'fees-tables', true) != 'true' )
       {
           $return .= "<tr>";
           $return .= "<td>".get_post_meta( get_the_id(), 'fee-name', true);
           $return .= ( $description = get_post_meta( get_the_id(), 'fee-description', true) ) ? "<span class='feedescription'>$description</span>" : null;
           $return .= "</td><td>";
           $return .= get_post_meta( get_the_id(), 'initial-payment', true) ? 'Initial payment of '.pence_to_pounds ( get_post_meta( get_the_id(), 'initial-payment', true) ). ' and ' : null;
           $return .= pence_to_pounds ( get_post_meta( get_the_id(), 'fee-amount', true) )." per month</td>";
           $return .= "</tr>";
          
       }
   } 
   $return .= "</tbody></table>";
   
   return $return;
}
add_shortcode('memfeesDD', 'fees_direct_debit_shortcode');




function fees_single_payment_shortcode(  )
{
   $fees = new WP_Query ( array( 'post_type' => 'membership_fee', 'order' => 'ASC', 'orderby' => 'meta_value', 'meta_key' => 'fee-order' ) ); 
   $return = "<table class='center'><tbody>";
   
   while ( $fees->have_posts() ) 
   {
       $fees->the_post();
       if ( get_post_meta( get_the_id(), 'fee-type', true) == "Single Payment" && get_post_meta( get_the_id(), 'fees-tables', true) != 'true' )
       {
           $return .= "<tr>";
           $return .= "<td>".get_post_meta( get_the_id(), 'fee-name', true);
           $return .= ( $description = get_post_meta( get_the_id(), 'fee-description', true) ) ? "<span class='feedescription'>$description</span>" : null;
           $return .= "</td>";
           $return .= "<td>".pence_to_pounds ( get_post_meta( get_the_id(), 'initial-payment', true) )."</td>";
           $return .= "</tr>";
       }
   }
   $return .= "</tbody></table>";
   
   return $return;
}
add_shortcode('memfeessingle', 'fees_single_payment_shortcode');
