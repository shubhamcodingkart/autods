<?php
/**
 * This file contains wallet restrictions settings.
 *
 * @package Woocommerce Wallet System
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<form method="post" action="options.php" enctype="multipart/form-data">

	<?php settings_fields( 'wc-wallet-restriction-settings' );?>

	<table class="form-table">

		<tbody>

			<tr valign="top">

				<th scope="row" class="titledesc">
					<label for="woocommerce_customer_min_wallet_credit_amount">Minimum Wallet Credit Amount</label>
				</th>

				<td>
					<input type = "number" step="0.1" name = "woocommerce_customer_min_wallet_credit_amount" id = "woocommerce_customer_min_wallet_credit_amount" value = "<?php echo get_option( 'woocommerce_customer_min_wallet_credit_amount', true ); ?>" />
				</td>

			</tr>

			<tr valign="top">

				<th scope="row" class="titledesc">
					<label for="woocommerce_customer_max_wallet_debit_amount">Maximum Wallet Debit Amount</label>
				</th>

				<td>
					<input type = "number" step="0.1" name = "woocommerce_customer_max_wallet_debit_amount" id = "woocommerce_customer_max_wallet_debit_amount" value = "<?php echo get_option( 'woocommerce_customer_max_wallet_debit_amount', true ); ?>" />
				</td>

			</tr>

		</tbody>

	</table>

	<p class="submit">

		<input name="wallet-transaction-submit" class="button-primary wallet-setttings" type="submit" value="Save">

		<a href="admin.php?page=customer_wallet" class="button-secondary">Cancel</a>

	</p>

</form>
