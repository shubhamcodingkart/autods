<?php

if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}


if ( ! empty( WC()->session->get( 'val' ) ) ) {

	WC()->session->__unset( 'val', null );

}

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

foreach ( $fees as $key => $value ) {
	if ( 'Wallet' === (string) $value->get_data()['name'] ) {
		$fees = $value->get_data()['total'];
		$wallet_amount = $wallet_amount + $fees;

		$message .= 'Order No. : ' . $order_id . "\n";
		$message .= 'Wallet Transaction : ' . html_entity_decode( get_woocommerce_currency_symbol() ) . wc_format_decimal( $fees, 2 ) . "\n";
		$message .= 'Remaining Amount : ' . html_entity_decode( get_woocommerce_currency_symbol() ) . wc_format_decimal( $wallet_amount, 2 );

		wp_mail( $to, $subject, $message );
		update_user_meta( $user_id, 'wallet-amount', $wallet_amount );

		$data = array(
			'transaction_type' => 'debit',
			'order_id'         => $order_id,
			'amount'           => (-1) * $fees,
			'sender'           => $user_id,
			'transaction_note' => '',
			'transaction_date' => date( 'Y-m-d H:i:s',strtotime( $offset ) ),
			'reference'        => 'Use Wallet',
		);

		$wc_transaction->generate( $data );
	}
}

if ( 'wallet' === (string) $payment_method ) {
	$wallet_amount = (float) $wallet_amount - (float) $order_total;

	$message .= 'Order No. : ' . $order_id . "\n";
	$message .= 'Wallet Transaction : ' . html_entity_decode( get_woocommerce_currency_symbol() ) . wc_format_decimal( $order_total, 2 ) . "\n";
	$message .= 'Remaining Amount : ' . html_entity_decode( get_woocommerce_currency_symbol() ) . wc_format_decimal( $wallet_amount, 2 );

	wp_mail( $to, $subject, $message );
	update_user_meta( $user_id, 'wallet-amount', $wallet_amount );

	$data = array(
		'transaction_type' => 'debit',
		'order_id'         => $order_id,
		'amount'           => $order_total,
		'sender'           => $user_id,
		'transaction_note' => '',
		'transaction_date' => date( 'Y-m-d H:i:s',strtotime( $offset ) ),
		'reference'        => 'Use Wallet',
	);

	$wc_transaction->generate( $data );
}
