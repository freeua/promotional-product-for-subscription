<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'PPS_Custom_Product_Tab' ) ) {

	/**
	* PPS_Custom_Product_Tab class.
	*
	* @since 1.0.0
	*/
	class PPS_Custom_Product_Tab {

		/**
		 * Class constructor.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct() {
			//Adding a custom tab to the Products Metabox.
			add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_product_for_subscription_data_tab' ), 99 , 1 );
			//Adding and POPULATING (with data) custom fields in custom tab for Product Metabox.
			add_action( 'woocommerce_product_data_panels', array( $this, 'add_product_for_subscription_data_fields' ) );
            //Saving custom fields data of custom products tab metabox.
            add_action( 'woocommerce_process_product_meta', array( $this, 'save_meta_fields_product_for_subscription' ) );
		}


		/**
		 * Adding a custom tab to the Products Metabox
		 *
		 * @since 1.0.0
		 * @param array $product_data_tabs - Array of product tabs in admin.
		 * @return array
		 */
		public static function add_product_for_subscription_data_tab( $product_data_tabs ) {
			$product_data_tabs['product-for-subscription'] = array(
				'label' => __( 'Upsell product for subscription', 'promotional-product-for-subscription' ),
				'target' => 'product_for_subscription_data',
			);
			return $product_data_tabs;
		}


		/**
		 * Adding and POPULATING (with data) custom fields in custom tab for Product Metabox.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public static function add_product_for_subscription_data_fields() {
			global $post;

			$post_id = $post->ID;

			echo '<div id="product_for_subscription_data" class="panel woocommerce_options_panel">';

                // Checkbox Upsell product for subscription.
				woocommerce_wp_checkbox( array(
					'id'			=> 'maybe_upsell_product_for_subscription',
					'label'	  => __( 'Upsell product for subscription', 'promotional-product-for-subscription' ),
					'description'   => __( 'Is the current product an upsell that can be added to an existing subscription (checked- yes, unchecked - no)', 'promotional-product-for-subscription' ),
					'desc_tip'	=> true,
				) );

				// Text input for button label.
				woocommerce_wp_text_input( array(
					'id'			=> 'add_subscription_button_label',
					'placeholder'   => __( 'Enter the text for the "Add to Subscription" button', 'promotional-product-for-subscription' ),
					'label'	  => __( '"Add to my box subscription" button text', 'promotional-product-for-subscription' ), 
					'description'   => __( 'This text will be displayed on the button, which allows adding current product to an existing subscription', 'promotional-product-for-subscription' ),
					'desc_tip'	=> true,
				) );


			echo '</div>';
		}


        /**
         * ASaving custom fields data of custom products tab metabox.
         *
         * @since 1.0.0
         * @param int $post_id - Id of the post for which saving meta.
         * @return void
         */
        public static function save_meta_fields_product_for_subscription( $post_id ){
            // save the add_subscription_button_label field data
            if( isset( $_POST['add_subscription_button_label'] ) ) {
                update_post_meta( $post_id, 'add_subscription_button_label', esc_attr( $_POST['add_subscription_button_label'] ) );
            }

            // save the maybe_upsell_product_for_subscription field data
            if( isset( $_POST['maybe_upsell_product_for_subscription'] ) ) {
                update_post_meta( $post_id, 'maybe_upsell_product_for_subscription', esc_attr( $_POST['maybe_upsell_product_for_subscription'] ) );
            }
            else{
            	delete_post_meta( $post_id, 'maybe_upsell_product_for_subscription' );
            }
        }


	}

}

new PPS_Custom_Product_Tab();