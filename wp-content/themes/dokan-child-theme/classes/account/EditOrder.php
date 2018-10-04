<?php
/**
 * @class    EditOrder
 * @category Class
 * @author   Codingkart
 */  
class EditOrder extends BaseAccount 
{	
	/**
     * Constructor for the EditOrder class
     *
     * Sets up all the appropriate hooks and actions
     * 
     */
    public function __construct() {
    	
    	// Register in wc_order_statuses 
		add_action( 'init', array( $this, 'codingkart_woocommerce_register_order_statuses'), 10);

		// new order statuses
		add_action( 'wc_order_statuses', array( $this, 'codingkart_woocommerce_new_order_statuses'), 10);

		// shipping status popup ajax 
		add_action( 'wp_ajax_codingkart_order_status_shipping_form_ajax_fn', array($this,'codingkart_order_status_shipping_form_ajax_fn') );
        add_action( 'wp_ajax_nopriv_codingkart_order_status_shipping_form_ajax_fn', array($this,'codingkart_order_status_shipping_form_ajax_fn' ));

        // crate new order api
        add_action( 'wp_ajax_codingkart_create_order_api', array($this,'codingkart_create_order_api') );
        
        // update new order meta
        add_action( 'wp_ajax_codingkart_assign_vendor_to_new_order', array($this,'codingkart_assign_vendor_to_new_order') );

        // Order status change email
        add_action("woocommerce_api_create_order", array($this,"codingkart_woocommerce_order_status_change_email"));
        
        // BaseRest Class Object
        $this->BaseRest_obj = new BaseRest;
    }

    /**
     *  Register in wc_order_statuses 
    */
    public function codingkart_woocommerce_register_order_statuses() {
	    register_post_status( 'wc-shipped', array(
	        'label'                     => _x( 'Shipped', 'Order status', 'dokan-child-theme' ),
	        'public'                    => true,
	        'exclude_from_search'       => false,
	        'show_in_admin_all_list'    => true,
	        'show_in_admin_status_list' => true,
	        'label_count'               => _n_noop( 'Shipped <span class="count">(%s)</span>', 'Shipped<span class="count">(%s)</span>')
	    ) );

	    register_post_status( 'wc-approved', array(
	        'label'                     => _x( 'Approved', 'Order status', 'dokan-child-theme' ),
	        'public'                    => true,
	        'exclude_from_search'       => false,
	        'show_in_admin_all_list'    => true,
	        'show_in_admin_status_list' => true,
	        'label_count'               => _n_noop( 'Approved <span class="count">(%s)</span>', 'Approved<span class="count">(%s)</span>')
	    ) );

	    
	}

	/**
     *  new order statuses
    */
	public function codingkart_woocommerce_new_order_statuses( $order_statuses ) {
	    $order_statuses['wc-shipped'] = _x( 'Shipped', 'Order status', 'dokan-child-theme' );
	    $order_statuses['wc-approved'] = _x( 'Approved', 'Order status', 'dokan-child-theme' );

	    return $order_statuses;
	}

	/**
     *  get dokan order status with custom orders
    */
	public function dokan_count_orders_with_custom_orders( $user_id ) {
	    global $wpdb;

	    $cache_group = 'dokan_seller_data_'.$user_id;
	    $cache_key   = 'dokan-count-orders-' . $user_id;
	  	//$counts      = wp_cache_get( $cache_key, $cache_group );

	    //if ( $counts === false ) {
	        $counts = array('wc-pending' => 0, 'wc-completed' => 0, 'wc-on-hold' => 0, 'wc-processing' => 0, 'wc-refunded' => 0, 'wc-cancelled' => 0, 'wc-shipped' => 0, 'wc-approved' => 0, 'total' => 0);

	        $sql = "SELECT do.order_status
	                FROM {$wpdb->prefix}dokan_orders AS do
	                LEFT JOIN $wpdb->posts p ON do.order_id = p.ID
	                WHERE
	                    do.seller_id = %d AND
	                    p.post_type = 'shop_order' AND
	                    p.post_status != 'trash'";

	        $results = $wpdb->get_results( $wpdb->prepare( $sql, $user_id ) );

	        if ($results) {
	            $total = 0;

	            foreach ($results as $order) {
	                if ( isset( $counts[$order->order_status] ) ) {
	                    $counts[$order->order_status] += 1;
	                    $counts['total'] += 1;
	                }
	            }
	        }

	        $counts = (object) $counts;
	        wp_cache_set( $cache_key, $counts, $cache_group );
	        dokan_cache_update_group( $cache_key , $cache_group );
	    //}

	    return $counts;
	}


