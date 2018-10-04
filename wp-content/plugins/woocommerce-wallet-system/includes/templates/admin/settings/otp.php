<?php
/**
 * This file contains wallet otp settings.
 *
 * @package Woocommerce Wallet System
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<form method="post" action="options.php" enctype="multipart/form-data">

	<?php settings_fields( 'wc-wallet-otp-settings' );?>

	<table class="form-table">

		<tbody>

			<tr valign="top">

				<th scope="row" class="titledesc">
					<label for="woocommerce_customer_otp_verification">OTP Verification</label>
				</th>

				<td>
					<input type = "checkbox" name = "woocommerce_customer_otp_verification" id = "woocommerce_customer_otp_verification" <?php   if ( get_option( 'woocommerce_customer_otp_verification', true ) === 'on' ) echo 'checked="checked"'; ?> />
				</td>

			</tr>

			<tr valign="top">

				<th scope="row" class="titledesc">
					<label for="woocommerce_customer_otp_access_method">OTP Access Method</label>
				</th>

				<td>
					<select name="woocommerce_customer_otp_access_method" class="woocommerce_customer_otp_access_method">
						<option value="mail" <?php if( get_option( 'woocommerce_customer_otp_access_method' ) == 'mail' ) echo 'selected="selected"'; ?> >Mail</option>
						<option value="sms" <?php if( get_option( 'woocommerce_customer_otp_access_method' ) == 'sms' ) echo 'selected="selected"'; ?> >SMS</option>
					</select>
				</td>

			</tr>

			<tr>

				<th scope="row" class="titledesc">
					<label for="woocommerce_wallet_twilio_sid">Account SID</label>
				</th>

				<td>
					<input type="text" name="woocommerce_wallet_twilio_sid" id="woocommerce_wallet_twilio_sid" value = "<?php echo get_option( 'woocommerce_wallet_twilio_sid', true ); ?>" />
				</td>

			</tr>

			<tr>

				<th scope="row" class="titledesc">
					<label for="woocommerce_wallet_twilio_number">Twilio Number</label>
				</th>

				<td>
					<input type="text" name="woocommerce_wallet_twilio_number" id="woocommerce_wallet_twilio_number" value = "<?php echo get_option( 'woocommerce_wallet_twilio_number', true ); ?>" />
				</td>

			</tr>

			<tr>

				<th scope="row" class="titledesc">
					<label for="woocommerce_wallet_twilio_auth_token">Auth Token</label>
				</th>

				<td>
					<input type="text" name="woocommerce_wallet_twilio_auth_token" id="woocommerce_wallet_twilio_auth_token" value = "<?php echo get_option( 'woocommerce_wallet_twilio_auth_token', true ); ?>" />
				</td>

			</tr>

			<tr>

				<th scope="row" class="titledesc">
					<label for="woocommerce_wallet_twilio_otp_validation_limit">OTP Validation Limit</label>
				</th>

				<td>
					<input type="text" name="woocommerce_wallet_twilio_otp_validation_limit" id="woocommerce_wallet_twilio_otp_validation_limit" value = "<?php echo get_option( 'woocommerce_wallet_twilio_otp_validation_limit', true ); ?>" />
				</td>

			</tr>


		</tbody>

	</table>

	<p class="submit">

		<input name="wallet-transaction-submit" class="button-primary wallet-setttings" type="submit" value="Save">

		<a href="admin.php?page=customer_wallet" class="button-secondary">Cancel</a>

	</p>

</form>
