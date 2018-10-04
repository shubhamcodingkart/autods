<?php

if( ! defined ( 'ABSPATH' ) )
		exit;

if( ! function_exists( 'woocommerce_customer_phone_number' ) ){

	function woocommerce_customer_phone_number(){

		require_once( WC_WALLET . 'includes/templates/front/myaccount/registration.php' );

	}

}

if( ! function_exists( 'woocommerce_customer_phone_number_validation' ) ){

	function woocommerce_customer_phone_number_validation(){

		if ( isset( $_POST['reg_user_phone_number'] ) && empty( $_POST['reg_user_phone_number'] ) ) {

						$validation_errors->add( 'reg_user_phone_number_error', __( '<strong>Error</strong>: Phone number is required!', 'woocommerce' ) );

		}

	}

}

if( ! function_exists( 'woocommerce_customer_save_phone_number' ) ){

	function woocommerce_customer_save_phone_number( $customer_id ){

		if ( isset( $_POST['reg_user_phone_number'] ) ) {

			update_user_meta( $customer_id, 'wp_user_phone', sanitize_text_field( $_POST['reg_user_phone_number'] ) );

		}

	}

}

if( ! function_exists ( 'woocommerce_wallet_product_metabox' ) ){

	function woocommerce_wallet_product_metabox(){

		$page = get_page_by_title('Wallet', OBJECT, 'product');
		$wallet_id = $page->ID;

		if( is_admin() && isset( $_GET[ 'post' ] ) && $_GET[ 'post' ] != $wallet_id )

			require_once( WC_WALLET . 'includes/admin/product/metabox.php' );

		else{

			?>

				<script>
					document.addEventListener( "DOMContentLoaded", function(e){
						var wallet = '<?php echo $wallet_id; ?>';
						var post   = '<?php echo $_GET[ "post" ] ?>';
						if( wallet == post ){

							var update = document.getElementById( "publish" );

							update.setAttribute( "disabled", "disabled" );

						}
					});
				</script>

			<?php

		}

	}

}

if( ! function_exists ( 'woocommerce_wallet_cashback_on_product' ) ){

	function woocommerce_wallet_cashback_on_product(){

		require_once( WC_WALLET . 'includes/templates/admin/product/metabox/content.php' );

	}

}

if( ! function_exists( 'wallet_order_processing' ) ){

	function wallet_order_processing($order_id){

		require_once( WC_WALLET . 'includes/front/checkout/order-processing.php' );

	}

}

if( ! function_exists( 'wallet_after_order_completed' ) ){

	function wallet_after_order_completed($order_id){

		require_once( WC_WALLET . 'includes/front/checkout/order-completed.php' );

	}

}

if( ! function_exists( 'wallet_order_cancelled' ) ){

	function wallet_order_cancelled( $order_id ){

		require_once( WC_WALLET . 'includes/front/checkout/order-cancelled.php' );

	}

}

if( ! function_exists( 'woo_add_cart_fee' ) ){

	function woo_add_cart_fee() {

		require WC_WALLET . 'includes/front/checkout/fees.php';

	}

}

if( ! function_exists( 'wallet_payment_gateway_handler' ) ){

	function wallet_payment_gateway_handler( $available_gateways ) {

			require_once( WC_WALLET . 'includes/front/checkout/gateways.php' );

	}

}

if( ! function_exists( 'wallet_template_redirect' ) ){

	function wallet_template_redirect(){

		require_once( WC_WALLET . 'includes/front/cart/restriction.php' );

	}

}

if( ! function_exists( 'wallet_check_product_in_cart' ) ){

	function wallet_check_product_in_cart( $cart_item_data ){

		// require_once( WC_WALLET . 'includes/front/cart/items.php' );

		global $woocommerce;

		$page = get_page_by_title('Wallet', OBJECT, 'product');

		$wallet_id = $page->ID;

		$cart = WC()->cart;

		$get_cart = WC()->cart->cart_contents;

		if(!empty($get_cart)){

			foreach($get_cart as $key => $value ){

				$product_id = $value['product_id'];

				if($product_id == $wallet_id){

					$woocommerce->cart->empty_cart();

					WC()->cart->add_to_cart($wallet_id);

					return false;

				}

			}

		}

		return $cart_item_data;

	}

}

if( ! function_exists( 'woocommerce_wallet_metabox_data_handler' ) ){

	function woocommerce_wallet_metabox_data_handler( $post_id ){

		if( isset( $_POST['wc_wallet_cashback_product_nonce'] ) && wp_verify_nonce( $_POST['wc_wallet_cashback_product_nonce'], 'wc_wallet_cashback_product' ) ){

				update_post_meta( $post_id, '_cashback_min_quantity_restriction', $_POST['cashback_min_quantity_restriction'] );

				update_post_meta( $post_id, '_cashback_type_restriction', $_POST['cashback_type_restriction'] );

				update_post_meta( $post_id, '_cashback_amount_awarded', $_POST['cashback_amount_awarded'] );

		}

	}

}

if( ! function_exists( 'woocommerce_wallet_restrict_orders' ) ){

	function woocommerce_wallet_restrict_orders(){

		global $typenow;

		if ( 'shop_order' == $typenow ) {

			woocommerce_wallet_authorwise_filter();

		}

	}

}

if( ! function_exists( 'woocommerce_wallet_authorwise_filter' ) ){

	function woocommerce_wallet_authorwise_filter(){

		$author_id = '';

		?>

		<select id="transaction" name="transaction" style="width: 15%;" class="enhanced" tabindex="-1" aria-hidden="true">

			<?php

				if( isset( $_GET['transaction'] ) ){

						$wallet_transaction = $_GET['transaction'];

				}

				echo '<option value = "">Payment Gateways</option>';
				echo '<option value = "all">All</option>';
				echo '<option value = "wallet">Wallet</option>';

			?>
		</select>

		<?php

	}

}

if( ! function_exists ( 'woocommerce_wallet_filter_query' ) ){

	function woocommerce_wallet_filter_query( $query ){

		if( !empty( $_GET['transaction'] ) && $_GET['transaction'] == 'wallet' ) {

				$query->query_vars['meta_key']   = '_payment_method';
				$query->query_vars['meta_value'] = $_GET['transaction'];

		}

	}

}
