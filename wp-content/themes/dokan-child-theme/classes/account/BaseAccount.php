<?php
/**
 * Base Account class.
 *
 * The Bases Account Class and  may extend by chlid class to get the comman functionlity .
 *
 * @class    BaseAccount
 * @category Class
 * @author   Codingkart
 */  
class BaseAccount 
{

     /**
     * Constructor for the EditAddress class
     *
     * Sets up all the appropriate hooks and actions
     * 
     */
    public function __construct() {      
		// Remove wallet link for vendore type from my account
		add_filter ( 'woocommerce_account_menu_items',  array( $this, 'codingkart_remove_my_account_links' )); 
			  		  
	    // Remove wallet page for vendore type from my account
		add_action( 'woocommerce_account_navigation', array( $this, 'codingkart_redirect_wallet_link') );

		// Remove menu items from dasboard menu
		add_filter( 'dokan_get_dashboard_nav', array( $this, 'codingkart_dokan_remove_dashboard_menus') ,100 );

		// Unique Vendor Registration Data
		add_filter( 'registration_errors', array( $this, 'codingkart_unique_vendor_registration_data'), 10,3);

		// Custom vendor progressbar profile completion values
		add_filter( 'dokan_profile_completion_values', array( $this, 'codingkart_custom_profile_completion_values'), 100);

		// Add Custom fields on the User(vendor) profile (wp-admin)
		add_action( 'show_user_profile', array( $this, 'codingkart_add_user_vendor_meta_fields'), 20 );
		add_action( 'edit_user_profile', array( $this, 'codingkart_add_user_vendor_meta_fields'), 20 );
		add_action( 'personal_options_update', array( $this, 'codingkart_update_user_vendor_meta_fields') );
		add_action( 'edit_user_profile_update',array( $this, 'codingkart_update_user_vendor_meta_fields') );

		// Hide fields from Vendor setup wizard
		add_action( 'dokan_setup_wizard_styles', array( $this, 'codingkart_hide_fields_from_vendor_setup_wizard' ));

	}

     /**
     * Remove wallet link for vendore type from my account
     */
	public function codingkart_remove_my_account_links( $menu_links ){
        
		// calling function codingkart_customer_check_user_type with help of BaseCustomer class object
		$mBaseCustomer=new BaseCustomer();
		$check_type=$mBaseCustomer->codingkart_customer_check_user_type('subscriber');
		if(!$check_type)
		{
		unset( $menu_links['wallet'] ); // wallet
		}
		return $menu_links;
	 
	}
	
	 /**
     * Remove wallet page for vendore type from my account
     */
	public function codingkart_redirect_wallet_link(){
	    global $wp;
		// calling function codingkart_customer_check_user_type with help of BaseCustomer class object
		$mBaseCustomer=new BaseCustomer();
		$check_type=$mBaseCustomer->codingkart_customer_check_user_type('subscriber');
		
		if(!$check_type && isset($wp->query_vars['wallet'])==1)
		{
		    wp_safe_redirect(dokan_get_page_url( 'myaccount', 'woocommerce'));exit; 
		}
	}


	/**
     * Remove menu items from dasboard menu
    */
	public function codingkart_dokan_remove_dashboard_menus( $menus ) {
 
		unset($menus['coupons']);

		// remove social tab from dashboard settings menu
		unset($menus['settings']['sub']['social']);

		// remove seo tab from dashboard settings menu
		unset($menus['settings']['sub']['seo']);

		// remove tools tab from dashboard settings menu
		unset($menus['tools']);

		// remove update products tab from dashboard settings menu
		unset($menus['update_products']);

		// remove reviews tab from dashboard settings menu
		unset($menus['reviews']);

	    return $menus;
	}

