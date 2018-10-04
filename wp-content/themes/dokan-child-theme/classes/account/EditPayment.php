<?php
/**
 * EditPayment class.
 * @class    EditPayment
 * @category Class
 * @author   Codingkart
 */  
class EditPayment extends BaseAccount
{

     /**
     * Constructor for the EditAddress class
     *
     * Sets up all the appropriate hooks and actions
     * 
     */
    public function __construct() { 
        
        // Register and show Payoneer Email field to payment form 
    	add_filter( 'dokan_withdraw_methods', array( $this, 'codingkart_add_payoneer_payement_gateway_to_vendor_setup_form'),10,2);

        // Save Payoneer Email in vendor dasboard payment section
        add_action( 'dokan_store_profile_saved', array( $this, 'codingkart_save_payoneer_email'),10,2);

        // Save Payoneer Email in vendor payment setup section
        add_action( 'dokan_seller_wizard_payment_field_save', array( $this, 'codingkart_save_payoneer_email'),10,2);
    
    }

    /**
     *  Add Payoneer Email field to peyment form
     */
    public function codingkart_add_payoneer_payement_gateway_to_vendor_setup_form($methods){
        $methods['payoneer'] = array(
                'title'    => __( 'Payoneer', 'dokan-lite' ),
                'callback' => array($this,'dokan_withdraw_method_payoneer'),
            );

        return $methods;
    }

    /**
     *  Callback function to add Payoneer Email html
     */
    public function dokan_withdraw_method_payoneer( $store_settings ) {
        $email = isset( $store_settings['payment']['payoneer']['email'] ) ? esc_attr( $store_settings['payment']['payoneer']['email'] ) : $current_user->user_email ;
        ?>
        <div class="dokan-form-group">
            <div class="dokan-w8">
                <div class="dokan-input-group">
                    <span class="dokan-input-group-addon"><?php _e( 'E-mail', 'dokan-lite' ); ?></span>
                    <input value="<?php echo $email; ?>" name="payoneer_email" class="dokan-form-control email" placeholder="you@domain.com" type="text">
                </div>
            </div>
        </div>
        <?php
    }

    /**
     *  Save Payoneer Email
     */
    public function codingkart_save_payoneer_email(){
        $dokan_settings = dokan_get_store_info(get_current_user_id());
        $dokan_settings['payment']['payoneer'] = array(
            'email'    => '',
        );

        if ( isset( $_POST['payoneer_email'] ) ) {
            if (filter_var($_POST['payoneer_email'], FILTER_VALIDATE_EMAIL)) {
                $dokan_settings['payment']['payoneer']['email'] = $_POST['payoneer_email'];
            }
        }
        update_user_meta( get_current_user_id(), 'dokan_profile_settings', $dokan_settings );
    }

}
new EditPayment();
?>
