<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user_id = get_current_user_ID();

$wallet_amount = get_user_meta( $user_id, 'wallet-amount', true );

if ( ! empty( $wallet_amount ) && WC()->session->get( 'val' ) !== null ) {

	$amount = WC()->session->get( 'val' );

	$extracost = (-1) * $amount;

	WC()->cart->add_fee( 'Wallet', $extracost, false );

}
