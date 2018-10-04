<?php

if( ! defined ( 'ABSPATH' ) )
    exit;




global $woocommerce;

$count = 0;

$get_cart = WC()->cart->cart_contents;

$arrayKeys = array_keys($available_gateways);

$page = get_page_by_title('Wallet', OBJECT, 'product');

$wallet_id = $page->ID;

if(!empty($get_cart)){

  foreach($get_cart as $key => $value ){

    $product_id = $value['product_id'];

    if($product_id == $wallet_id){

      $count = 1;

    }

  }

}
if(is_user_logged_in()){

  $user_id = get_current_user_ID();

  $wallet_amount = get_user_meta($user_id, 'wallet-amount', true);

  if(isset($_SESSION['val']) && $wallet_amount >= ($_SESSION['val'] + $woocommerce->cart->total) ){

    foreach ($arrayKeys as $key => $value) {

        if($value == "wallet"){


        }else{

          unset($available_gateways[$value]);

        }

      }

  }
    else if($wallet_amount >= $woocommerce->cart->total && !isset($_SESSION['val']) && $count==0){

    }
    else {

      unset( $available_gateways['wallet'] );

    }

}
else{

  unset( $available_gateways['wallet'] );

}

return $available_gateways;
