<?php

if( ! defined ( 'ABSPATH' ) )
		exit;

?>

<form method="post" action="options.php" enctype="multipart/form-data">

	<?php settings_fields( 'wc-wallet-settings' );?>

	<table class="form-table">

		<tbody>

			<tr valign="top">

				<th scope="row" class="titledesc">
					<label for="woocommerce_customer_min_wallet_credit_amount">Minimum Wallet Credit Amount</label>
				</th>

				<td>
					<input type = "text" name = "woocommerce_customer_min_wallet_credit_amount" id = "woocommerce_customer_min_wallet_credit_amount" value = "<?php echo get_option( 'woocommerce_customer_min_wallet_credit_amount', true ); ?>" />
				</td>

			</tr>

			<tr valign="top">

				<th scope="row" class="titledesc">
					<label for="woocommerce_customer_max_wallet_debit_amount">Maximum Wallet Debit Amount</label>
				</th>

				<td>
					<input type = "text" name = "woocommerce_customer_max_wallet_debit_amount" id = "woocommerce_customer_max_wallet_debit_amount" value = "<?php echo get_option( 'woocommerce_customer_max_wallet_debit_amount', true ); ?>" />
				</td>

			</tr>

			<tr valign="top">

				<th scope="row" class="titledesc">
					<label for="woocommerce_customer_otp_verification">OTP Verification</label>
				</th>

				<td>
					<input type = "checkbox" name = "woocommerce_customer_otp_verification" id = "woocommerce_customer_otp_verification" <?php   if( get_option( 'woocommerce_customer_otp_verification', true ) === 'on' ) echo 'checked="checked"'; ?> />
				</td>

			</tr>

			<tr valign="top">

				<th scope="row" class="titledesc">
					<label for="woocommerce_multiple_cashback_condition">Multiple Cashback Conditions</label>
				</th>

				<td>
					<select name="woocommerce_multiple_cashback_condition" id="woocommerce_multiple_cashback_condition">
						<option value="yes" <?php if( get_option( 'woocommerce_multiple_cashback_condition', true ) == 'yes' ) echo 'selected="selected"'; ?> >Yes</option>
						<option value="no" <?php if( get_option( 'woocommerce_multiple_cashback_condition', true ) == 'no' ) echo 'selected="selected"'; ?> >No</option>
					</select>
				</td>

			</tr>

			<tr valign="top">

				<th scope="row" class="titledesc">
					<label for="woocommerce_multiple_cashback_condition_preference">Preference in Multiple Cashback</label>
				</th>

				<td>
					<select name="woocommerce_multiple_cashback_condition_preference" id="woocommerce_multiple_cashback_condition_preference">
						<option value="product" <?php if( get_option( 'woocommerce_multiple_cashback_condition_preference', true ) == 'product' ) echo 'selected="selected"'; ?> >Product</option>
						<option value="cart" <?php if( get_option( 'woocommerce_multiple_cashback_condition_preference', true ) == 'cart' ) echo 'selected="selected"'; ?> >Cart</option>
					</select>
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


		<!-- <tr valign="top">

			<th scope="row" class="titledesc">
				<label for="wallet-action">Action</label>
			</th>

			<td>

				<select class="" name="wallet-action" id="wallet-action" title="action">
					<option value="credit">Credit</option>
					<option value="debit">Debit</option>
				</select>
			</td>

		</tr> -->

		<tr valign="top">

			<th></th>

			<td><p class="error"></p></td>

		</tr>

		</tbody>
	</table>

	<p class="submit">

		<input name="wallet-transaction-submit" class="button-primary wallet-setttings" type="submit" value="Save">

		<a href="admin.php?page=customer_wallet" class="button-secondary">Cancel</a>

	</p>

</form>
