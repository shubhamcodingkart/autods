<?php
/**
 * This file handles all wallet involved transactions.
 *
 * @package WC_WALLET
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Wallet_Transactions' ) ) {

	/**
	 * Wallet Transaction List.
	 */
	class WC_Wallet_Transactions {

		/**
		 * Transaction params.
		 *
		 * @var array
		 */
		protected $default_data = array(
			'order_id'            => '',
			'reference'           => '',
			'sender'              => '',
			'customer'            => 0,
			'amount'              => 0,
			'transaction_type'    => '',
			'transaction_date'    => '',
			'transaction_status'  => 'completed',
			'transaction_note'    => '',
		);

		/**
		 * Constructor.
		 */
		public function __construct() {
			global $wpdb, $wc_transaction;
			$this->table_name = $wpdb->prefix . 'wallet_transactions';
			$wc_transaction = __CLASS__;
		}

		/**
		 * Generate Transaction.
		 *
		 * @param array $data Transaction Data.
		 */
		public function generate( $data ) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'wallet_transactions';

			$data = wp_parse_args( $data, $this->default_data );
			$response = $wpdb->insert( $table_name, $data );

			return $response;
		}

		public function get_transaction_template( $id ) {
			global $wpdb;
			$transaction = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wallet_transactions WHERE id = $id", ARRAY_A );
			require_once WC_WALLET . 'includes/templates/admin/transactions/view.php';
		}
	}
}
