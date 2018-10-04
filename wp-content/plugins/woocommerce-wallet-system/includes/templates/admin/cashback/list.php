<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


echo '<div class="wrap">';

echo '<h1 class="wp-heading-inline">Cashback Rules</h1>';

echo '<a href="admin.php?page=manage-cashback-rule&action=add" class="page-title-action">Add Rule</a>';

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Cashback rule listing.
 */
class Rule_List_Table extends WP_List_Table {

	/**
	 * Constructor, we override the parent to pass our own arguments
	 * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
	 */
	public function __construct() {

		parent::__construct( array(

			'singular' => 'Rule',

			'plural'   => 'Rules',

			'ajax'     => false,

		) );

	}

	/**
	 * Prepare Items for listing.
	 */
	public function prepare_items() {

		global $wpdb;

		$columns    = $this->get_columns();

		$sortable   = $this->get_sortable_columns();

		$hidden     = $this->get_hidden_columns();

		$this->process_bulk_action();

		$data       = $this->table_data();

		$totalitems = count( $data );

		$user       = get_current_user_id();

		$screen     = get_current_screen();

		$perpage    = $this->get_items_per_page( 'rule_per_page', 20 );

		$this->_column_headers = array( $columns, $hidden, $sortable );

		if ( empty( $per_page ) || $per_page < 1 ) {

			$per_page = $screen->get_option( 'per_page', 'default' );

		}

		/**
		 * Sorting handler.
		 *
		 * @param array $a Basic Data.
		 * @param array $b Sorted Data.
		 */
		function usort_reorder( $a, $b ) {

			$orderby = ( ! empty( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : 'id'; // If no sort, default to title.

			$order = ( ! empty( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : 'asc'; // If no order, default to asc.

			$result = strcmp( $a[ $orderby ], $b[ $orderby ] ); // Determine sort order.

			return ( 'asc' === $order ) ? $result : -$result; // Send final sort direction to usort.

		}

		usort( $data, 'usort_reorder' );

				$totalpages = ceil( $totalitems / $perpage );

				$currentpage = $this->get_pagenum();

				$data = array_slice( $data, ( ( $currentpage - 1 ) * $perpage ), $perpage );

				$this->set_pagination_args( array(

					'total_items' => $totalitems,

					'total_pages' => $totalpages,

					'per_page'    => $perpage,

				) );

			$this->items = $data;

	}

	/**
	 * Hidden Columns
	 */
	public function get_hidden_columns() {

		return array();

	}

	/**
	 * Checkbox Column.
	 *
	 * @param array $item List items.
	 */
	public function column_cb( $item ) {

		return sprintf( '<input type="checkbox" id="rule_%s"name="rule[]" value="%s" />', $item['id'], $item['id'] );

	}

	/**
	 * Get Columns.
	 */
	public function get_columns() {

			$columns = array(

				'cb'          => '<input type="checkbox" />', // Render a checkbox instead of text.

				'id'          => __( 'Id', 'wc_wallet' ),

				'price_from'  => __( 'Price From', 'wc_wallet' ),

				'price_to'    => __( 'Price To', 'wc_wallet' ),

				'type'        => __( 'Type', 'wc_wallet' ),

				'amount'      => __( 'Amount', 'wc_wallet' ),

				'status'      => __( 'Status', 'wc_wallet' ),

			);

			return $columns;

	}

	/**
	 * Decide which columns to activate the sorting functionality on
	 *
	 * @return array $sortable, the array of columns that can be sorted by the user
	 */
	public function get_sortable_columns() {

		$sortable_columns = array(

			'price_from'  => array( 'price_from', true ),

			'price_to'    => array( 'price_to', true ),

			'amount'      => array( 'amount', true ),

		);

		return $sortable_columns;

	}

	/**
	 * Data.
	 */
	private function table_data() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'cashback_rules';

		$data = array();

		$wk_post = $wpdb->get_results( "SELECT id, rule_type, rule_price_from, rule_price_to, amount, rule_status FROM $table_name" );

		$id = array();
		$start_price = array();
		$end_price = array();
		$cashback_type = array();
		$cashback_amount = array();
		$cashback_rule_status = array();
		$i = 0;

		foreach ( $wk_post as $posts ) {

			$id[] = $posts->id;

			$cashback_type[] = $posts->rule_type;

			$start_price[] = $posts->rule_price_from;

			$end_price[] = $posts->rule_price_to;

			$cashback_amount[] = $posts->amount;

			$cashback_rule_status[] = $posts->rule_status;

			$data[] = array(

				'id'         => $id[ $i ],

				'price_from' => $start_price[ $i ],

				'price_to'   => $end_price[ $i ],

				'type'       => ucfirst( $cashback_type[ $i ] ),

				'amount'     => $cashback_amount[ $i ],

				'status'     => $cashback_rule_status[ $i ],

			);

			$i++;

		}

		return $data;

	}

	/**
	 * Bulk Actions.
	 */
	public function get_bulk_actions() {

		$actions = array(

			'trash'    => __( 'Delete', 'wc_wallet' ),

		);

		return $actions;

	}


	/**
	 * Process Bulk Actions.
	 */
	public function process_bulk_action() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'cashback_rules';

		if ( 'trash' === $this->current_action() ) {

			if ( isset( $_GET['rule'] ) ) {

				if ( is_array( $_GET['rule'] ) ) {

					foreach ( $_GET['rule'] as $id ) {

						if ( ! empty( $id ) ) {

								$wpdb->query( "DELETE FROM $table_name WHERE id = '$id'" );

						}
					}
				} else {

					if ( ! empty( $_GET['rule'] ) ) {

							$id = $_GET['rule'];

							$wpdb->query( "DELETE FROM $table_name WHERE id = '$id'" );

					}
				}
			}
		}
	}

	/**
	 * Default Column.
	 *
	 * @param array  $item List Item.
	 * @param string $column_name Column Name.
	 */
	public function column_default( $item, $column_name ) {

		switch ( $column_name ) {

			case 'id':
			case 'price_from':
			case 'price_to':
			case 'type':
			case 'amount':
			case 'status':
				return $item[ $column_name ];
			default:
				return print_r( $item, true );

		}

	}

	/**
	 * Column ID.
	 *
	 * @param array $item List items.
	 */
	public function column_id( $item ) {

		$actions = array(

			'edit'     => sprintf( '<a href="admin.php?page=manage-cashback-rule&action=edit&id=%s">Edit</a>', $item['id'] ),

			'trash'    => sprintf( '<a href="admin.php?page=manage-cashback-rule&action=trash&rule=%s">Delete</a>',$item['id'] ),

		);

		return sprintf( '%1$s %2$s', $item['id'], $this->row_actions( $actions ) );

	}

}

$wp_list_table = new Rule_List_Table();

$wp_list_table->prepare_items();

	?>

<form method="GET">

		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />

<?php

	$wp_list_table->display();

	?>

	</form>

<?php

echo '</div>';
