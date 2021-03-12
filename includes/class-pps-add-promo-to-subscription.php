<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'PPS_Add_Promo_To_Subscription' ) ) {

	/**
	* PPS_Add_Promo_To_Subscription class.
	*
	* @since 1.0.0
	*/
	class PPS_Add_Promo_To_Subscription {

		/**
		 * Class constructor.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct() {
			// To change add to cart text on single product page and in loop for upsell subscription products.
			add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'woocommerce_custom_add_to_cart_text_for_upsell_subscription_products' ), 10, 2 );
			add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'woocommerce_custom_add_to_cart_text_for_upsell_subscription_products' ), 10, 2 );
			// Checking if user adding to cart upsell products for an active subscription and update current subscription.
			add_action( 'woocommerce_add_to_cart', array( $this, 'add_upsell_product_for_subscription' ), 10, 3 );
		}


		/**
		 * To change add to cart text on single product page and in loop for upsell subscription products.
		 *
		 * @since 1.0.0
		 * @param string $var - Current add to cart button label.
		 * @param object $instance - Current instance.
		 * @return string
		 */
		public static function woocommerce_custom_add_to_cart_text_for_upsell_subscription_products( $var, $instance ) {
			global $product, $post;
			$upsell_product_for_subscription = get_post_meta( $post->ID, 'maybe_upsell_product_for_subscription', true );
			if ( $upsell_product_for_subscription ) {
				$button_label = !empty( get_post_meta( $post->ID, 'add_subscription_button_label', true ) ) ? get_post_meta( $post->ID, 'add_subscription_button_label', true ) : __( 'Add to my box subscription', 'promotional-product-for-subscription' );
				
			}
			else{
				$button_label = $var;
			}

			return __( $button_label, 'woocommerce' ); 
			
		}

		/**
		 * Remove product from cart after adding to the subscription.
		 *
		 * @since 1.0.0
		 * @param int $prod_id - The product ID.
		 * @return void
		 */
		public static function remove_product_from_cart_after_adding_to_subscription( $prod_id ) {
			$product_id = $prod_id;
			$product_cart_id = WC()->cart->generate_cart_id( $product_id );
			$cart_item_key = WC()->cart->find_product_in_cart( $product_cart_id );
			if ( $cart_item_key ) WC()->cart->remove_cart_item( $cart_item_key );
		}

		/**
		 * Checking if user adding to cart upsell products for an active subscription and update current subscription.
		 *
		 * @since 1.0.0
		 * @param int $cart_item_key - The cart item key.
		 * @param int $product_id - The product ID.
		 * @param int $quantity - The product quantity.
		 * @return void
		 */
		public static function add_upsell_product_for_subscription( $cart_item_key, $product_id, $quantity ) {
			$upsell_product_for_subscription = get_post_meta( $product_id, 'maybe_upsell_product_for_subscription', true );

			if ( $upsell_product_for_subscription ) {
				$cur_user_id = get_current_user_id();
				$subscription_id = 0;
				$users_subscriptions = wcs_get_users_subscriptions( $cur_user_id );

				//get all users active subscription.
				foreach ( $users_subscriptions as $subscription ){
					if ( $subscription->has_status( array( 'active' ) ) ) {
						$subscription_id = $subscription->get_id(); 
					}
				}

				//get last users active subscription.
				$active_subscription = wcs_get_subscription( $subscription_id );

				if( $cur_user_id ) {

					if ( $active_subscription ) {
						//get product which need to add to subscription.
						$product = wc_get_product( $product_id );
						$product_title = $product->get_title();
						$date_added = current_time('d-m-Y');
						$current_date_time = date('d-m-Y H:i:s');

						//add product to subscription.
						$tax = ( $product->get_price_including_tax() - $product->get_price_excluding_tax() ) * $quantity;

						$active_subscription->add_product($product, $quantity, array(
			                'totals' => array(
			                    'subtotal'     => $product->get_price_excluding_tax() * $quantity,
			                    'subtotal_tax' => $tax,
			                    'total'        => $product->get_price_excluding_tax() * $quantity,
			                    'tax'          => $tax,
			                    'tax_data'     => array( 'subtotal' => array( 1=>$tax ), 'total' => array( 1=>$tax ) )
			                )
			            ));

						$active_subscription->calculate_totals();


						$subscription_upsell_product_details_temp = array();

						$subscription_current_upsell_product_details = array(
							'_added_upsell_product_id' 		=> $product_id,
							'_added_upsell_product_date'	=> $date_added,
							'_added_upsell_product_count'	=> $quantity,
							'_added_upsell_product_price'	=> $product->get_price_excluding_tax()
						);

						//delete_post_meta( $subscription_id, '_subscription_current_upsell_product_details' );

						$get_subscription_current_upsell_product_details_meta = get_post_meta( $subscription_id, '_subscription_current_upsell_product_details', false );


						if ( empty( $get_subscription_current_upsell_product_details_meta ) ) {

							$subscription_upsell_product_details_temp[$current_date_time] = $subscription_current_upsell_product_details;
		
							update_post_meta( $subscription_id, '_subscription_current_upsell_product_details', $subscription_upsell_product_details_temp );
							
						}
						else {

							$subscription_upsell_product_details_temp[$current_date_time] = $subscription_current_upsell_product_details;

							$result = array_replace_recursive($get_subscription_current_upsell_product_details_meta[0], $subscription_upsell_product_details_temp);

							update_post_meta( $subscription_id, '_subscription_current_upsell_product_details', $result );
						}

						//Add admin notice to Subscription notes that product by user was added to subscription.
						$active_subscription->add_order_note( sprintf( __( 'User added the upsell product %s', 'promotional-product-for-subscription' ), $product_title ) );

						//Add user notice that product was added to subscription.
						wc_add_notice( __('The product has been added to your active subscription, the payment will be debited with the next regular payment, after which the product will be removed from the box', 'promotional-product-for-subscription' ), 'success');

						//Remove notice that product added to cart.
						add_filter( 'wc_add_to_cart_message_html', '__return_false' );


					}
					else{

						wc_add_notice( sprintf( esc_html__( 'You do not have an active subscription. Please first select and create your subscription %1$shere%2$s', 'promotional-product-for-subscription' ), '<a href="' . home_url() . '" target="_blank">', '</a>' ) );

						add_filter( 'wc_add_to_cart_message_html', '__return_false' );
					}

				}
				else {
						wc_add_notice( sprintf( esc_html__( 'Please login %1$shere%2$s first to add this product to your subscription', 'promotional-product-for-subscription' ), '<a href="' . get_permalink( get_option('woocommerce_myaccount_page_id') ) . '" target="_blank">', '</a>' ) );

						add_filter( 'wc_add_to_cart_message_html', '__return_false' );
				}

				//Remove this product from cart.
				$this->remove_product_from_cart_after_adding_to_subscription( $product_id );

			}
			

		}


	}

}

new PPS_Add_Promo_To_Subscription();