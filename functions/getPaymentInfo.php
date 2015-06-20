<?php

/**
 *
 * Takes the id of a user and queries the Wordpress database,
 * returning user payment info in an array
 *
 * @param $id Wordpress User ID
 *
 * @return array
 */
function getPaymentInfo( $id ) {

	if ( get_user_meta ( $id, 'joined', true) ) {

		$args  = array( 'post_type' => 'GCLBillLog', 'author' => $id, 'posts_per_page' => - 1 );
		$query = new WP_Query( $args );

		$paymentInfo = array(
			'Subscription Status' => ucwords( getDDStatus( $id ) ),
			'Membership Type'     => get_user_meta( $id, 'joiningas', true ),
			'Successful Payments' => 0,
			'Total Paid'          => 0,
			'Total Refunded'      => 0,
			'Last Bill'           => 0
		);

		setlocale( LC_MONETARY, 'en_GB.UTF-8' );

		while ( $query->have_posts() ) {

			$query->the_post();

			switch ( get_post_meta( get_the_id(), 'status', true ) ) {

				case "withdrawn":
				case "paid":

					$paymentInfo['Successful Payments'] ++;
					$paymentInfo['Total Paid'] += get_post_meta( get_the_id(), 'amount', true );

					if ( get_the_date( ( 'U' ) ) > $paymentInfo['Last Bill'] ) {
						$paymentInfo['Last Bill'] = get_the_date( 'U' );
					}

					break;

				case "failed":
					break;

				case "refunded":
				case "chargedback":
					$paymentInfo['Total Refunded'] += get_post_meta( get_the_id(), 'amount', true );
					break;
			}

		}
	}
	else {
		$paymentInfo = array(   'Subscription Status' => 'Not Joined' );
	}

	return $paymentInfo;

}