<?php
/**
 * Base Customer class.
 *
 * The Dokan Custom Class.
 *
 * @class    DokanCustom
 * @category Class
 * @author   Codingkart
 */  
class DokanCustom  
{
  	 /**
     * Constructor for the DokanCustom class
     *
     * Sets up all the appropriate hooks and actions
     * 
     */
    public function __construct() {
        // change text on withdraw methods
		add_filter( 'dokan_withdraw_methods', array( $this,  'codingkart_dokan_get_seller_active_withdraw_methods_filter' ) ,1);  
	}
	
	/**
     * change text on withdraw methods
     */
    public function codingkart_dokan_get_seller_active_withdraw_methods_filter($methods) {
	  	$methods['bank']['title']='Bank Transfer (US banks only)';
    	return $methods;
	}

}
new DokanCustom();
?>