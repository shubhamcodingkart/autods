<?php
/**
 * This file handles transaction view.
 *
 * @package WordPress Woocommerce Wallet System
 **/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<?php
/**
 * This file handles single transaction detail view template.
 *
 * @package Woocommerce Marketplace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $transaction[0] ) || empty( $transaction[0] ) ) {
	wc_print_notice( 'Invalid Transaction ID.', 'error' );
} else {
	$transaction = $transaction[0];

?>
<div>
	<h2 class="wp-heading-inline"><?php echo __( 'Woocommerce Wallet Transaction Details', 'wc_wallet' );?></h2>
</div>
<div class = 'wallet-transaction-view-wrapper'>
	<table class="wallet-transaction-view">
		<tbody>
				<tr>
					<th><?php echo __( 'Amount', 'wc_wallet' ); ?></th>
					<td><?php echo wc_price( $transaction['amount'] ); ?></td>
				</tr>
				<?php
				if ( ! empty( $transaction['order_id'] ) ) :
					$order_id  = $transaction['order_id'];
					if ( ! is_admin() ) {
						$order     = wc_get_order( $order_id );
						$order_url = $order->get_view_order_url();
					} else {
						$order_url = get_edit_post_link( $order_id );
					}
				?>
				<tr>
						<th><?php echo __( 'Order ID', 'wc_wallet' ); ?></th>
						<td><?php echo '<a href="' . $order_url . '" >#' . $transaction['order_id'] . '</a>'; ?></td>
				</tr>
				<?php endif; ?>
				<tr>
						<th><?php echo __( 'Action', 'wc_wallet' ); ?></th>
						<td><?php echo ucfirst( $transaction['transaction_type'] ); ?></td>
				</tr>
				<tr>
						<th><?php echo __( 'Type', 'wc_wallet' ); ?></th>
						<td><?php echo ucfirst( $transaction['reference'] ); ?></td>
				</tr>
				<?php
				if ( ! empty( $transaction['customer'] ) ) :
					$customer_id    = $transaction['customer'];
					$customer       = get_user_by( 'ID', $customer_id );
					$customer_email = $customer->user_email . ' (#' . $customer_id . ')';
				?>
				<tr>
						<th><?php echo __( 'Customer', 'wc_wallet' ); ?></th>
						<td><?php echo $customer_email; ?></td>
				</tr>
				<?php endif; ?>
				<?php
				if ( ! empty( $transaction['sender'] ) ) :
					$sender_id    = $transaction['sender'];
					$sender       = get_user_by( 'ID', $sender_id );
					$sender_email = $sender->user_email . ' (#' . $sender_id . ')';
				?>
				<tr>
						<th><?php echo __( 'Payee', 'wc_wallet' ); ?></th>
						<td><?php echo $sender_email; ?></td>
				</tr>
				<?php endif; ?>
				<tr>
						<th><?php echo __( 'Transaction On', 'wc_wallet' ); ?></th>
						<td><?php echo date( 'M d, Y g:i:s A', strtotime( $transaction['transaction_date'] ) ); ?></td>
				</tr>
				<tr>
						<th><?php echo __( 'Transaction Note', 'wc_wallet' ); ?></th>
						<td><?php echo $transaction['transaction_note'] ? stripslashes( $transaction['transaction_note'] ) : '-'; ?></td>
				</tr>
		</tbody>
</table>
</div>
<?php
}
