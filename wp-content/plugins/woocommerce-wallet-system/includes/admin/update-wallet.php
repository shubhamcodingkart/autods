<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $wpdb;

$admin_users = new WP_User_Query(
	array(
		'role'   => 'administrator1',
		'fields' => 'ID',
	)
);

$admin_users = new WP_User_Query(
	array(
		'role'   => 'administrator',
		'fields' => 'ID',
	)
);

$manager_users = new WP_User_Query(
	array(
		'role'   => 'shop_manager',
		'fields' => 'ID',
	)
);

$manager_users = new WP_User_Query(
	array(
		'role'   => 'seller',
		'fields' => 'ID',
	)
);

$query = new WP_User_Query( array(
	'exclude' => array_merge( $admin_users->get_results(), $manager_users->get_results() ),
) );

$items = $query->get_results();
$errmsg = '';

if ( isset( $_POST['wallet-transaction-submit'] ) ) {

	$wallet_customer = $_POST['wallet-customer'];

	$wallet_amount = $_POST['wallet-transaction-amount'];

	$wallet_action = $_POST['wallet-action'];

	$wallet_note = $_POST['wallet-note'];

	if ( ! empty( $wallet_customer ) && ! empty( $wallet_action ) && ! empty( $wallet_amount ) ) {
		global $wc_transaction;
		$check_val = '';
		$reference = '';
		$old_amount = (float) get_user_meta( $wallet_customer, 'wallet-amount', true );

		if ( 'credit' === $wallet_action ) {
			$new_amount = $old_amount + $wallet_amount;
			update_user_meta( $wallet_customer, 'wallet-amount', $new_amount );
			$reference = __( 'Manual Wallet Credit', 'wc_wallet' );
			$check_val = 'updated';
		} elseif ( $old_amount >= $wallet_amount ) {
			$new_amount = $old_amount - $wallet_amount;
			update_user_meta( $wallet_customer, 'wallet-amount', $new_amount );
			$reference = __( 'Manual Wallet Debit', 'wc_wallet' );
			$check_val = 'updated';
		} else {
			$errmsg = 'Insufficient Amount.';
		}

		if ( $check_val ) {
			$offset   = get_option( 'gmt_offset' );
			$offset = $offset * 60 . ' minutes';
			$data = array(
				'transaction_type' => $wallet_action,
				'amount'           => $wallet_amount,
				'sender'           => get_current_user_ID(),
				'customer'         => $wallet_customer,
				'transaction_note' => $wallet_note,
				'transaction_date' => date( 'Y-m-d H:i:s',strtotime( $offset ) ),
				'reference'        => $reference,
			);
			$wc_transaction->generate( $data );
			wp_safe_redirect( site_url() . '/wp-admin/admin.php?page=customer_wallet' );

			exit;

		}
	} else {

		$errmsg = 'Some fields are empty.';

	}
}
?>
<div class="wrap">

	<h1>Custom Wallet Transaction</h1>

	<form method="post" action="" enctype="multipart/form-data">

		<table class="form-table">
			<tbody>

			<?php if ( ! empty( $items ) ) { ?>

				<tr valign="top">

					<th scope="row" class="titledesc">
						<label for="wallet-customer">Customer Name</label>
					</th>

					<td>

						<select class="" name="wallet-customer" id="wallet-customer" title="customer">

							<?php
							foreach ( $items as $key => $value ) {
								?>
									<option value="<?php echo $value->ID; ?>"><?php echo $value->data->user_email . ' ( ' . $value->data->user_login . ' ) '; ?>
									</option>
								<?php
							}
							?>
						</select>

					</td>

				</tr>

			<?php
			}
			?>

			<tr valign="top">

				<th scope="row" class="titledesc">
					<label for="wallet-transaction-amount">Amount</label>
				</th>

				<td>
					<input type="number" class="" name="wallet-transaction-amount" id="wallet-transaction-amount" step="0.01">
				</td>

			</tr>

			<tr valign="top">

				<th scope="row" class="titledesc">
					<label for="wallet-action">Action</label>
				</th>

				<td>

					<select class="" name="wallet-action" id="wallet-action" title="action">
						<option value="credit">Credit</option>
						<option value="debit" >Debit</option>
					</select>
				</td>

			</tr>

			<tr valign="top">

				<th scope="row" class="titledesc">
					<label for="wallet-note">Transaction Note</label>
				</th>

				<td>
					<textarea cols="46" rows="7" class="" name="wallet-note" id="wallet-note" title="note">
					</textarea>
				</td>

			</tr>

			<tr valign="top">

				<th></th>

				<td><?php echo '<p class="error">' . $errmsg . '</p>'; ?></td>

			</tr>

			</tbody>
		</table>

		<p class="submit">

			<input name="wallet-transaction-submit" class="button-primary wallet-transaction-submit" type="submit" value="Save">

			<a href="admin.php?page=customer_wallet" class="button-secondary">Cancel</a>

		</p>


	</form>

</div>
