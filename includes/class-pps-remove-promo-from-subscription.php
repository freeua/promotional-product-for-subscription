<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'PPS_Remove_Promo_From_Subscription' ) ) {

	/**
	* PPS_Remove_Promo_From_Subscription class.
	*
	* @since 1.0.0
	*/
	class PPS_Remove_Promo_From_Subscription {

		/**
		 * Class constructor.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct() {
			//Remove upsell products from subscription.
			add_filter( 'wcs_renewal_order_created', array( $this, 'remove_upsell_product_from_subscription' ), 11, 2 );
		}


		/**
		 * Remove upsell products from subscription.
		 *
		 * @since 1.0.0
		 * @param WC_Order $renewal_order - Order Object of the renewed order.
		 * @param object   $subscription - Subscription for which the order has been created.
		 * @return void
		 */
		public static function remove_upsell_product_from_subscription( $renewal_order, $subscription ) {

			$removal_item_id = 0;
			$product_id = 0;
			$added_products_arr = array();
			$line_items = $subscription->get_items();
			$subscription_id = $subscription->get_id();

			$added_subscription_upsell_product_details = get_post_meta( $subscription_id, '_subscription_current_upsell_product_details', false );
			
			if( !empty( $added_subscription_upsell_product_details ) ) {

				//Added to array all upsel products ids.
				foreach ( $added_subscription_upsell_product_details[0] as $date_added => $subscription_products ) {
					foreach ( $subscription_products as $key => $added_product_data ) {
						if ( $key == '_added_upsell_product_id' ) { 
							$product_id = $added_product_data;
							array_push( $added_products_arr, $product_id );
						}
					}			
				}

				//Checking all order  items for added upsell product ids and remove them if matches found.
				foreach ( $added_products_arr as $key => $prod_id ) {
					foreach ($line_items as $key => $value) {
						if ( $value['product_id'] == $prod_id ) {
							$removal_item_id = $key;
							$item_id = $removal_item_id;
							$line_item  = $line_items[ $item_id ];
					
							$product_id = wcs_get_canonical_product_id( $line_item );
							WCS_Download_Handler::revoke_downloadable_file_permission( $product_id, $subscription->get_id(), $subscription->get_user_id() );
							wcs_update_order_item_type( $item_id, 'line_item_removed', $subscription->get_id() );
						}	
					}
				}

				$subscription2 = wcs_get_subscription($subscription_id);
				$subscription2->calculate_totals();

				//Adding a note to the subscription that upsells products was removed.
				foreach ( $added_products_arr as $key => $prod_id ) {
					$product = wc_get_product( $prod_id );
					$product_title = $product->get_title();

					$subscription2->add_order_note( sprintf( __( 'The upsell product %s was removed automatically from the subscription', 'promotional-product-for-subscription' ), $product_title ) );
				}

				delete_post_meta( $subscription_id, '_subscription_current_upsell_product_details' );

			}

			return $renewal_order;
		}
	
	}

}

new PPS_Remove_Promo_From_Subscription();