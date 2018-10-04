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

	<?php settings_fields( 'wc-wallet-cashback-settings' );?>

	<table class="form-table">

		<tbody>

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

		</tbody>

	</table>

	<p class="submit">

		<input name="wallet-transaction-submit" class="button-primary wallet-setttings" type="submit" value="Save">

		<a href="admin.php?page=customer_wallet" class="button-secondary">Cancel</a>

	</p>

</form>
