<?php
/**
 * Base Product Integration class.
 *
 * The Bases Product Integration Class and may extend by chlid class to get the comman functionlity .
 *
 * @class    BaseProductIntegration
 * @category Class
 * @author   Codingkart
 */  
class BaseProductIntegration 
{
	 /**
     * Constructor for the EditAddress class
     *
     * Sets up all the appropriate hooks and actions
     * 
     */
    public function __construct() {  

    	// Product integration custom styles and scripts
    	add_action( 'wp_enqueue_scripts',  array( $this, 'product_integration_styles' ));

		// Product integration menus
		add_filter( 'dokan_query_var_filter',  array( $this, 'codingkart_dokan_load_document_menu' ));
		
		// Templates for Product integration page
		add_filter( 'dokan_load_custom_template',  array( $this, 'codingkart_dokan_product_integration_template' ));

		// Import Products Magento popup form ajax function
		add_action( 'wp_ajax_codingkart_import_products_magento_credentials_form_ajax', array($this,'codingkart_import_products_magento_credentials_form_ajax') );
	}

	/**
     *  Product integration custom styles and scripts
     */
	public function product_integration_styles() { 
	    wp_enqueue_style( 'style',get_stylesheet_directory_uri(). '/product-integration/css/styles.css' );
	    wp_enqueue_script( 'script',get_stylesheet_directory_uri(). '/product-integration/js/product_integration.js' );
	}

	/**
     *  Product integration menu
     */
	public function codingkart_dokan_load_document_menu( $query_vars ) {
		// Import Products tab
	    $query_vars['import-products'] = 'import-products';

	    // Update Products tab
	    $query_vars['update-products'] = 'update-products';
	    return $query_vars;
	}

	/**
     *  Template for Product integration page
     */
	public function codingkart_dokan_product_integration_template( $query_vars ) {
		// Import Products template
	    if ( isset( $query_vars['import-products'] ) ) {
	        include_once get_stylesheet_directory(). '/product-integration/template-import-products.php';
	        exit();
	    }

	    // Update Products template
	    if ( isset( $query_vars['update-products'] ) ) {
	        include_once get_stylesheet_directory(). '/product-integration/template-update-products.php';
	        exit();
	    }
	}

	
	/**
     *  Product integration popup form ajax function
     */
	public function codingkart_import_products_magento_credentials_form_ajax(){
		// get the access key and secret key
		$url 			= $_POST['url'];
		$consumer_key 	= $_POST['consumer_key'];
		$token 			= $_POST['token'];
		$vendor_id 		= get_current_user_id();

		require(ABSPATH . 'sns/aws/aws-autoloader.php');
		$sdk = new Aws\Sns\SnsClient([
		    'region'  => 'us-west-2',
		    'version' => 'latest',
		    'credentials' => ['key' => 'AKIAJ74RUIPLH56M242A', 'secret' => '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']
		  ]);
		  
		$result = $sdk->publish(['TopicArn' => 'arn:aws:sns:us-west-2:956449821269:autobooster_integration', 'Message' => '{"vendor_id":'.$vendor_id.', "api_details": {"url": "'.$url.'", "consumer_key": "'.$consumer_key.'", "Token": "'.$token.'"}, "integration_type": "magento"}', 'Subject' => 'SQS Message send']);

			unset($_POST['post_id']);
			unset($_POST['action']);
			$serialized_data=serialize($_POST);
			update_user_meta($vendor_id, '_import_products_magento_crednetials', $serialized_data);
			update_user_meta($vendor_id, '_import_products_magento_status'.$post_id, 1);
			die;
	}

	/**
     *  Successful integration popup
     */
	public function codingkart_successful_integration_popup(){ ?>
		<!-- Modal -->
		<div id="successful_integration_popup" class="modal fade" role="dialog">
		  	<div class="modal-dialog">
			  	<!-- Modal content-->
			    <div class="modal-content" style="margin-top: 40%;">
			      <div class="modal-body">
			        <p>Successful Integration.</p>
			      </div>
			    </div>
			</div>
		</div>
	<?php }
	
}
new BaseProductIntegration();
?>