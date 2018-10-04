<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}




/* ---------->>> Admin Page Hooks <<<---------- */

add_action( 'woocommerce_register_form_start', 'woocommerce_customer_phone_number' );

add_action( 'woocommerce_register_post', 'woocommerce_customer_phone_number_validation' );

add_action( 'woocommerce_created_customer', 'woocommerce_customer_save_phone_number' );

add_action( 'add_meta_boxes', 'woocommerce_wallet_product_metabox' );

add_action( 'save_post', 'woocommerce_wallet_metabox_data_handler' );

add_action( 'new_to_publish', 'woocommerce_wallet_metabox_data_handler' );

add_action( 'restrict_manage_posts', 'woocommerce_wallet_restrict_orders' );

add_filter( 'parse_query', 'woocommerce_wallet_filter_query' );

/* ---------->>> Checkout Page Hooks <<<---------- */

add_action( 'woocommerce_checkout_order_processed', 'wallet_order_processing', 10, 1 );

add_action( 'woocommerce_order_status_completed', 'wallet_after_order_completed', 10, 1 );

add_action( 'woocommerce_order_status_cancelled', 'wallet_order_cancelled', 10, 1 );

add_action( 'woocommerce_cart_calculate_fees', 'woo_add_cart_fee' );

// add_filter( 'woocommerce_available_payment_gateways', 'wallet_payment_gateway_handler', 10, 1 );



/* ---------->>> Cart Page Hooks <---------- */

add_action( 'template_redirect', 'wallet_template_redirect' );

add_filter( 'woocommerce_add_to_cart_validation', 'wallet_check_product_in_cart' );

add_filter( 'woocommerce_before_cart', 'wallet_check_product_in_cart', 10, 1 );

/* ---------->>> My Account Page Hooks <---------- */
