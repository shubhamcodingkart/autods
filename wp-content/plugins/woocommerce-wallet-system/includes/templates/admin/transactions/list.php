<?php
/**
 * This file handles Wallet Transactions on admin end.
 *
 * @package WordPress Woocommerce Wallet System
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $label ) || empty( $label ) ) {
	$label = __( 'List', 'wc_wallet' );
}

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

if ( ! class_exists( 'Wallet_Transaction_List' ) ) {
	/**
	 * Wallet Transaction List.
	 */
	class Wallet_Transaction_List extends WP_List_Table {

		/**
		 * Constructor
		 */
		public function __construct() {
			parent::__construct(
				array(
					'singular' => __( 'Wallet Transaction List', 'wc_wallet' ),
					'plural'   => __( 'Wallet Transactions List', 'wc_wallet' ),
					'ajax'     => false,
				)
			);
		}

		public function prepare_items() {
			global $wpdb;
			$columns    = $this->get_columns();
			$hidden     = $this->get_hidden_columns();
			$sortable   = $this->get_sortable_columns();
			$data = $this->table_data();
			$totalitems = count( $data );
			$user       = get_current_user_ID();
			$screen     = get_current_screen();
			$perpage    = $this->get_items_per_page( 'product_per_page', 20 );
			$this->_column_headers = array( $columns, $hidden, $sortable );

			if ( empty( $per_page ) || $per_page < 1 ) {
				$per_page = $screen->get_option( 'per_page', 'default' );
			}

			usort( $data, array( $this, 'usort_reorder' ) );
			$totalpages = ceil( $totalitems / $perpage );
			$currentpage = $this->get_pagenum();
			$data = array_slice( $data, ( ( $currentpage - 1 ) * $perpage ), $perpage );
			$this->set_pagination_args(
				array(
					'total_items' => $totalitems,
					'total_pages' => $totalpages,
					'per_page'    => $perpage,
				)
			);
			$this->items = $data;
		}

		/**
		 * Defining Columns
		 */
		public function get_columns() {
			$columns = array(
				'transaction_id'   => __( 'Transaction ID', 'wc_wallet' ),
				'reference'        => __( 'Reference', 'wc_wallet' ),
				'customer'         => __( 'Customer', 'wc_wallet' ),
				'amount'           => __( 'Amount', 'wc_wallet' ),
				'transaction_type' => __( 'Transaction Type', 'wc_wallet' ),
				'date'             => __( 'Date', 'wc_wallet' ),
			);

			return $columns;
		}

		/**
		 * Get Default Columns.
		 *
		 * @param array  $item List columns.
		 * @param string $column_name Column name.
		 */
		public function column_default( $item, $column_name ) {

			switch ( $column_name ) {
				case 'transaction_id':
				case 'reference':
				case 'customer':
				case 'amount':
				case 'transaction_type':
				case 'date':
					return $item[ $column_name ];
				default:
					return print_r( $item, true );
			}
		}

		/**
		 * Defining Hidden Columns
		 */
		public function get_hidden_columns() {
			return array();
		}

		/**
		 * Column checkbox.
		 *
		 * @param array $item List columns.
		 */
		public function column_cb( $item ) {
			return sprintf( '<input type="checkbox" id="transaction_id_%s" name="transaction_id[]" value="%s" />', $item['transaction_id'], $item['transaction_id'] );
		}

		/**
		 * Getting data from database
		 */
		public function table_data() {
			global $wpdb;
			$data = array();
			$conditions = "transaction_status = 'completed'";

			if ( isset( $_GET['transaction-type'] ) && ! empty( $_GET['transaction-type'] ) ) {
				$transaction_type = $_GET['transaction-type'];
				$conditions .= ' AND transaction_type="' . $transaction_type . '"';
			}
			if ( isset( $_GET['transaction-date'] ) && ! empty( $_GET['transaction-date'] ) ) {
				$transaction_date = $_GET['transaction-date'];
				$conditions .= ' AND DATE(transaction_date)="' . $transaction_date . '"';
			}

			$transactions = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wallet_transactions WHERE $conditions", ARRAY_A );

			if ( ! empty( $transactions ) ) {
				foreach ( $transactions as $key => $transaction ) {
					$id          = $transaction['id'];
					$customer_id = ! empty( $transaction['customer'] ) ? $transaction['customer'] : $transaction['sender'] ;
					$customer    = get_user_by( 'ID', $customer_id );
					$email       = $customer->user_email . ' (#' . $customer_id . ')';
					$data[]      = array(

						'id'               => $id,

						'transaction_id'   => '<a href = "' . admin_url( "admin.php?page=wallet-transactions&transaction_id=$id" ) . '" >#' . $id . '</a>',

						'reference'        => $transaction['reference'],

						'customer'         => $email,

						'amount'           => wc_price( $transaction['amount'] ),

						'transaction_type' => ucfirst( $transaction['transaction_type'] ),

						'date'             => date( 'M d, Y g:i:s A', strtotime( $transaction['transaction_date'] ) ),

					);
				}
			}
			return $data;
		}

		/**
		 * List Filters.
		 *
		 * @param string $which Position of filter.
		 */
		public function extra_tablenav( $which ) {
			global $wpdb;
			$transaction_type = '';
			$transaction_date = '';
			if ( 'top' === $which ) {
				if ( isset( $_GET['transaction-type'] ) ) {
					$transaction_type = $_GET['transaction-type'];
				}
				if ( isset( $_GET['transaction-date'] ) ) {
					$transaction_date = $_GET['transaction-date'];
				}
				?>
				<div class="alignleft actions bulkactions">
					<select name="transaction-type" class="transaction-type">
						<option value="">Transaction Type</option>
						<option value="credit"
						<?php
						if ( 'credit' === $transaction_type ) {
							echo "selected='selected'";
						}
						?>
						>Credit</option>
						<option value="debit"
						<?php
						if ( 'debit' === $transaction_type ) {
							echo "selected='selected'";
						}
						?>
						>Debit</option>
					</select>

					<input type="text" value="<?php echo $transaction_date; ?>" name="transaction-date" id="transaction-date" placeholder="yyyy-mm-dd" class="transaction-datepicker" />

					<input type="submit" value="<?php esc_html_e( 'Filter', 'wc_wallet' ); ?>" name="transaction" class="button" />
				</div>
				<?php
			}
		}

		/**
		 * Sorting data to be displayed
		 *
		 * @param array $a Old Data.
		 * @param array $b Updated Data.
		 */
		public function usort_reorder( $a, $b ) {
			$orderby = ( ! empty( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : 'id';

			$order   = ( ! empty( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : 'desc';

			$result = strcmp( $a[ $orderby ], $b[ $orderby ] );

			return ( 'asc' === $order ) ? $result : -$result;
		}

		/**
		 * Get sortable columns.
		 */
		public function get_sortable_columns() {

			$sortable_columns = array(

				'transaction_id'   => array( 'transaction_id', true ),

				'reference'        => array( 'reference', true ),

				'customer'         => array( 'customer', true ),

				'transaction_type' => array( 'transaction_type', true ),

				'date'             => array( 'date', true ),

			);

			return $sortable_columns;

		}

	}

	$obj = new Wallet_Transaction_List();
	$label = 'Woocommerce Wallet Transaction List';
	echo '<div class="wrap">';
	echo '<h1 class="wp-heading-inline">' . $label . '</h1>';

	$obj->prepare_items();
	?>

	<form method = "get">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
		<?php $obj->display(); ?>
	</form>
	<?php
}
