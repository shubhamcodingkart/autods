<?php

if( ! defined ( 'ABSPATH' ) )
  exit;


add_meta_box(
  'wallet_cashback',
  'Wallet Cashback',
  'woocommerce_wallet_cashback_on_product',
  'product',
  'side',
  'high'
);
