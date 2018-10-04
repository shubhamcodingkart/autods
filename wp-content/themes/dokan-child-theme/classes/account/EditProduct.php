<?php
/**
 * EditProduct class.
 *
 *
 * @class    EditProduct
 * @category Class
 * @author   Codingkart
 */  
class EditProduct extends BaseAccount
{

     /**
     * Constructor for the EditProduct class
     *
     * Sets up all the appropriate hooks and actions
     * 
     */
    public function __construct() {

    	// Add new Blank product
        add_action( 'wp_ajax_codingkart_add_new_blank_product', array($this,'codingkart_add_new_blank_product') ); 

        // Update meta if product is updated
        add_action( 'dokan_product_updated', array($this,'codingkart_update_custom_fields') ); 

        //add_action( 'template_redirect', array( $this, 'codingkart_update_custom_fields' ), 12 ); 

        // API call - on product update
        add_action( 'dokan_product_updated', array($this,'codingkart_send_api_call_on_product_update') ); 

        // Product CSV import mandatory SKU
        add_filter( 'woocommerce_csv_product_import_mapped_columns', array($this,'codingkart_product_import_mandatory_sku'), 10, 2 );

        // Register the Custom columns in the importer.
        add_filter( 'woocommerce_csv_product_import_mapping_options', array($this,'codingkart_add_column_to_importer') );

        // Add automatic mapping support for Custom Columns.
        add_filter( 'woocommerce_csv_product_import_mapping_default_columns', array($this,'codingkart_add_column_to_mapping_screen' ) );

        // Process the data read from the CSV file.
        add_filter( 'woocommerce_product_import_pre_insert_product_object', array($this,'codingkart_process_import'), 10, 2 );
         
		// BaseRest Class Object
        $this->BaseRest_obj = new BaseRest; 

    }

    /**
     *  Add new Blank product
    */
    public function codingkart_add_new_blank_product(){
  		$args = array(
	    	'author'        =>  get_current_user_id(),
			'post_type'		=>	'product',
			'meta_query'	=>	array(
				array(
					'key' => '_ck_valid_product',
					'value'	=>	0
				)
			)
		);
		$my_query = new WP_Query( $args );

		// execute the main query
		$the_main_loop = new WP_Query($args);

		if( $my_query->have_posts() ) {
		  while( $my_query->have_posts() ) {
		    $my_query->the_post();
		    $blank_post_id = get_the_id();
		    // Do your work...
		  } // end while
		} // end if
		wp_reset_postdata();

		// if blank product exist
		if($blank_post_id){
			echo $blank_post_id;
			die;
		}else{ //create a new blank product
			$id = wp_insert_post(array('post_title'=>'Blank', 'post_type'=>'product', 'post_content'=>'', 'post_status'=>'draft', 'post_author'=>get_current_user_id()));
			update_post_meta($id, '_ck_valid_product' , 0);
			print_r($id);
			die;
		}
	}

	/**
     *  Update meta if product is updated
    */
	public function codingkart_update_custom_fields($post_id){
		
		// add custom fields to new product page
		if( isset( $_POST['product_brand'] ) && !empty( $_POST['product_brand'] ) ) {
			update_post_meta($post_id, '_product_brand' , $_POST['product_brand']);
		}

		if( isset( $_POST['product_manufacture'] ) && !empty( $_POST['product_manufacture'] ) ) {
			update_post_meta($post_id, '_product_manufacture' , $_POST['product_manufacture']);
		}

		if( isset( $_POST['product_model'] ) && !empty( $_POST['product_model'] ) ) {
			update_post_meta($post_id, '_product_model' , $_POST['product_model']);
		}

		if( isset( $_POST['product_UPC'] ) && !empty( $_POST['product_UPC'] ) ) {
			update_post_meta($post_id, '_product_UPC' , $_POST['product_UPC']);
		}

		if( isset( $_POST['product_EAN'] ) && !empty( $_POST['product_EAN'] ) ) {
			update_post_meta($post_id, '_product_EAN' , $_POST['product_EAN']);
		}

		if( isset( $_POST['custom_product_tag'] ) && !empty( $_POST['custom_product_tag'] ) ) {
            $tags_ids = (array)$_POST['custom_product_tag'];
            wp_set_object_terms( $post_id, $tags_ids, 'product_tag' );
        }

        // update status of blank product from draft to pending
		$page = get_page( $post_id );
	  	if ($page->post_status == 'draft') {
	    	wp_update_post(array('ID' => $post_id,'post_status' => 'pending'));
		}

	  	// make it valid
	    delete_post_meta($post_id, '_ck_valid_product');
	}

	/**
     *  Product CSV import mandatory SKU
    */
	public function codingkart_product_import_mandatory_sku( $headers, $raw_headers ){
		if (!in_array('sku', $headers)) {
			echo "<p class='sku-mandatory'>SKU is Mandatory</p>";
			die;
		}
		return $headers;
	}

