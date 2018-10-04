<?php
/**
 * This file handles Wallet hooks on admin end.
 *
 * @package WordPress Woocommerce Wallet System
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wc_wallet_restrictions_setting_tab', 'wc_wallet_restrictions_setting_tab_content' );

add_action( 'wc_wallet_otp_setting_tab', 'wc_wallet_otp_setting_tab_content' );

add_action( 'wc_wallet_cashback_setting_tab', 'wc_wallet_cashback_setting_tab_content' );
