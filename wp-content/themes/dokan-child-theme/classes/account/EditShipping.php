<?php
/**
 * EditShipping class.
 * @class    EditShipping
 * @category Class
 * @author   Codingkart
 */  
class EditShipping extends BaseAccount
{

     /**
     * Constructor for the EditAddress class
     *
     * Sets up all the appropriate hooks and actions
     * 
     */
    public function __construct() { 

    	// change shipping nav url
    	add_filter ( 'dokan_get_dashboard_nav',  array( $this, 'codingkart_change_shipping_nav_url' )); 

    	// Add extra fields to vendor shipping form
    	add_action( 'dokan_after_shipping_options_updated', array( $this, 'codingkart_custom_handle_shipping'), 10,2  );	   
	}

    /**
	 * change shipping nav url
	 *
	 * @param  array  $urls 
	 *
	 * @return array  
	 */
	function codingkart_change_shipping_nav_url( $urls ) {
		$urls['settings']['sub']['shipping']['url'] = home_url().'/index.php/dashboard/settings/regular-shipping/';
		return $urls;
	}

	/**
     *  Add extra fields to vendor shipping form
     *
     *  @since  2.0
     *
     *  @return void
     */
    function codingkart_custom_handle_shipping($rates, $s_rates ) {

    	$user_id = get_current_user_id();
            if ( isset( $_POST['dps_form_location_city'] ) ) {
                update_user_meta( $user_id, '_dps_form_location_city', $_POST['dps_form_location_city'] );
            }

            if ( isset( $_POST['dps_form_location_zipcode'] ) ) {
                update_user_meta( $user_id, '_dps_form_location_zipcode', $_POST['dps_form_location_zipcode'] );
            }

    }

}
new EditShipping();
?>
