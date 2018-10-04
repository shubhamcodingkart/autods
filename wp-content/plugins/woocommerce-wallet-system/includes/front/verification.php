<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$id = WC_Wallet_User::get_id();
$amount = WC_Wallet_User::wallet_amount( $id );
$error = '';

if ( isset( $_POST['wallet-transfer-user'] ) && isset( $_POST['wallet-pay-amount'] ) ) {
	$secondary_user = $_POST['wallet-transfer-user'];
	$transfer_amount = $_POST['wallet-pay-amount'];
	if ( empty( $secondary_user ) ) {
		$error = 1;
		wc_add_notice( 'Customer Email is required.', 'error' );
	}
	if ( empty( $transfer_amount ) ) {
		$error = 1;
		wc_add_notice( 'Transfer Amount is required.', 'error' );
	}
	if ( ! empty( $secondary_user ) && ! empty( $transfer_amount ) ) {
		$user_data = get_user_by( 'email', $secondary_user );
		if ( ! $user_data ) {
			$error = 1;
			wc_add_notice( 'Customer Email is invalid.', 'error' );
		}
		if ( $transfer_amount > $amount ) {
			$error = 1;
			wc_add_notice( 'Insufficient Amount.', 'error' );
		}
	}

	if ( ! empty( $error ) ) {
		wp_safe_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '/wallet/transfer' );
		exit();
	}
}
if ( isset( $_POST['wallet_transfer_verification_nonce'] ) && isset( $_POST['wallet-verification-code'] ) ) {
	$secondary_user = $_POST['wc-wallet-transfer-user'];
	$transfer_amount = $_POST['wc-wallet-user-transfer-amount'];
	if ( empty( $secondary_user ) ) {
		$error = 1;
		wc_add_notice( 'Customer Email is required.', 'error' );
	}
	if ( empty( $transfer_amount ) ) {
		$error = 1;
		wc_add_notice( 'Transfer Amount is required.', 'error' );
	}
	if ( ! empty( $secondary_user ) && ! empty( $transfer_amount ) ) {
		$user_data = get_user_by( 'email', $secondary_user );
		if ( ! $user_data ) {
			$error = 1;
			wc_add_notice( 'Customer Email is invalid.', 'error' );
		}
		if ( $transfer_amount > $amount ) {
			$error = 1;
			wc_add_notice( 'Insufficient Amount.', 'error' );
		}
	}

	if ( ! empty( $error ) ) {
		wp_safe_redirect( get_permalink( get_option('woocommerce_myaccount_page_id') ) . '/wallet/transfer' );
		exit();
	}
}

if ( get_option( 'woocommerce_customer_otp_verification', true ) === 'on' && ( get_option( 'woocommerce_customer_otp_access_method', true ) === 'sms' || get_option( 'woocommerce_customer_otp_access_method', true ) === 'mail' ) ) {

	if ( isset( $_POST['wallet_transfer_nonce'] ) ) {

		$response = send_wallet_verification_code();

	}

	if ( isset( $_POST['wallet_transfer_verification_nonce'] ) && isset( $_POST['wallet-verification-code'] ) ) {

		$response = get_wallet_verification_code();

		if ( 'true' == $response ) {

			wc_add_notice( 'Amount Transfered Successfully.', 'success' );

			wp_safe_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '/wallet' );

			exit();

		} else {

			wc_add_notice( $response, 'error' );

		}
	}
} else {

	$wallet_max_debit_res = get_option( 'woocommerce_customer_max_wallet_debit_amount' );

	if ( (float) $transfer_amount > (float) $wallet_max_debit_res ) {
		wc_add_notice( sprintf( 'Maximum Wallet Debit Amount is %s', wc_price( $wallet_max_debit_res ) ), 'error' );
	} else {

		$wallet_updation = WC_Wallet_User::set_user_wallet( $id, $secondary_user, $transfer_amount );

		if ( $wallet_updation ) {

			wc_add_notice( 'Amount Transfered Successfully.', 'success' );

		}
	}

	wp_safe_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '/wallet' );

	exit();
}

?>

<div class = "main-wrapper">
		<div class = "form-wrapper">
			<form action = "" method = "post">
					<?php wp_nonce_field( 'wallet_transfer_verification', 'wallet_transfer_verification_nonce' ); ?>
					<table>
							<tr>
									<td>
											<label for = "wallet-verification-code">Enter OTP : </label>
									</td>
									<td>
											<input type = "number" name = "wallet-verification-code" id = "wallet-verification-code" placeholder = "111111" />
									</td>
									<td>
									</td>
							</tr>
							<tr>
									<td>
										<p class="submit">
											<input type="submit" name="submit" id="submit" class="button button-primary" value="Verify"  />
										</p>
									</td>
									<td>
										<input type = "hidden" name = "wc-wallet-user" value = "<?php echo $id; ?>" />
										<input type = "hidden" name = "wc-wallet-transfer-user" value = "<?php echo $secondary_user; ?>" />
										<input type = "hidden" name = "wc-wallet-user-transfer-amount" value = "<?php echo $transfer_amount; ?>" />
									</td>
							</tr>
					</table>
			</form>
		</div>
</div>
