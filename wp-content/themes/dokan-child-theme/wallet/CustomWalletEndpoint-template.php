<?php if(!defined('ABSPATH')){
	exit;
} ?>

<style type="text/css">
	.wallet-money-form #add_wallet_money{
		display: none;
	}
	.wallet-money-form{
		margin-bottom: 10px;
	}

	.gift-card-amounts{
		padding: 20px;
	}

	.gift-card-amounts .active{
		background: #555555 !important;
		color: #fff !important;
	}
</style>

<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('.add_money_to_wallet_custom').click(function() {
			var amount = jQuery(this).attr('data-amount');
			jQuery('.add_money_to_wallet_custom').removeClass('active');
			jQuery(this).addClass('active');
			jQuery('.wallet-money-form #add_wallet_money').val(amount);
		});
	});
</script>
<?php 
if(isset($_REQUEST['add_wallet_money_button'])){
	if (isset( $_POST['wallet_amount'] ) && wp_verify_nonce($_POST['wallet_amount'], 'add-amount') ){

		$status = 1;
		$wallet_min_credit_res = get_option( 'woocommerce_customer_min_wallet_credit_amount' );

		if( ! empty( $wallet_min_credit_res ) ){

			if( $_POST['add_wallet_money'] < $wallet_min_credit_res ){

				$status = 0;

			}

		}
		if( $status == 1 ){
			if ( ! is_numeric( $_POST['add_wallet_money'] ) ) {
				wc_add_notice( 'Wallet Amount is not a number', 'error' );
				wp_safe_redirect( get_permalink( get_option('woocommerce_myaccount_page_id') ) . '/customer-wallet' );
				die;
			}
			else{
				update_post_meta($_POST['wallet_id'], '_price', $_POST['add_wallet_money']);
				WC()->cart->add_to_cart($_POST['wallet_id']);
				//$url = wc_get_cart_url();
				$url = wc_get_checkout_url();
				wp_safe_redirect( $url );
				exit;
			}

		} else {
			wc_print_notice( sprintf( 'Minimum Wallet Credit Amount is %s', wc_price( $wallet_min_credit_res ) ), 'error' );
		}

	}
}

	?>
	<div class = "wrap">
		<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ) . '/wallet/transfer'; ?>" class="page-title-action">Wallet Transfer</a>
	</div>
	<div class="main-container">
		<div class="add-wallet-wrapper">
			<h4>Remaining Amount:
				<?php
					$user_id = get_current_user_ID();
					$wallet_amount = get_user_meta($user_id, 'wallet-amount', true);
					if(empty($wallet_amount)){
						$wallet_amount = 0;
					}
					echo get_woocommerce_currency_symbol() . number_format($wallet_amount, 2, '.', '');
				?>
			</h4>
			<form class="wallet-money-form" action="" method="POST" enctype="multipart/form-data">
				<?php wp_nonce_field('add-amount','wallet_amount'); ?>
				<input type="text" id="add_wallet_money" class="add_wallet_money" name="add_wallet_money" />
				<label for="add_wallet_money" style="display: none;"><?php echo get_woocommerce_currency_symbol(); ?></label>
				<?php $wallet = get_page_by_title( 'Wallet' , OBJECT, 'product' ); ?>
				<input type="hidden" name="wallet_id" value="<?php echo $wallet->ID; ?>"/>
				
				<div class="gift-card-amounts">
					<a href="javascript:void(0);" class="button add_money_to_wallet_custom" data-amount="50">50$</a>
					<a href="javascript:void(0);" class="button add_money_to_wallet_custom" data-amount="100">100$</a>
					<a href="javascript:void(0);" class="button add_money_to_wallet_custom" data-amount="250">250$</a>
					<a href="javascript:void(0);" class="button add_money_to_wallet_custom" data-amount="500">500$</a>
					<a href="javascript:void(0);" class="button add_money_to_wallet_custom" data-amount="1000">1000$</a>
				</div>
				<input type="submit" value="Add to Wallet" class="add_wallet_money_button" name="add_wallet_money_button" />
				<?php
				 ?>
			</form>
		</div>
		<div class="wallet-transactions-wrapper">
			<h4>Wallet Transactions</h4>
			<?php
			global $wpdb, $wc_transaction;
			$page       	= get_page_by_title('Wallet', OBJECT, 'product');
			$wallet_id  	= $page->ID;

			$paged = !empty(get_query_var('wallet')) ? get_query_var('wallet') : 1;

			if (!is_numeric(get_query_var('wallet'))){
				$paged = 1;
			}

			$pagenum = isset( $paged ) ? absint( $paged ) : 1;
			$limit = 10;
			$offset = ($pagenum==1) ? 0 : ($pagenum-1) * $limit;
			$user_id = get_current_user_ID();

			$customer_all_wallet_orders = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wallet_transactions WHERE transaction_status = 'completed' AND ( sender = '$user_id' OR customer = '$user_id' ) ORDER BY id DESC", ARRAY_A );

			$customer_orders = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wallet_transactions WHERE transaction_status = 'completed' AND ( sender = '$user_id' OR customer = '$user_id' ) ORDER BY id DESC LIMIT $offset, $limit", ARRAY_A );

			$has_orders = 0 < count( $customer_orders );

				if ( $has_orders ) : ?>

					<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
						<thead>
							<tr>
								<th>
									<?php echo esc_html( __( 'ID', 'wc_wallet' ) ); ?>
								</th>
								<th>
									<?php echo esc_html( __( 'Reference', 'wc_wallet' ) ); ?>
								</th>
								<th>
									<?php echo esc_html( __( 'Amount', 'wc_wallet' ) ); ?>
								</th>
								<th>
									<?php echo esc_html( __( 'Type', 'wc_wallet' ) ); ?>
								</th>
								<th>
									<?php echo esc_html( __( 'Date', 'wc_wallet' ) ); ?>
								</th>
							</tr>
						</thead>

						<tbody>
						<?php foreach ( $customer_orders as $key => $transaction ) :
							$id          = $transaction['id'];
							$customer_id = ! empty( $transaction['customer'] ) ? $transaction['customer'] : $transaction['sender'] ;
							$customer    = get_user_by( 'ID', $customer_id );
							$email       = $customer->user_email . ' (#' . $customer_id . ')';

						?>
							<tr>
								<td>
									<?php echo '<a href = "view/' . $id . '" >#' . $id . '</a>'; ?>
								</td>
								<td>
									<?php echo $transaction['reference']; ?>
								</td>
								<td>
									<?php echo wc_price( $transaction['amount'] ); ?>
								</td>
								<td>
									<?php echo ucfirst( $transaction['transaction_type'] ); ?>
								</td>
								<td>
									<?php echo date( 'M d, Y g:i:s A', strtotime( $transaction['transaction_date'] ) ); ?>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>

				<?php else : ?>
					<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
						<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
							<?php _e( 'Go shop', 'woocommerce' ) ?>
						</a>
						<?php _e( 'No order has been made yet.', 'woocommerce' ); ?>
					</div>
				<?php endif; ?>
		</div>

		<?php

		if ( 1 < count($customer_all_wallet_orders) ) : ?>

			<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination wallet-pagination" style="margin-top:10px;">

				<?php if ( 1 !== $paged && $paged > 1 ) : ?>
					<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'wallet', $paged - 1 ) ); ?>"><?php _e( 'Previous', 'woocommerce' ); ?></a>
				<?php endif; ?>

				<?php if ( ceil( count( $customer_all_wallet_orders ) / 10 ) > $paged) : ?>
					<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'wallet', $paged + 1 ) ); ?>"><?php _e( 'Next', 'woocommerce' ); ?></a>
				<?php endif; ?>

			</div>

		<?php endif; ?>
	</div>
<?php
