<?php

if( ! defined ( 'ABSPATH' ) )

    exit;





global $woocommerce;

$page = get_page_by_title('Wallet', OBJECT, 'product');

$wallet_id = $page->ID;

$cart = WC()->cart;

$get_cart = WC()->cart->cart_contents;

if(!empty($get_cart)){

  foreach($get_cart as $key => $value ){

    $product_id = $value['product_id'];

    if($product_id == $wallet_id){

      $woocommerce->cart->empty_cart();

      WC()->cart->add_to_cart($wallet_id);

    }

  }

  return WC()->cart->cart_contents;

}

return $cart_item_data;
