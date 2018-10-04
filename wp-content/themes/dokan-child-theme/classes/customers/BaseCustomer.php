<?php
/**
 * Base Customer class.
 *
 * The Bases Customer Class and  may extend by chlid class to get the comman functionlity .
 *
 * @class    BaseCustomer
 * @category Class
 * @author   Codingkart
 */  
class BaseCustomer 
{
 	
 	/**
     * Constructor for the EditProduct class
     *
     * Sets up all the appropriate hooks and actions
     * 
     */
    public function __construct() {

    	//Add BCC to contact vendor form
        add_filter( 'woocommerce_email_headers', array($this,'codingkart_add_bcc_to_contact_vendor_form'), 10, 2);

        // Update user Autods tokens 
		add_action( 'wpuf_after_register', array( $this, 'codingkart_update_user_tokens_on_registration') );

		// Update single Autods token ajax function
		add_action( 'wp_ajax_codingkart_update_autods_token_ajax_fn', array($this,'codingkart_update_autods_token_ajax_fn') );

	}

	/**
	* Saving data for new extra field on edit account form 
	*/
	public function codingkart_customer_check_user_type($type) {	 
		
		$user_id = get_current_user_id();
		$user = get_userdata( $user_id );	

		$user_roles=$user->roles;
		  
		if($user_roles[0]==$type)
		{
		$customer_type=true;
		}
		else
		{
		$customer_type=false;
		}
	  return $customer_type;	 
	}

	/**
	 * Add BCC to contact vendor form
	 */
    public function codingkart_add_bcc_to_contact_vendor_form( $headers, $object ) {

    	if ($object == 'dokan_contact_seller') {
	        $headers .= 'BCC: My name <bcc@autoboostertools.com>' . "\r\n";
	    }

	    return $headers;
	}

	/**
     * Update user Autods tokens 
    */
	public function codingkart_update_user_tokens_on_registration($user_id, $form_id, $form_settings){

		$user = get_userdata( $user_id );	
		$user_roles=$user->roles;
		  
		if($user_roles[0]=='subscriber')
		{
			$autods_tokens = get_user_meta($user_id, 'autods_token', true);
			$removeSpaces = str_replace(' ', '', $autods_tokens);
			$autods_tokens_array = explode("|", $removeSpaces );
			
			update_user_meta($user_id, 'autods_token', $removeSpaces);

			$set_autods_tokens = $autods_tokens_array[0];
			update_user_meta($user_id, 'set_autods_token', $set_autods_tokens);
		}

	}

	/**
     * Get user autods tokens 
    */
	public function codingkart_get_user_all_tokens($user_id){
		$get_autods_token = get_user_meta( $user_id, 'autods_token', true );
		$autods_tokens_array = explode("|", $get_autods_token );
		return $autods_tokens_array;
	}

	/**
     * Get single Token of user
    */
	public function codingkart_get_set_autods_token($user_id){
		$set_autods_token = get_user_meta($user_id, 'set_autods_token', true);
		return $set_autods_token;
	}

	/**
     * Set single Token of user
    */
	public function codingkart_update_autods_token_ajax_fn(){
		$user_id = get_current_user_id();
		update_user_meta($user_id, 'set_autods_token', $_POST['selected_token']);
	}

	/**
     * Get User id by token 
     */
    public function codingkart_get_userid_by_autods_token($autods_token){
      	global $wpdb;
        
        $result = $wpdb->get_results ( "SELECT user_id FROM `wp_usermeta` WHERE `meta_key` LIKE '%autods_token%' AND `meta_value` LIKE '%".$autods_token."%'" );

        foreach ($result as $key => $value) {
            
            $array = json_decode(json_encode($value), true);
            
            foreach ($array as $key => $user_ids) {
                $user_id = $user_ids;

                $autods_tokens_array =  $this->codingkart_get_user_all_tokens($user_id);
               
                foreach ($autods_tokens_array as $key => $tokens) {
                    if ($autods_token == $tokens) {
                        $customerid_by_token = $user_id;
                        break;
                    }
                } 
            }
        }

        return $customerid_by_token;
    }

}
//new BaseCustomer();
?>