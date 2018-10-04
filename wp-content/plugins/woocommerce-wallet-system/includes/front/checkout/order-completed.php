<?php

if( ! defined ( 'ABSPATH' ) )
		exit;

global $wc_transaction;
$page = get_page_by_title( 'Wallet', OBJECT, 'product' );
$wallet_id = $page->ID;
$offset   = get_option( 'gmt_offset' );
$offset = $offset * 60 . ' minutes';

$order = new WC_Order( $order_id );
$user_id = (float) $order->get_user_id();
$payment_method = $order->get_payment_method();
$order_total = (float) $order->get_total();
$wallet_amount = get_user_meta( $user_id, 'wallet-amount', true );
$line_items = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );
$fees = $order->get_fees();
$to = get_user_meta( $user_id, 'billing_email', true );
$subject = 'Automated mail for wallet based transaction.';

foreach ( $line_items as $item_id => $item ) {
	if ( (float) $item->get_data()['product_id'] === (float) $wallet_id ) {
		if ( ! empty( $wallet_amount ) ) {
			$wallet_amount = $wallet_amount + (float) $order_total;
		} else {
			$wallet_amount = (float) $order_total;
		}

		$message .= 'Order No. : ' . $order_id . "\n";
		$message .= 'Wallet Transaction : ' . html_entity_decode( get_woocommerce_currency_symbol() ) . wc_format_decimal( $order_total, 2 ) . "\n";
		$message .= 'Remaining Amount : ' . html_entity_decode( get_woocommerce_currency_symbol() ) . wc_format_decimal( $wallet_amount, 2 );

		wp_mail( $to, $subject, $message );
		update_user_meta( $user_id, 'wallet-amount', $wallet_amount );

		$data = array(
			'transaction_type' => 'credit',
			'order_id'         => $order_id,
			'amount'           => $order_total,
			'customer'         => $user_id,
			'transaction_note' => '',
			'transaction_date' => date( 'Y-m-d H:i:s',strtotime( $offset ) ),
			'reference'        => __( 'Recharge Wallet', 'wc_wallet' ),
		);

		$wc_transaction->generate( $data );
	}
}


if ( ! empty( $user_id ) ) {
	if ( 'yes' === get_option( 'woocommerce_multiple_cashback_condition', true ) || get_option( 'woocommerce_multiple_cashback_condition_preference', true ) === 'cart' ) :

		global $wpdb;

		$table_name = $wpdb->prefix . 'cashback_rules';

		$cashback_rules = $wpdb->get_results( "SELECT id, rule_type, rule_price_from, rule_price_to, amount FROM $table_name WHERE rule_status = 'publish'" );

		if ( ! empty( $cashback_rules ) ) {

			foreach ( $cashback_rules as $cb_key => $cb_value ) {

				if ( $order_total >= (float) $cb_value->rule_price_from && $order_total <= (float) $cb_value->rule_price_to ) {

					if ( 'fixed' === $cb_value->rule_type ) {

						$cashback_amount = $cb_value->amount;

					} elseif ( 'percent' === $cb_value->rule_type ) {

						$cashback_amount = ( $cb_value->amount * $order_total ) / 100 ;

					}


					$cb_wallet_amount = floatval( $cashback_amount ) + floatval( $wallet_amount );

					update_user_meta( $user_id, 'wallet-amount', $cb_wallet_amount );

					$data = array(
						'transaction_type' => 'credit',
						'order_id'         => $order_id,
						'amount'           => $cashback_amount,
						'customer'         => $user_id,
						'transaction_note' => '',
						'transaction_date' => date( 'Y-m-d H:i:s',strtotime( $offset ) ),
						'reference'        => __( 'Wallet Cashback', 'wc_wallet' ),
					);

					$wc_transaction->generate( $data );

					$message .= 'Order No. : ' . $order_id . "\n";
					$message .= 'Wallet Cashback : ' . html_entity_decode( get_woocommerce_currency_symbol() ) . wc_format_decimal( $cashback_amount, 2 ) . ' for Shopping of ' . html_entity_decode( get_woocommerce_currency_symbol() ) . wc_format_decimal( $order_total, 2 ) . "\n";
					$message .= 'Remaining Amount : ' . html_entity_decode( get_woocommerce_currency_symbol() ) . wc_format_decimal( $cb_wallet_amount, 2 );

					wp_mail( $to, $subject, $message );

				}
			}
		}

	endif;

	if ( 'yes' === get_option( 'woocommerce_multiple_cashback_condition', true ) || 'product' === get_option( 'woocommerce_multiple_cashback_condition_preference', true ) ) :

		foreach ( $line_items as $item_id => $item ) {

			$woocommerce_wallet_cashback_quantity_restriction = get_post_meta( $item->get_data()['product_id'], '_cashback_min_quantity_restriction', true );
			$woocommerce_wallet_cashback_type = get_post_meta( $item->get_data()['product_id'], '_cashback_type_restriction', true );
			$woocommerce_wallet_cashback_amount = get_post_meta( $item->get_data()['product_id'], '_cashback_amount_awarded', true );

			if ( (float) $woocommerce_wallet_cashback_quantity_restriction <= (float) $item->get_data()['quantity'] ) :

				if ( 'fixed' === $woocommerce_wallet_cashback_type ) {

					$cashback_amount = $woocommerce_wallet_cashback_amount;

				} elseif ( 'percent' === $woocommerce_wallet_cashback_type ) {

					$cashback_amount = ( $woocommerce_wallet_cashback_amount * $order_total ) / 100 ;

				}

				$cb_wallet_amount = floatval( $cashback_amount ) + floatval( $wallet_amount );

				update_user_meta( $user_id, 'wallet-amount', $cb_wallet_amount );

				$data = array(
					'transaction_type' => 'credit',
					'order_id'         => $order_id,
					'amount'           => $cashback_amount,
					'customer'         => $user_id,
					'transaction_note' => '',
					'transaction_date' => date( 'Y-m-d H:i:s',strtotime( $offset ) ),
					'reference'        => __( 'Wallet Cashback', 'wc_wallet' ),
				);

				$wc_transaction->generate( $data );

				$message .= 'Order No. : ' . $order_id . "\n";
				$message .= 'Wallet Cashback : ' . html_entity_decode( get_woocommerce_currency_symbol() ) . wc_format_decimal( $cashback_amount, 2 ) . ' for ' . get_the_title( $item->get_data()['product_id'] ) . "\n";
				$message .= 'Remaining Amount : ' . html_entity_decode( get_woocommerce_currency_symbol() ) . wc_format_decimal( $cb_wallet_amount, 2 );

				wp_mail( $to, $subject, $message );

			endif;

		}

	endif;

}
