<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$id = WC_Wallet_User::get_id();
$amount = WC_Wallet_User::wallet_amount( $id );

// if ( isset( $_POST['wallet_transfer_nonce'] ) && isset( $_POST['wallet_transfer'] ) ) {
//
// 		do_action( '' );
// 		$response = send_wallet_verification_code();
// 		var_dump( $response );
// 		die;
//
// 		if( $response ){
//
// 			// wp_redirect( site_url() . '/my-account/wallet/transfer/verification' );
// 			//
// 			// exit();
//
// 		}
//
//
// }

?>

<div class="main-container">
		<div class = "form-wrapper">
				<form action = "<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '/wallet/transfer/verification'; ?>" method = "POST">
						<?php wp_nonce_field( 'wallet_transfer', 'wallet_transfer_nonce' ); ?>
						<table>
								<tr>
										<td>
												<label for = "wallet-pay-amount">Select Customer : </label>
										</td>
										<td>
												<input type="email" id="wallet-transfer-user" name="wallet-transfer-user" placeholder="e.g. example@xyz.com" />
										</td>
								</tr>
								<tr>
										<td></td>
										<td></td>
								</tr>
								<tr>
										<td>
												<label for = "wallet-pay-amount">Amount : </label>
										</td>
										<td>
												<input type = "number" name = "wallet-pay-amount" id = "wallet-pay-amount" placeholder = "100" min = "1" max = "<?php echo $amount; ?>" />
										</td>
								</tr>
								<tr>
										<td></td>
										<td></td>
								</tr>
								<tr>
										<td>
											<p class="submit">
												<input type="submit" name="wallet_transfer" id="submit" class="button button-primary" value="Transfer"  />
											</p>
										</td>
										<td>
											<input type = "hidden" name = "wc-wallet-user" value = "<?php echo $id; ?>" />
										</td>
								</tr>
						</table>
				</form>
		</div>
</div>
