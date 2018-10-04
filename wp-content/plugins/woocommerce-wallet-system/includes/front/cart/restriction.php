<?php

if( ! defined( 'ABSPATH' ) )
    exit;




global $woocommerce;

$page = get_page_by_title('Wallet', OBJECT, 'product');

$wallet_id = $page->ID;

if(is_shop() || (get_post_type() == 'product'  && is_single())){

  $get_cart = WC()->cart->cart_contents;

  if(!empty($get_cart)){

    foreach($get_cart as $key => $value ){

      $product_id = $value['product_id'];

      if($product_id == $wallet_id){

        wc_add_notice( sprintf( 'Cannot add new product now. Either empty cart or process it first.</p>'));

      }

    }

  }

}
