<?php

if( ! defined ( 'ABSPATH' ) )
  exit;



$refund = array();
$order = wc_get_order( $order_id );
$user_id = (int)$order->user_id;
$payment_method = get_post_meta($order_id, '_payment_method', true);
$order_total = get_post_meta($order_id, '_order_total', true);
$wallet_amount = get_user_meta($user_id, 'wallet-amount', true);
$wallet_refund = get_post_meta($order_id, 'wallet-refund', true);
$refunds = $order->get_refunds();
$refund_id = $refunds[0]->get_id();
$refund_amount = $refunds[0]->get_data()['amount'];

$refund[$refund_id] = $refund_amount;
$wallet_refund[$refund_id] = $refund_amount;

if($payment_method == 'wallet'){
  $wallet_amount=$wallet_amount+(int)$amount;

  $message .= 'Order No. : '.$order_id.'  ';
  $message .= 'Wallet Credited : '.$order_total.'  ';

  wp_mail($to, $subject, $message);
  update_user_meta($user_id, 'wallet-amount', $wallet_amount);
  // var_dump(update_post_meta($order_id, 'wallet-refund', $wallet_refund));
  $asd = update_post_meta($order_id, 'wallet-refund', $wallet_refund);
  $offset   = get_option( 'gmt_offset' );
  $offset = $offset * 60 . ' minutes';
  $data = array(
    'transaction_type' => 'debit',
    'order_id'         => $order_id,
    'amount'           => $wallet_refund,
    'sender'           => get_current_user_ID(),
    'customer'         => $user_id,
    'transaction_note' => '',
    'transaction_date' => date( 'Y-m-d H:i:s',strtotime( $offset ) ),
    'reference'        => __( 'Wallet Cashback', 'wc_wallet' ),
  );

  $wc_transaction->generate( $data );
  if ($asd) {
    return true;
  }
}
return false;