	/**
     *  show new status in dashboard orders
    */
	public 	function codingkart_custom_dokan_order_listing_status_filter() {
		$status_class         = isset( $_GET['order_status'] ) ? $_GET['order_status'] : 'all';
	    $orders_counts        = $this->dokan_count_orders_with_custom_orders( dokan_get_current_user_id() );
	    $order_date           = ( isset( $_GET['order_date'] ) ) ? $_GET['order_date'] : '';
	    $date_filter          = array();
	    $all_order_url        = array();
	    $complete_order_url   = array();
	    $processing_order_url = array();
	    $pending_order_url    = array();
	    $on_hold_order_url    = array();
	    $cancelled_order_url  = array();
	    $refund_order_url     = array();
	    $shipped_order_url    = array();
	    $approved_order_url	  = array();
	    ?>

	    <ul class="list-inline order-statuses-filter abcd">
	        <li<?php echo $status_class == 'all' ? ' class="active"' : ''; ?>>
	            <?php
	                if( $order_date ) {
	                    $date_filter = array(
	                        'order_date' => $order_date,
	                        'dokan_order_filter' => 'Filter',
	                    );
	                }
	                $all_order_url = array_merge( $date_filter, array( 'order_status' => 'all' ) );
	            ?>
	            <a href="<?php //echo ( empty( $all_order_url ) ) ? $orders_url : add_query_arg( $complete_order_url, $orders_url ); 
	            echo home_url().'/index.php/dashboard/orders/';
	            ?>">
	                <?php printf( __( 'All (%d)', 'dokan-lite' ), $orders_counts->total ); ?></span>
	            </a>
	        </li>
	        <li<?php echo $status_class == 'wc-pending' ? ' class="active"' : ''; ?>>
	            <?php
	                if( $order_date ) {
	                    $date_filter = array(
	                        'order_date' => $order_date,
	                        'dokan_order_filter' => 'Filter',
	                    );
	                }
	                $pending_order_url = array_merge( $date_filter, array( 'order_status' => 'wc-pending' ) );
	            ?>
	            <a href="<?php echo add_query_arg( $pending_order_url); ?>">
	                <?php printf( __( 'Pending (%d)', 'dokan-lite' ), $orders_counts->{'wc-pending'} ); ?></span>
	            </a>
	        </li>
	        <li<?php echo $status_class == 'wc-approved' ? ' class="active"' : ''; ?>>
	            <?php
	                if( $order_date ) {
	                    $date_filter = array(
	                        'order_date' => $order_date,
	                        'dokan_order_filter' => 'Filter',
	                    );
	                }
	                $approved_order_url = array_merge( $date_filter, array( 'order_status' => 'wc-approved' ) );
	            ?>
	            <a href="<?php echo add_query_arg( $approved_order_url); ?>">
	                <?php printf( __( 'Approved (%d)', 'dokan-lite' ), $orders_counts->{'wc-approved'} ); ?></span>
	            </a>
	        </li>
	        <li<?php echo $status_class == 'wc-shipped' ? ' class="active"' : ''; ?>>
	            <?php
	                if( $order_date ) {
	                    $date_filter = array(
	                        'order_date' => $order_date,
	                        'dokan_order_filter' => 'Filter',
	                    );
	                }
	                $shipped_order_url = array_merge( $date_filter, array( 'order_status' => 'wc-shipped' ) );
	            ?>
	            <a href="<?php echo add_query_arg( $shipped_order_url); ?>">
	                <?php printf( __( 'Shipped (%d)', 'dokan-lite' ), $orders_counts->{'wc-shipped'} ); ?></span>
	            </a>
	        </li>
	        <li<?php echo $status_class == 'wc-cancelled' ? ' class="active"' : ''; ?>>
	            <?php
	                if( $order_date ) {
	                    $date_filter = array(
	                        'order_date' => $order_date,
	                        'dokan_order_filter' => 'Filter',
	                    );
	                }
	                $cancelled_order_url = array_merge( $date_filter, array( 'order_status' => 'wc-cancelled' ) );
	            ?>
	            <a href="<?php echo add_query_arg( $cancelled_order_url); ?>">
	                <?php printf( __( 'Cancelled (%d)', 'dokan-lite' ), $orders_counts->{'wc-cancelled'} ); ?></span>
	            </a>
	        </li>

	        <li<?php echo $status_class == 'wc-completed' ? ' class="active"' : ''; ?>>
	            <?php
	                if( $order_date ) {
	                    $date_filter = array(
	                        'order_date' => $order_date,
	                        'dokan_order_filter' => 'Filter',
	                    );
	                }
	                $complete_order_url = array_merge( array( 'order_status' => 'wc-completed' ), $date_filter );
	            ?>
	            <a href="<?php echo add_query_arg( $complete_order_url); ?>">
	                <?php printf( __( 'Completed (%d)', 'dokan-lite' ), $orders_counts->{'wc-completed'} ); ?></span>
	            </a>
	        </li>

	        <?php do_action( 'dokan_status_listing_item', $orders_counts ); ?>
	    </ul>
	    <?php //print_r($orders_counts);?>
	    <?php
	}

