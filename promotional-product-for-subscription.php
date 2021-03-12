<?php
/**
 * Plugin Name: Promotional Product for Subscription
 * Plugin URI: https://github.com/freeua/promotional-product-for-subscription
 * Description: A custom addon for Woocommerce Subscriptions, which add a promotional product to the subscription
 * Version: 1.0.0
 * Author: Free UA
 * Author URI: https://freeua.agency/
 * Text Domain: promotional-product-for-subscription
 * Domain Path: /languages
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core plugin class
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'PPS_Promotional_Product_Subscription' ) ) {
	class PPS_Promotional_Product_Subscription {
		/**
		 * @var Singleton The reference the *Singleton* instance of this class
		 */
		private static $instance;

		/**
		 * Main Instance.
		 *
		 * @since 1.0.0
		 * @return Main instance.
		 */
		public static function get_instance () {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Define the core functionality of the plugin.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'pps_promotional_product_for_subscription_init' ) );
			$this->includes();
			$this->load_plugin_textdomain();
		}

		/**
		 * Add admin notices if plugins woocommerce and woocommerce subscription is inactive.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public static function pps_promotional_product_for_subscription_init() {
			if ( ! class_exists( 'WooCommerce' ) || ! class_exists( 'WC_Subscriptions' ) ) {
				add_action( 'admin_notices', array( $this, 'pps_inactive_notice' ) );
				return;
			}
		}

		/**
		 * Load the translation of the plugin.
		 * @since 1.0.0
		 * @return void
		*/
		public static function load_plugin_textdomain() {
			load_plugin_textdomain( 'promotional-product-for-subscription', false, plugin_basename(dirname(__FILE__)) . '/languages' );
		}


		/**
		 * Notify user if plugins woocommerce and woocommerce subscription is inactive.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public static function pps_inactive_notice() {
		?>
			<div id="message" class="error">
				<p><?php
					printf(esc_html__('%1$sPromotional Product for Subscription is inactive.%2$s The %3$sWooCommerce%4$s and %5$sWooCommerce Subscriptions%6$s plugins must be active for Promotional Product for Subscription to work. Please install & activate WooCommerce and WooCommerce Subscriptions', 'promotional-product-for-subscription'), '<strong>', '</strong>', '<a href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="http://www.woothemes.com/products/woocommerce-subscriptions/">', '</a>');
					?>
				</p>
			</div>
			<style>#message.updated.notice.is-dismissible{display: none;}</style>
		<?php
		}

		/**
		 * nclude required core files.
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public static function includes() {
			include_once( 'includes/class-pps-custom-product-tab.php' );
			include_once( 'includes/class-pps-add-promo-to-subscription.php' );
			include_once( 'includes/class-pps-remove-promo-from-subscription.php' );
		}

	}

	PPS_Promotional_Product_Subscription::get_instance();
}