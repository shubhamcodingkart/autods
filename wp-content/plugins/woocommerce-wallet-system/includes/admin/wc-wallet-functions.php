<?php
/**
 * This file handles Wallet functions on admin end.
 *
 * @package WordPress Woocommerce Wallet System
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wallet_transactions' ) ) {
	/**
	 * Wallet Transaction List.
	 */
	function wallet_transactions() {
		global $wpdb, $wc_transaction;
		if ( isset( $_GET['transaction_id'] ) && ! empty( $_GET['transaction_id'] ) ) {
			$id = $_GET['transaction_id'];
			$wc_transaction->get_transaction_template( $id );
		} else {
			require_once WC_WALLET . 'includes/templates/admin/transactions/list.php';
		}
	}
}

if ( ! function_exists( 'wc_wallet_restrictions_setting_tab_content' ) ) {
	/**
	 * Obtain wallet restrictions setting fields.
	 */
	function wc_wallet_restrictions_setting_tab_content() {
		require_once WC_WALLET . 'includes/templates/admin/settings/restrictions.php';
	}
}

if ( ! function_exists( 'wc_wallet_otp_setting_tab_content' ) ) {
	/**
	 * Obtain wallet otp setting fields.
	 */
	function wc_wallet_otp_setting_tab_content() {
		require_once WC_WALLET . 'includes/templates/admin/settings/otp.php';
	}
}

if ( ! function_exists( 'wc_wallet_cashback_setting_tab_content' ) ) {
	/**
	 * Obtain wallet cashback setting fields.
	 */
	function wc_wallet_cashback_setting_tab_content() {
		require_once WC_WALLET . 'includes/templates/admin/settings/cashback.php';
	}
}