	/**
     * Unique Vendor Registration Data
    */
	public function codingkart_unique_vendor_registration_data($errors, $username, $user_email){

		$obj = new WPUF_Render_Form();
		$users = get_users( array( 'fields' => array( 'ID' ) ) );
	    
	    // unique vendor store name
		if(isset($_POST['dokan_store_name']))
		{
			foreach($users as $user_id){
	            $shopurl = get_user_meta( $user_id->ID, 'dokan_store_name', true );
	            $shopurl_field = trim( $_POST['dokan_store_name'] );

	            if ($shopurl == $shopurl_field) {
	            	$obj->send_error( __( 'Shop Name already exists.', 'wpuf-pro' ) );
	            }
			}
	    }

		// unique vendor store url
		if(isset($_POST['shopurl']))
		{
			foreach($users as $user_id){
	            $shopurl = get_user_meta( $user_id->ID, 'shopurl', true );
	            $shopurl_field = trim( $_POST['shopurl'] );

	            if ($shopurl == $shopurl_field) {
	            	$obj->send_error( __( 'Shop URL already exists.', 'wpuf-pro' ) );
	            }
			}
	    }

	    return $errors;
	}

	/**
     * Custom vendor progressbar profile completion values
    */
	public function codingkart_custom_profile_completion_values($progress_values){
		$progress_values = array(
           'banner_val'          => 20,
           'profile_picture_val' => 20,
           'store_name_val'      => 20,
           'payment_method_val'  => 30,
           'phone_val'           => 10,
        );
		return $progress_values;
	}

	/**
     * Add fields to user(vendor) profile
     *
     * @param WP_User $user
     *
     * @return void|false
     */
	public function codingkart_add_user_vendor_meta_fields( $user ) {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
            return;
        }

        if ( ! user_can( $user, 'dokandar' ) ) {
            return;
        }

        $verified_supplier 	= get_the_author_meta( 'dokan_verified_supplier', $user->ID); ?>

	    <table class="form-table">
	        <tr>
	            <th><?php esc_html_e( 'Verified supplier', 'dokan-lite' ); ?></th>
	            <td>
	                <label for="verified_supplier">
	                    <input type="hidden" name="verified_supplier" value="no">
	                    <input type="checkbox" name="verified_supplier" <?php if ($verified_supplier == 'yes' ) { ?>checked="checked"<?php }?> value="yes" />
	                    <?php esc_html_e( 'Mark as Verified supplier', 'dokan-lite' ); ?>
	                </label>

	                <p class="description"><?php esc_html_e( 'This vendor will be marked as a Verified supplier.', 'dokan-lite' ) ?></p>
	            </td>
	        </tr>
	        <?php do_action( 'dokan_seller_meta_fields', $user ); ?>
	        <?php
                wp_nonce_field( 'dokan_update_user_profile_info', 'dokan_update_user_profile_info_nonce' );
            ?>
	    </table>

		<?php
	}

	/**
     * Update to user(vendor) profile
     *
     * @param WP_User $user
     *
     * @return void|false
     */
	public function codingkart_update_user_vendor_meta_fields( $user_id ) {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
            return;
        }

        if ( isset( $_POST['dokan_update_user_profile_info_nonce'] ) && ! wp_verify_nonce( $_POST['dokan_update_user_profile_info_nonce'], 'dokan_update_user_profile_info' ) ) {
            return;
        }

        update_usermeta( $user_id, 'dokan_verified_supplier', $_POST['verified_supplier'] );
	}

	/**
     * Check Verified User
    */
	public function codingkart_check_verified_user($user_id){
		$verified_supplier 	= get_the_author_meta( 'dokan_verified_supplier', $user_id);
		
		if( $verified_supplier == yes){ ?>
			<span class="verified-supplier-text">Verified Booster Supplier <i class="fa fa-check fa-1" aria-hidden="true"></i></span>
		<?php 
		}else{
			return false;
		}
	}

	/**
     * Hide fields from Vendor setup wizard
    */
	public function codingkart_hide_fields_from_vendor_setup_wizard(){ ?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("form.dokan-seller-setup-form label[for=store_ppp], form.dokan-seller-setup-form label[for=show_email], form.dokan-seller-setup-form input#show_email, form.dokan-seller-setup-form input#store_ppp").parent().css( "display", "none" );
			});
		</script>
	<?php }

}
new BaseAccount();
?>
