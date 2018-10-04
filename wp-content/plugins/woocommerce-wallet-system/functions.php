<?php
/**
 * Plugin Name: WordPress Woocommerce Wallet System
 * Plugin URI: https://webkul.com
 * Description: WordPress Woocommerce Wallet System Plugin helps in integrating wallet payment method.
 * Version: 3.2.0
 * Author: Webkul
 * Author URI: https://webkul.com
 * Text Domain: wc_wallet
 *
 * @package Woocommerce Wallet System
 */

/*----------*/ /*---------->>> Exit if Accessed Directly <<<----------*/ /*----------*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WP_WALLET', plugin_dir_url( __FILE__ ) );
define( 'WC_WALLET', plugin_dir_path( __FILE__ ) );

if ( ! class_exists( 'Wallet_System' ) ) {
	/**
	 * Wallet_System class.
	 */
	class Wallet_System {
		/**
		 * Endpoint for wallet.
		 *
		 * @var string
		 */
		public static $endpoint = 'wallet';

		/**
		 * Constructor for the wallet class. Loads options and hooks in the init method.
		 */
		public function __construct() {
			ob_start();
			session_start();
			add_action( 'wc_wallet_send_verification_code', array( $this, 'wallet_verification_handler' ), 10, 2 );
			add_action( 'wc_wallet_after_send_verification_code', array( $this, 'wallet_after_send_verification_code' ), 10, 2 );

			$this->include_global();
			$this->include_backend();
			$this->include_frontend();

			// Actions used to insert a new endpoint in the WordPress.
			add_action( 'init', array( $this, 'add_endpoints' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'assets_enqueue' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'assets_enqueue' ) );
			add_action( 'wp_footer', array( $this, 'footer' ) );
			add_action( 'woocommerce_account_' . self::$endpoint . '_endpoint', array( $this, 'endpoint_content' ) );
			add_action( 'woocommerce_checkout_order_review', array( $this, 'woocommerce_wallet_payment' ), 20 );
			add_action( 'wp_ajax_nopriv_ajax_wallet_check', array( $this, 'ajax_wallet_check' ) );
			add_action( 'wp_ajax_ajax_wallet_check', array( $this, 'ajax_wallet_check' ) );
			add_action( 'woocommerce_refund_deleted', array( $this, 'wallet_refund_deleted' ), 10, 2 );

			// Change the My Accout page title.
			add_filter( 'the_title', array( $this, 'endpoint_title' ) );
			add_filter( 'woocommerce_account_menu_items', array( $this, 'new_menu_items' ) );
			add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );

			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'wallet_payment_gateway_handler' ), 10, 1 );

		}

		/**
		 * This function handles checkout payment gateways.
		 *
		 * @param array $available_gateways All available gateways.
		 */
		public function wallet_payment_gateway_handler( $available_gateways ) {

			global $woocommerce;

			$count = 0;

			$get_cart = WC()->cart->cart_contents;

			$arraykeys = array_keys( $available_gateways );

			$customer_wallet_max_debit = get_option( 'woocommerce_customer_max_wallet_debit_amount' );

			$page = get_page_by_title( 'Wallet', OBJECT, 'product' );

			$wallet_id = $page->ID;

			if ( ! empty( $get_cart ) ) {

				foreach ( $get_cart as $key => $value ) {

					$product_id = $value['product_id'];

					if ( $product_id == $wallet_id ) {

						$count = 1;

					}
				}
			}
			if ( is_user_logged_in() && ! is_admin() ) {

				$user_id = get_current_user_ID();

				$wallet_amount = get_user_meta( $user_id, 'wallet-amount', true );

				if ( ! empty( $customer_wallet_max_debit ) && $wallet_amount > $customer_wallet_max_debit ) {
					unset( $available_gateways['wallet'] );
					return $available_gateways;
				}

				if ( WC()->session->get( 'val' ) !== null && $wallet_amount >= ( WC()->session->get( 'val' ) + $woocommerce->cart->total ) ) {

					foreach ( $arraykeys as $key => $value ) {

						if ( $value == "wallet" ) {


						} else {

							unset( $available_gateways[ $value ] );

						}
					}
				} elseif ( $wallet_amount >= $woocommerce->cart->total && WC()->session->get( 'val' ) == null && $count == 0 ) {

				} else {

					unset( $available_gateways['wallet'] );

				}
			} else {

				unset( $available_gateways['wallet'] );

			}

			return $available_gateways;

		}

		/**
		 * After otp send.
		 *
		 * @param int $id ID of customer transferring money.
		 * @param int $code OTP code.
		 */
		public function wallet_after_send_verification_code( $id, $code ) {

			$status = WC_Wallet_SQL_Helper::insert_otp_code( $id, $code );

			return $status;

		}

		/**
		 * Handles otp send.
		 *
		 * @param int $code OTP code.
		 * @param int $phone_number Phone Number of customer transferring money.
		 */
		public function wallet_verification_handler( $code, $phone_number ) {

			if ( get_option( 'woocommerce_customer_otp_access_method', true ) == 'sms' ) {

				require_once WC_WALLET . 'includes/wc-wallet-verification-handler.php';

			} else if ( get_option( 'woocommerce_customer_otp_access_method', true ) == 'mail' ) {

				require_once WC_WALLET . 'includes/wc-wallet-mailer.php';

			}

		}

		/**
		 * Handles order refund cancellation/deletion.
		 *
		 * @param int $refund_id Order Refund ID.
		 * @param int $order_id Order ID.
		 */
		public function wallet_refund_deleted( $refund_id, $order_id ) {

			$order = wc_get_order( $order_id );
			$user_id = (int) $order->user_id;
			$payment_method = get_post_meta( $order_id, '_payment_method', true );

			if ( $payment_method == 'wallet' ) {

				$wallet_refund = get_post_meta( $order_id, 'wallet-refund', true );
				$wallet_amount = get_user_meta( $user_id, 'wallet-amount', true );

				$refund_amount = $wallet_refund[ $refund_id ];
				unset( $wallet_refund[ $refund_id ] );
				$wallet_amount = $wallet_amount - (int) $refund_amount;

				update_user_meta( $user_id, 'wallet-amount', $wallet_amount );
				update_post_meta( $order_id, 'wallet-refund', $wallet_refund );
			}
		}

		/**
		 * Removing add to cart button.
		 */
		public function remove_add_to_cart_buttons() {
			if ( is_product_category() || is_shop() ) {
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
			}
		}

		/**
		 * Handles assets enqueue.
		 */
		public function assets_enqueue() {
			wp_enqueue_style( 'wallet-css', plugin_dir_url( __FILE__ ) . '/assets/css/style.css' );
			if ( is_admin() || is_page( 'wallet' ) ) {
				wp_enqueue_script( 'jquery-ui-datepicker' );
				wp_enqueue_style( 'jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
				wp_enqueue_script( 'select2-js', plugins_url() . '/woocommerce/assets/js/select2/select2.min.js' );
				wp_enqueue_style( 'select2-css', plugins_url() . '/woocommerce/assets/css/select2.css' );
				wp_enqueue_script( 'backend-js', plugin_dir_url( __FILE__ ) . 'assets/js/backend.js' );
			}
		}

		/**
		 * Handles assets enqueue in footer.
		 */
		public function footer() {
			wp_enqueue_script( 'pluginjs', plugin_dir_url( __FILE__ ) . '/assets/js/plugin.js', array() );
			wp_localize_script(
				'pluginjs',
				'walletajax',
				array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'ajaxnonce' ),
				)
			);
		}

		/**
		 * Handles checkout wallet amount check.
		 */
		public function ajax_wallet_check() {
			if ( ! wp_verify_nonce( $_POST['nonce'], 'ajaxnonce' ) ) {
				die( 'Busted!' );
			}
			global $woocommerce;
			$check = intval( $_POST['check'] );

			if ( $check == 1 ) {
				$user_id = get_current_user_ID();
				$total = $woocommerce->cart->total;
				$customer_wallet_max_debit = get_option( 'woocommerce_customer_max_wallet_debit_amount' );
				$wallet_amount = get_user_meta( $user_id, 'wallet-amount', true );
				if ( ! empty( $wallet_amount ) ) {
					if( $wallet_amount > $customer_wallet_max_debit ) {
						WC()->session->set( 'val', $customer_wallet_max_debit );
					} else {
						WC()->session->set( 'val', $wallet_amount );
					}
				}
			} else {
				WC()->session->__unset( 'val', null );
			}

			die;
		}

		/**
		 * Register new endpoint to use inside My Account page.
		 */
		public function add_endpoints() {
			add_rewrite_endpoint( self::$endpoint, EP_ROOT | EP_PAGES );
		}

		/**
		 * Add new query var.
		 *
		 * @param array $vars All endpoints.
		 * @return array
		 */
		public function add_query_vars( $vars ) {
			$vars[] = self::$endpoint;
			return $vars;
		}

		/**
		 * Set endpoint title.
		 *
		 * @param string $title Title of endpoint.
		 * @return string
		 */
		public function endpoint_title( $title ) {
			global $wp_query;
			$is_endpoint = isset( $wp_query->query_vars[ self::$endpoint ] );
			if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
				// New page title.
				$title = __( 'My Wallet', 'woocommerce' );
				remove_filter( 'the_title', array( $this, 'endpoint_title' ) );
			}
			return $title;
		}

		/**
		 * Insert the new endpoint into the My Account menu.
		 *
		 * @param array $items All endpoint items.
		 * @return array
		 */
		public function new_menu_items( $items ) {
			// Remove the logout menu item.
			$logout = $items['customer-logout'];
			unset( $items['customer-logout'] );
			// Insert your custom endpoint.
			$items[ self::$endpoint ] = __( 'My Wallet', 'woocommerce' );
			// Insert back the logout item.
			$items['customer-logout'] = $logout;
			return $items;
		}

		/**
		 * Endpoint HTML content.
		 */
		public function endpoint_content() {

			global $wp_query;
			if ( $wp_query->query_vars['wallet'] == 'transfer' ) {
				include_once( 'includes/front/transfer.php' );
			} elseif ( $wp_query->query_vars['wallet'] == 'transfer/verification' ) {
				include_once( 'includes/front/verification.php' );
			} elseif ( strpos( $wp_query->query_vars['wallet'], 'view' ) !== false ) {
				global $wpdb;
				$id = preg_replace( "/[^0-9]/", '', $wp_query->query_vars['wallet'] );
				$user_id = get_current_user_ID();
				if ( ! empty( $id ) ) {
					$transaction = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wallet_transactions WHERE id = '$id' AND ( sender = '$user_id' OR customer = '$user_id' )", ARRAY_A );
				}
				require_once WC_WALLET . 'includes/templates/admin/transactions/view.php';
			} else {
				include_once( 'includes/front/wallet.php' );
			}
		}

		/**
		 * Plugin install action.
		 * Flush rewrite rules to make our custom endpoint available.
		 */
		public static function install() {
			flush_rewrite_rules();
			require_once( 'includes/install.php' );
		}

		/**
		 * Handles checkout wallet partial pay option.
		 */
		public function woocommerce_wallet_payment() {
			global $woocommerce;
			$total = $woocommerce->cart->total;
			if ( is_user_logged_in() ) {
				$user_id = get_current_user_ID();
				$wallet_money = get_user_meta( $user_id, 'wallet-amount', true );
				$count = 0;
				$get_cart = $woocommerce->cart->cart_contents;
				$page = get_page_by_title( 'Wallet', OBJECT, 'product' );
				$customer_wallet_max_debit = get_option( 'woocommerce_customer_max_wallet_debit_amount' );
				$wallet_id = $page->ID;
				if ( ! empty( $get_cart ) ) {
					foreach ( $get_cart as $key => $value ) {
						$product_id = $value['product_id'];
						if ( $product_id == $wallet_id ) {
							$count = 1;
						}
					}
				}
				if ( ! empty( WC()->session->get( 'val' ) ) ) {
					$cart_total = $total + WC()->session->get( 'val' );
				} else {
					$cart_total = $total;
				}
				if ( ! empty( $wallet_money ) && $wallet_money > 0 && ( $wallet_money < $cart_total || $customer_wallet_max_debit < $wallet_money ) && $count == 0 ) {
					if ( is_page( 'checkout' ) ) {
					?>
						<div class="wallet-checkout">
							<input type="checkbox" name="wallet-checkout-payment" id="wallet-checkout-payment"  <?php if ( ! empty( WC()->session->get( 'val' ) ) ) echo "checked='checked'" ?>/>
							<label for="wallet-checkout-payment"><?php echo __( 'Pay via Wallet', 'wc_wallet' ); ?></label>
						</div>
					<?php
					}
				}
			}
		}

		/**
		 * Handles Backend assets enqueue.
		 */
		private function include_backend() {
			if ( is_admin() ) {
				require_once 'includes/admin/wc-wallet-functions.php';
				require_once 'includes/admin/wc-wallet-hooks.php';
				require_once 'includes/admin/index.php';
				require_once 'includes/admin/product.php';
			}
		}

		/**
		 * Handles front end files.
		 */
		public function include_frontend() {
			require_once 'includes/wc-wallet-hooks.php';
			require_once 'includes/wc-wallet-functions.php';
			if ( ! is_admin() ) {
				require_once 'includes/wc-wallet-user-functions.php';
				require_once 'includes/helper/wc-wallet-db-handler.php';
				require_once 'includes/front/save-wallet-data.php';
			}
		}

		/**
		 * Handles all files.
		 */
		public function include_global() {
			require_once WC_WALLET . 'includes/class-wc-wallet-tranactions.php';
			$this->globals();
		}

		/**
		 * Global class objects.
		 */
		public function globals() {
			global $wc_transaction;

			$wc_transaction = new WC_Wallet_Transactions();
		}

	}
}

// Flush rewrite rules on plugin activation.
register_activation_hook( __FILE__, array( 'Wallet_System', 'install' ) );

function wc_wallet_loaded() {
	new Wallet_System();
	require WC_WALLET . 'includes/gateways/wallet/class-wc-gateway-wallet.php';
}

add_action( 'plugins_loaded', 'wc_wallet_loaded' );

$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
if ( in_array( 'woocommerce/woocommerce.php', $active_plugins, true ) ) {
	add_filter( 'woocommerce_payment_gateways', 'add_w_c_gateway_wallet_payment_gateway', 11 );
}

/**
 * Add the gateway to woocommerce
 *
 * @param array $methods All payment methods.
 */
function add_w_c_gateway_wallet_payment_gateway( $methods ) {
	$methods[] = 'WC_Gateway_Wallet';
	return $methods;
}