	/**
     *  API call - on product update
    */
	public function codingkart_send_api_call_on_product_update($post_id){
		$product = wc_get_product( $post_id );
		
		// get product images-------------------------------
		$attachment_ids = $product->get_gallery_attachment_ids();

        // Add featured image.
        if ( has_post_thumbnail( $product->get_id() ) ) {
            $attachment_ids[] = $product->get_image_id();
        }

        foreach( $attachment_ids as $attachment_id ) 
        {
          	// Display the image URL
        	$img_urls[] = wp_get_attachment_url( $attachment_id );
		}
		// get product images-------------------------------

		// custom fields
		$brand = get_post_meta($product->get_id(), '_product_brand', true);
		$manufacture = get_post_meta($product->get_id(), '_product_manufacture', true);

        $array = array(
			'item_data' => 
			  	array(
			    'features' 			=> '',
			    'sold_by' 			=> '',
			    'shipping_weight' 	=> '',
			    'item_input_id' 	=> $product->get_id(),
			    'id' 				=> $product->get_id(),
			    'shipping_price' 	=> 0,
			    'shipping_time' 	=> '',
			    'store_name' 		=> 'autobooster',
			    'item_specific' 	=> 
			    array(
			      	'Item Weight' 		=> $product->get_weight( $context ),
			      	'Shipping Weight' 	=> '',
					      'dimensions'  => array(
		                    'package_dimensions'=>array(
		                    'width'  => $product->get_width( $context ),
		                    'length' => $product->get_length( $context ),
		                    'weight' => $product->get_weight( $context ),
		                    'height' => $product->get_height( $context ),
		                ),
		            ),
			      	'Manufacturer' 	 => $manufacture,
			    ),
			    'price' 		=> $product->get_price( $context ),
			    'description' 	=> 'view' === $context ? wpautop( do_shortcode( $product->get_description() ) ) : $product->get_description( $context ),
			    'brand' 		=> $brand,
			    'item_weight' 	=> $product->get_weight( $context ),
			    'in_stock' 		=> $product->managing_stock(),
			    'manufacturer' 	=> $manufacture,
			    'in_stock_qty' 	=> $product->get_stock_quantity( $context ),
			    'url' 			=> $product->get_permalink(),
			    'title' 		=> $product->get_name( $context ),
			    'variations' 	=> array(),
			    'all_images' 	=>$img_urls,
			),
		);

        //$api_url 		= $this->BaseRest_obj->codingkart_get_api_url_stating_or_live();
        $api_url 		= api_url;
        $url 			= $api_url.'api/ebay_api/update_monitoring_products';
		$data 			= json_encode( $array );

		$make_call 	= $this->BaseRest_obj->codingkart_rest_callAPI('POST',$url,$data);
		
		if ($make_call != 'Connection Failure') {
			return $make_call;
		}
		
	}

	/**
	 * Register the Custom columns in the importer.
	 *
	 * @param array $options
	 * @return array $options
	 */
	public function codingkart_add_column_to_importer( $options ) {

	    // column slug => column name
	    $options['_product_brand']          = 'Brand';
	    $options['_product_manufacture']    = 'Manufacturer';
	    $options['_product_model']          = 'Model';
	    $options['_product_UPC']            = 'UPC';
	    $options['_product_EAN']            = 'EAN';

	    return $options;
	}

	/**
	 * Add automatic mapping support for Custom Columns. 
	 * This will automatically select the correct mapping for columns named 'Custom Column' or 'custom column'.
	 *
	 * @param array $columns
	 * @return array $columns
	 */
	public function codingkart_add_column_to_mapping_screen( $columns ) {
	    
	    // potential column name => column slug
	    $columns['Brand']           = '_product_brand';
	    $columns['Manufacturer']    = '_product_manufacture';
	    $columns['Model']           = '_product_model';
	    $columns['UPC']             = '_product_UPC';
	    $columns['EAN']             = '_product_EAN';

	    return $columns;
	}

	/**
	 * Process the data read from the CSV file.
	 * This just saves the value in meta data, but you can do anything you want here with the data.
	 *
	 * @param WC_Product $object - Product being imported or updated.
	 * @param array $data - CSV data read for the product.
	 * @return WC_Product $object
	 */
	public function codingkart_process_import( $object, $data ) {
	    
	    if ( ! empty( $data['_product_brand'] ) ) {
	        $object->update_meta_data( '_product_brand', $data['_product_brand'] );
	    }

	    if ( ! empty( $data['_product_manufacture'] ) ) {
	        $object->update_meta_data( '_product_manufacture', $data['_product_manufacture'] );
	    }

	    if ( ! empty( $data['_product_model'] ) ) {
	        $object->update_meta_data( '_product_model', $data['_product_model'] );
	    }

	    if ( ! empty( $data['_product_UPC'] ) ) {
	        $object->update_meta_data( '_product_UPC', $data['_product_UPC'] );
	    }

	    if ( ! empty( $data['_product_EAN'] ) ) {
	        $object->update_meta_data( '_product_EAN', $data['_product_EAN'] );
	    }

	    return $object;
	}

}
new EditProduct();
?>