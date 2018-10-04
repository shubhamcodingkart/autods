<?php
/**
 * This file handles admin menu items.
 *
 * @package WordPress Woocommerce Wallet System
 */

/*----------*/ /*---------->>> Exit if Accessed Directly <<<----------*/ /*----------*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'admin_menu_items' );
add_action( 'admin_menu', 'customer_wallet_update_menu', 20 );

/**
 * Admin menu items.
 */
function admin_menu_items() {
	$hook = add_menu_page(
		'Customer Wallet',
		'Customer Wallet',
		'manage_options',
		'customer_wallet',
		'customer_wallet',
		'dashicons-portfolio',
		55
	);

	add_submenu_page(
		'customer_wallet',
		__( 'Transactions', 'wc_wallet' ),
		__( 'Transactions', 'wc_wallet' ),
		'manage_options',
		'wallet-transactions',
		'wallet_transactions'
	);

	$hook2 = add_submenu_page(
		'customer_wallet',
		'Cashback Rules',
		'Cashback Rules',
		'manage_options',
		'manage-cashback-rule',
		'woocommerce_wallet_cashback_rules'
	);

	add_submenu_page(
		'customer_wallet',
		'Settings',
		'Settings',
		'manage_options',
		'wallet-settings',
		'wallet_settings'
	);

	add_action( 'load-$hook', 'add_rule_screen_option' );
	add_action( 'admin_init', 'reg_function' );
}

/**
 * Settings field registeration.
 */
function reg_function() {
	register_setting( 'wc-wallet-restriction-settings', 'woocommerce_customer_min_wallet_credit_amount' );
	register_setting( 'wc-wallet-restriction-settings', 'woocommerce_customer_max_wallet_debit_amount' );
	register_setting( 'wc-wallet-otp-settings', 'woocommerce_customer_otp_verification' );
	register_setting( 'wc-wallet-otp-settings', 'woocommerce_customer_otp_access_method' );
	register_setting( 'wc-wallet-otp-settings', 'woocommerce_wallet_twilio_sid' );
	register_setting( 'wc-wallet-otp-settings', 'woocommerce_wallet_twilio_number' );
	register_setting( 'wc-wallet-otp-settings', 'woocommerce_wallet_twilio_auth_token' );
	register_setting( 'wc-wallet-otp-settings', 'woocommerce_wallet_twilio_otp_validation_limit' );
	register_setting( 'wc-wallet-cashback-settings', 'woocommerce_multiple_cashback_condition' );
	register_setting( 'wc-wallet-cashback-settings', 'woocommerce_multiple_cashback_condition_preference' );
}

/**
 * Screen Options.
 */
function add_rule_screen_option() {

	$options = 'per_page';

	$args = array(
		'label'   => 'Rule Per Page',
		'default' => 20,
		'option'  => 'rule_per_page',
	);

	add_screen_option( $options, $args );
}

add_filter( 'set-screen-option', 'set_options', 10, 3 );

/**
 * Set Options.
 *
 * @param string $status Status.
 * @param string $option Option.
 * @param string $value Option Value.
 */
function set_options( $status, $option, $value ) {

	return $value;

}

/**
 * Manual Wallet Updation.
 */
function update_wallet() {
	require_once 'update-wallet.php';
}

/**
 * Customer Wallet Update Menu.
 */
function customer_wallet_update_menu() {
	global $_registered_pages;

	$menu_slug = plugin_basename( 'update-wallet.php' );

	$hookname = get_plugin_page_hookname( $menu_slug, '' );

	if ( ! empty( $hookname ) ) {
		add_action( $hookname, 'update_wallet' );
	}

	$_registered_pages[ $hookname ] = true;
}

/**
 * Customer Wallet Amount.
 */
function customer_wallet() {
	require_once 'customer-wallet-amount.php';
}

/**
 * Wallet Configuration.
 */
function wallet_settings() {
	echo '<div class="wrap">';
	echo '<nav class="nav-tab-wrapper">';
	$wc_wallet_setting_tabs = array(
		'restrictions'  => __( 'Restrictions', 'wc_wallet' ),
		'otp'           => __( 'OTP', 'wc_wallet' ),
		'cashback'      => __( 'Cashback', 'wc_wallet' ),
	);
	$wc_wallet_setting_tabs = apply_filters( 'wc_wallet_setting_tabs', $wc_wallet_setting_tabs );
	$current_tab = empty( $_GET['tab'] ) ? 'restrictions' : sanitize_title( $_GET['tab'] );
	$id = $current_tab;

	foreach ( $wc_wallet_setting_tabs as $name => $label ) {
		echo '<a href="' . admin_url( 'admin.php?page=wallet-settings&tab=' . $name ) . '" class="nav-tab ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) . '">' . $label . '</a>';
	}
	?>
	</nav>

	<h1 class="screen-reader-text">
		<?php echo esc_html( $wc_wallet_setting_tabs[ $current_tab ] ); ?>
	</h1>
	<?php
	do_action( 'wc_wallet_' . $current_tab . '_setting_tab' );
}

/**
 * Cashback Rules.
 */
function woocommerce_wallet_cashback_rules() {

	if ( isset( $_GET['action'] ) && ( 'add' === $_GET['action'] || 'edit' === $_GET['action'] ) ) {

		require_once WC_WALLET . 'includes/templates/admin/cashback/content.php';

	} else {

		require_once WC_WALLET . 'includes/templates/admin/cashback/list.php';

	}

}
