<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;

$icon_class = ( $product->get_type() == 'simple' ) ? 'fa-shopping-cart' : 'fa-bars';

echo apply_filters( 'woocommerce_loop_add_to_cart_link',
    sprintf( '<a href="%s" rel="nofollow" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="cat btn add_to_cart_button %s" title="%s">%s</a>',
        esc_url( $product->add_to_cart_url() ),
        esc_attr( isset( $quantity ) ? $quantity : 1 ),
        esc_attr( $product->get_id() ),
        esc_attr( $product->get_sku() ),
        esc_attr( isset( $class ) ? $class : 'button' ),
        esc_html( $product->add_to_cart_text() ),
        sprintf( '<i class="fa %s"></i>', $icon_class )
    ),
$product );


if ( is_user_logged_in() ) {
    $user_id = get_current_user_id();
    $user = get_userdata( $user_id );
  
    // calling function codingkart_customer_check_user_type with help of BaseCustomer class object
    $mBaseCustomer=new BaseCustomer();
    $check_type=$mBaseCustomer->codingkart_customer_check_user_type('subscriber');
  
    if($check_type)
    {
        echo '<a href="javascript:void(0)" rel="nofollow" id="ajax_api_call_'.$product->get_id().'" data-product_id="'.$product->get_id().'" class="ajax_api_call" title="Upload to AutoDS"><i class="fa fa-upload"></i></a>';
    }
    
} else {
    echo '<a href="'.site_url().'/my-account/" rel="nofollow" class="upload-button-non-loggedin" title="Upload to AutoDS"><i class="fa fa-upload"></i></a>'; 
}

