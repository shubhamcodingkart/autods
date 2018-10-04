<?php
//include_once $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/dokan-child-theme/classes/customers/BaseCustomer.php';
/**
 * EditAddress Class.
 *
 * The EditAddress Account Class and may extend by chlid class to get the comman functionlity .
 *
 * @class    BaseCustomer
 * @category Class
 * @author   Codingkart
 */ 

class EditAddress extends BaseAccount  
{
       
    /**
     * Constructor for the EditAddress class
     *
     * Sets up all the appropriate hooks and actions
     * 
     */
    public function __construct() {  
        
	  	// Adding new extra field on edit account form 
	  	add_action( 'woocommerce_edit_account_form',  array( $this,  'codingkart_woocommerce_edit_account_form' ) );

	  	// Saving data for new extra field on edit account form 
      	add_action( 'woocommerce_save_account_details',  array( $this,  'codingkart_woocommerce_save_account_details' ) ); 
	
	}
	
	
	/**
     * Adding new extra field on edit account form 
     */
    public function codingkart_woocommerce_edit_account_form() {
          
		  	$user_id = get_current_user_id();
		  	$user = get_userdata( $user_id );
		  
		  	// calling function codingkart_customer_check_user_type with help of BaseCustomer class object
		  	$mBaseCustomer=new BaseCustomer();
		  	$check_type=$mBaseCustomer->codingkart_customer_check_user_type('subscriber');
		  
		  	if($check_type)
		  	{
		  	?>
			  	<div class="custom-wpuf-form">
			  		<label for="url">AutoDS Tokens:</label>
					<table>
						<tbody>
							<?php 
								$autods_tokens = $mBaseCustomer->codingkart_get_user_all_tokens($user_id);
								if (empty($autods_tokens)) { ?>
									<tr>
			                            <td>
			                                <input class="wpuf-autods_token" name="autods_token[]" placeholder="Enter token" value="" type="text">
			                            </td>
			                            <td>
			                                <img style="cursor:pointer; margin:0 3px;" alt="add another choice" title="add another choice" class="wpuf-clone-field" src="<?php echo site_url().'/wp-content/plugins/wp-user-frontend/assets/images/add.png';?>">
			                                <img style="cursor:pointer;" class="wpuf-remove-field" alt="remove this choice" title="remove this choice" src="<?php echo site_url().'/wp-content/plugins/wp-user-frontend/assets/images/remove.png';?>">
			                            </td>
		                        	</tr>
								<?php }else{
	               				foreach ($autods_tokens as $key => $autods_token) { ?>
				                <tr>
		                            <td>
		                                <input class="wpuf-autods_token" name="autods_token[]" placeholder="Enter token" value="<?php echo $autods_token; ?>"  type="text">
		                            </td>
		                            <td>
		                                <img style="cursor:pointer; margin:0 3px;" alt="add another choice" title="add another choice" class="wpuf-clone-field" src="<?php echo site_url().'/wp-content/plugins/wp-user-frontend/assets/images/add.png';?>">
		                                <img style="cursor:pointer;" class="wpuf-remove-field" alt="remove this choice" title="remove this choice" src="<?php echo site_url().'/wp-content/plugins/wp-user-frontend/assets/images/remove.png';?>">
		                            </td>
	                        	</tr> 
				            <?php } }?>
							
						</tbody>
	            	</table>
	            </div>
			<?php
			}	

    }
     
	/**
     * Saving data for new extra field on edit account form 
     */
    public function codingkart_woocommerce_save_account_details() {	 
    	$user_id = get_current_user_id();
    	$array = $_POST[ 'autods_token' ];
    	$array_tokens = array_filter( $array, 'strlen' );
    	update_user_meta($user_id, 'set_autods_token', $array_tokens[0]);
    	$autods_tokens_array = implode("|", $array_tokens );
    	update_user_meta( $user_id, 'autods_token', $autods_tokens_array ); 
	}
     
}
new EditAddress();
?>