	/**
     *  shipping status popup ajax
    */
	public function codingkart_order_status_shipping_popup() {
		echo '<!-- Modal -->
		<div id="shipping_status_modal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		    <div class="show-loader"><span class="ajaxloader_footer_nls"><img style="max-width: 80px;" src="https://upload.wikimedia.org/wikipedia/commons/3/3a/Gray_circles_rotate.gif"></span></div>
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		      </div>
		      <div class="modal-body">
		        <form id="order_status_shipping_popup_form" method="post">
		        <p class="error-field"></p>
			    <div class="form-group">
			      <label>Tracking number :</label>
			      <input type="text" class="tracking_number form-control" placeholder="Enter Tracking number" name="tracking_number">
			    </div>
			    <div class="form-group">
			     <label>Shipping career: </label>
			    <div class="ui-widget">
					<select id="selectbox_order_status" class="form-control" name="shipping_career">
					    <option value="">Select one...</option>
					    <option value="UPS">UPS</option>
					    <option value="USPS">USPS</option>
					    <option value="FedEx">FedEx</option>
					    <option value="China_Post">China Post</option>
					    <option value="ePacket">ePacket</option>
					  </select>
				</div>
				</div>
				<input type="hidden" name="order_status" value="wc-shipped">
			    <button type="submit" class="btn btn-default">Submit</button>
			  </form>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      </div>
		    </div>

		  </div>
		</div>';

	}

	/**
     *  change order status to shipped and update meta
    */
	public function codingkart_order_status_shipping_form_ajax_fn() {
		global $wpdb;

		$order_id = $_POST['order_id'];
		$order_status = $_POST['order_status'];

		// $the_order = new WC_Order( $order_id );

		if($order_status == 'wc-shipped'){
			$arg = array(
			    'ID' => $order_id,
			    'post_status' => $order_status,
			);
			wp_update_post( $arg );
			// $the_order->update_status('wc-shipped');
			update_post_meta($order_id,'_tracking_number',$_POST['tracking_number']);
			update_post_meta($order_id,'_shipping_career',$_POST['shipping_career']);

			$wpdb->update('wp_dokan_orders', array('order_id'=>$order_id, 'order_status'=>$order_status), array('order_id'=>$order_id));

			// update wp_dokan_vendor_balance table
			$wpdb->query( $wpdb->prepare("UPDATE `wp_dokan_vendor_balance` SET `status` = '".$order_status."' WHERE `trn_id` = ".$order_id));
			
			//$this->codingkart_order_status_changed_to_shipped($order_id);

			// API call - order status changed to shipped
			$this->codingkart_send_api_call_order_status_changed_to_shipped($order_id);

		}else{
			// $the_order->update_status($order_status);
			$arg = array(
			    'ID' => $order_id,
			    'post_status' => $order_status,
			);
			wp_update_post( $arg );
			$wpdb->update('wp_dokan_orders', array('order_id'=>$order_id, 'order_status'=>$order_status), array('order_id'=>$order_id));

			// update wp_dokan_vendor_balance table
			$wpdb->query( $wpdb->prepare("UPDATE `wp_dokan_vendor_balance` SET `status` = '".$order_status."' WHERE `trn_id` = ".$order_id));

		}

	}



