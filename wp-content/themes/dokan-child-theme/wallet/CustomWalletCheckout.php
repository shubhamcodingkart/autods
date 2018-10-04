<?php
/**
 * CustomWalletCheckout class.
 *
 * The CustomWalletCheckout Class and  may extend by chlid class to get the comman functionlity .
 *
 * @class    CustomEndpoint
 * @category Class
 * @author   Codingkart
 */  
class CustomWalletCheckout extends BaseWallet
{
 
    /**
     * Constructor for the CustomWalletCheckout class
     *
     * Sets up all the appropriate hooks and actions
     * 
     */
    public function __construct() {
        // Remove all payment gataways except paypal if wallet product in cart
       add_action( 'woocommerce_available_payment_gateways', array($this,'codingkart_show_only_paypal_payment_gateway_on_credit_wallet_amount'), 1 );

       //Add fee to your checkout if wallet product in cart
        add_action( 'woocommerce_cart_calculate_fees', array($this,'codingkart_woocommerce_custom_fee_on_wallet_product'));

    }

    /**
     * Remove all payment gataways except paypal if wallet product in cart
     */
    public function Codingkart_show_only_paypal_payment_gateway_on_credit_wallet_amount($gateways){
        foreach ( WC()->cart->get_cart() as $cart_item ) {
           $product = $cart_item['data'];
            if( ! empty($product) ){
                $product_id = method_exists( $product, 'get_id' ) ? $product->get_id() : $product->id;
                if ($product_id == 355) {
                    $payment_gateway_keys = array_keys($gateways);
                    foreach ($payment_gateway_keys as $key => $value) {
                        if ($value != 'paypal') {
                            unset( $gateways[$value]);
                        }
                    }

                    add_filter('woocommerce_enable_order_notes_field', '__return_false');
                }
            }
        }

        return $gateways; 
    }

    /**
     * Add fee to your checkout if wallet product in cart
     */
    public function codingkart_woocommerce_custom_fee_on_wallet_product() {
      global $woocommerce;

        if ( is_admin() && ! defined( 'DOING_AJAX' ) )
            return;
        foreach ( WC()->cart->get_cart() as $cart_item ) {
            $product = $cart_item['data'];
            if( ! empty($product) ){
                $product_id = method_exists( $product, 'get_id' ) ? $product->get_id() : $product->id;
                if ($product_id == 355) {
                    $surcharge = ( $woocommerce->cart->cart_contents_total + $woocommerce->cart->shipping_total ) * 0.03 + 0.3;    
                    $woocommerce->cart->add_fee( 'Fees', $surcharge, true, '' );

                    //Remove billing fields from checkout if wallet product in cart
                    add_filter( 'woocommerce_checkout_fields', array($this,'codingkart_remove_billing_fields_if_wallet_product_in_cart'));

                    // Remove form section titles
                    add_filter( 'wp_footer', array($this,'codingkart_remove_checkout_form_section_titles'));
                }
            }
        }
    }

    /**
     * Remove billing fields from checkout if wallet product in cart
     */
    public function codingkart_remove_billing_fields_if_wallet_product_in_cart($fields){
        foreach ($fields['billing'] as $key => $value) {
            unset( $fields['billing'][$key]);
        }

        unset($fields['order']['order_comments']);

        return $fields;
    }


    /**
     * Remove checkout form section titles
     */
    public function codingkart_remove_checkout_form_section_titles(){ ?>
        <style type="text/css">
            .woocommerce-billing-fields, .woocommerce-shipping-fields, .woocommerce-additional-fields{
                display: none;
            }
        </style>
    <?php }
    

}
new CustomWalletCheckout();
?>