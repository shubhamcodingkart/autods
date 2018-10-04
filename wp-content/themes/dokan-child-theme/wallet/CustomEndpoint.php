<?php
/**
 * CustomEndpoint class.
 *
 * The CustomEndpoint Class and  may extend by chlid class to get the comman functionlity .
 *
 * @class    CustomEndpoint
 * @category Class
 * @author   Codingkart
 */  
class CustomEndpoint extends BaseWallet
{
 
    /**
     * Constructor for the CustomEndpoint class
     *
     * Sets up all the appropriate hooks and actions
     * 
     */
    public function __construct() {
        add_action( 'init', array($this,'wk_custom_endpoint'));

        add_filter( 'woocommerce_account_menu_items', array($this,'wk_new_menu_items'));

        add_action( 'woocommerce_account_customer-wallet_endpoint', array($this,'wk_endpoint_content'));
    }

    public function wk_custom_endpoint() {
      add_rewrite_endpoint( 'customer-wallet', EP_ROOT | EP_PAGES );
    }

    /**
    * Insert the new endpoint into the My Account menu.
    *
    * @param array $items
    * @return array
    */
    public function wk_new_menu_items( $items ) {
        // Remove the logout menu item.
        $logout = $items['customer-logout'];
        unset( $items['customer-logout'] );

        // Remove plugin default Wallet endpoint
        unset($items['wallet']);

        // check user is subscriber
        $mBaseCustomer=new BaseCustomer();
        $check_type=$mBaseCustomer->codingkart_customer_check_user_type('subscriber');

        if($check_type){ 
            // Insert Custom customer-wallet
            $items[ 'customer-wallet' ] = __( 'Customer Wallet', 'webkul' );
        }

        $wallet_url = "/my-account/customer-wallet/";
        $currentpage = $_SERVER['REQUEST_URI'];
        
        // if user is not subscriber redirect to my account page
        if($wallet_url==$currentpage) {
            if(!$check_type){
                wp_redirect(home_url().'/my-account');
            }
        }
        
        // Insert back the logout item.
        $items['customer-logout'] = $logout;
        return $items;
    }

    /**
    * Add template for custom my-account endpoint
    */
    public function wk_endpoint_content() {
        require_once('CustomWalletEndpoint-template.php');   
    }
    

}
new CustomEndpoint();
?>