	/**
     *  crate new order api
    */
	public function codingkart_create_order_api(){
		# Our new data
		$array = [
		    'payment_method' => 'bacs',
		    'payment_method_title' => 'Direct Bank Transfer',
		    'set_paid' => true,
		    'billing' => [
		        'first_name' => 'John',
		        'last_name' => 'Doe',
		        'address_1' => '969 Market',
		        'address_2' => '',
		        'city' => 'San Francisco',
		        'state' => 'CA',
		        'postcode' => '94103',
		        'country' => 'US',
		        'email' => 'john.doe@example.com',
		        'phone' => '(555) 555-5555'
		    ],
		    'shipping' => [
		        'first_name' => 'John',
		        'last_name' => 'Doe',
		        'address_1' => '969 Market',
		        'address_2' => '',
		        'city' => 'San Francisco',
		        'state' => 'CA',
		        'postcode' => '94103',
		        'country' => 'US'
		    ],
		    'line_items' => [
		        [
		            'product_id' => 126,
		            'quantity' => 1
		        ]
		    ],
		];

		$url = site_url().'/wp-json/wc/v2/orders?customer_id=128';
		$data = json_encode($array);
		$make_call = $this->BaseRest_obj->codingkart_rest_callAPI('POST',$url,$data);
		print_r($make_call);
		die;
	}

	/**
     *  api call on order status changed to shipped
    */
	public function codingkart_order_status_changed_to_shipped($order_id){
		$data = $this->BaseRest_obj->codingkart_woocommerce_get_api_response(site_url().'/wp-json/wc/v2/orders/', $order_id);
		wp_send_json($data);
	}

	/**
     *  Order status change email
    */
	public function codingkart_woocommerce_order_status_change_email($order_id) {
		// Define a constant to use with html emails
		//define("HTML_EMAIL_HEADERS", array('Content-Type: text/html; charset=UTF-8'));

		$order_post_author_id = get_post_field( 'post_author', $order_id );
		$user_info = get_userdata($order_post_author_id);
      	$vendor_email = $user_info->user_email;

      	$email = $vendor_email;
		$subject = 'You got a new Order on AutoBooster!'; 
		$heading = 'You got a new Order on AutoBooster!';

		//$order_url = home_url().'/dashboard/orders/?order_id='.$order_id;
		$order_url = wp_nonce_url( add_query_arg( array( 'order_id' => $order_id ), dokan_get_navigation_url( 'orders' ) ), 'dokan_view_order' );
		$message = 'You got a new order on AutoBooster, To check the order <a href="'.$order_url.'">CLICK HERE</a>, Have a great day 🙂';

		// Get woocommerce mailer from instance
		$mailer = WC()->mailer();

		// Wrap message using woocommerce html email template
		 $wrapped_message = $mailer->wrap_message($heading, $message);

		// Create new WC_Email instance
		$wc_email = new WC_Email;

		// Style the wrapped message with woocommerce inline styles
		$html_message = $wc_email->style_inline($wrapped_message);

		// Send the email using wordpress mail function
		wp_mail( $email, $subject, $html_message,  array('Content-Type: text/html; charset=UTF-8') );
	}

	/**
     *  API call - order status changed to shipped
    */
	public function codingkart_send_api_call_order_status_changed_to_shipped($order_id){
		
		$vendor_id 		 = get_post_field( 'post_author', $order_id );
		$user_info 		 = get_userdata($vendor_id);
  		$vendor_username = $user_info->user_login;

  		$tracking_number = get_post_meta($order_id,'_tracking_number', true);
		$shipping_career = get_post_meta($order_id,'_shipping_career', true);

		$array = array('status_id'=>8, 'order_id'=>$order_id, 'error_message'=>'', 'shipping_carrier'=>$shipping_career, 'tracking_number'=>$tracking_number, 'search_by_order_id'=>True);

		//$api_url 		= $this->BaseRest_obj->codingkart_get_api_url_stating_or_live();
		$api_url 		= api_url;
		$url 			= $api_url.'/api/db_handler/orders/autobooster/'.$vendor_username.'/'.$order_id.'/'.$order_id;

       	$data 				= json_encode($array);
		$make_call  		= $this->BaseRest_obj->codingkart_rest_callAPI('PUT',$url,$data);

	    print_r($make_call);
	    die;
    }

}
new EditOrder();
?>