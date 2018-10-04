<?php
/**
 * Base Cart class.
 *
 * The Base Cart Class and  may extend by chlid class to get the comman functionlity .
 *
 * @class    BaseCart
 * @category Class
 * @author   Codingkart
 */  
class BaseCart 
{
     /**
     * Constructor for the BaseCart class
     *
     * Sets up all the appropriate hooks and actions
     * 
     */
    public function __construct() {
        // get the cart items api reponse ajax function
        add_action( 'wp_ajax_codingkart_get_cart_items_detail_api_fn', array($this,'codingkart_get_cart_items_detail_api_fn') );
        add_action( 'wp_ajax_nopriv_codingkart_get_cart_items_detail_api_fn', array($this,'codingkart_get_cart_items_detail_api_fn' ));

        // BaseRest Class Object
        $this->BaseRest_obj = new BaseRest;

        // BaseCustomer Class Object
        $this->BaseCustomer_obj = new BaseCustomer;
    }

    /**
     * get the cart items api reponse ajax function
     */
	public function codingkart_get_cart_items_detail_api_fn(){
        $all_items_response = array();
        $product_upload_count = 0;
        foreach( WC()->cart->get_cart() as $cart_item ){
            $product_id = $cart_item['product_id'];
            $data = $this->BaseRest_obj->codingkart_woocommerce_get_api_response(site_url().'/wp-json/wc/v2/products/', $product_id);

            if ( is_user_logged_in() ) {
                // check user is subscriber
                $check_type=$this->BaseCustomer_obj->codingkart_customer_check_user_type('subscriber');

                if($check_type){
                    $user_id    = get_current_user_id();
                    $token      = $this->BaseCustomer_obj->codingkart_get_set_autods_token($user_id);
                    $api_url    = api_url;
                    $url        = $api_url.'api/ebay_api/user/'.$token.'/upload_item';

                    $make_call  = $this->BaseRest_obj->codingkart_rest_callAPI('POST',$url,$data);
                    $result_arr = (array) json_decode($make_call);
                    $result     = '<li>'.$result_arr['message'].'</li>';
                    $all_items_response[] = $result;

                    $succ_string = 'Successfully uploaded item';
                    if( strpos( $result_arr['message'], $succ_string ) !== false) {
                        $product_upload_count++;
                    }

                }
            }
        }

        $haystack = $make_call;
        $needle   = "Not Found";

        // URL should be right
        if( strpos( $haystack, $needle ) !== false) {
            wp_send_json_error( 'Error: Invalid URL!' );
        }else{
            $all_items_response = implode( " ", $all_items_response );
            $all_items_response = '<ul>'.$all_items_response.'</ul>';
            $final_response = array('response'=>$all_items_response, 'product_upload_count'=>$product_upload_count);
            print_r( json_encode($final_response) );
            die;
        }
    }
	

}
new BaseCart();
?>