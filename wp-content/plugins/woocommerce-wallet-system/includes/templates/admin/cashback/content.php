<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Casback Handler Class.
 */
class Cashback_Rule {

	/**
	 * Setting Cashback Rule Handler.
	 */
	public function set_cashback_rule() {

		global $wpdb;

		$table_name = $wpdb->prefix . 'cashback_rules';

		$errmsg = '';

		if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {

			$rule_id = $_GET['id'];

		} else {
			$rule_id = '';
		}

		if ( isset( $_POST['cashback_rule'] ) ) {

			$com_type = $_POST['mp_advanced_cashback_rule_type'];

			$starting_price = $_POST['rule_price_from'];

			$end_price = $_POST['rule_price_to'];

			$rule_cashback = $_POST['mp_rule_cashback'];

			if ( $starting_price >= $end_price ) {

				wp_die( esc_html( __( 'Enter Valid Range', 'wc_wallet' ) ) );

			}

			$rule_post = $wpdb->get_results( "SELECT * FROM $table_name" );

			foreach ( $rule_post as $key => $value ) {

				if ( $rule_id !== $rule_post[ $key ]->id ) {

					if ( ( $rule_post[ $key ]->rule_price_from <= $starting_price) && ($starting_price <= $rule_post[ $key ]->rule_price_to ) ) {

						wp_die( esc_html( __( 'Enter Valid Range', 'wc_wallet' ) ) );

					}

					if ( ($rule_post[ $key ]->rule_price_from <= $end_price) && ($end_price <= $rule_post[ $key ]->rule_price_to) ) {

						wp_die( esc_html( __( 'Enter Valid Range', 'wc_wallet' ) ) );

					}
				}
			}

			if ( ! empty( $com_type ) && ! empty( $starting_price ) && ! empty( $end_price ) && ! empty( $rule_cashback ) ) {

				$check_val = '';

				if ( $rule_id) {
					$check_val = $wpdb->update($table_name, array('rule_type'=>$com_type, 'rule_price_from'=>$starting_price, 'rule_price_to'=>$end_price, 'amount'=>$rule_cashback), array('id'=>$rule_id));
				} else {

					$check_val          = $wpdb->insert($table_name, array(
						'rule_type'       => $com_type,
						'rule_price_from' => $starting_price,
						'rule_price_to'   => $end_price,
						'amount'          => $rule_cashback,
					));

				}

				if ( $check_val ) {

					if ( 'update' === $check_val ) {
						wp_safe_redirect( admin_url( 'admin.php?page=manage-cashback-rule&update=true' ) );

						exit;
					}

				}
			} else {

				$errmsg = 'Some fields are empty.';

			}
		}

		if (!empty($rule_id)) {

			$wk_post = $wpdb->get_results("Select * from $table_name where id = $rule_id");

		}

		else {

			$wk_post = '';
		}

		?>

		<div class="wrap ad-cashback">

			<h1>Cashback Rule Information</h1>

			<form method="post" action="">

				<table class="form-table">
					<tbody>

					<tr valign="top">

						<th scope="row" class="titledesc">
							<label for="mp_advanced_cashback_rule_type">Cashback Type</label>
						</th>

						<td class="">
							<span class="error">* </span><select class="" name="mp_advanced_cashback_rule_type" id="mp_advanced_cashback_rule_type" title="cashback type">
								<option value="fixed" <?php if ($wk_post && $wk_post[0]->rule_type == 'fixed') {
									echo 'selected="selected"';
								} ?>>Fixed</option>
								<option value="percent" <?php if ($wk_post && $wk_post[0]->rule_type == 'percent') {
									echo 'selected="selected"';
								} ?>>Percent</option>
							</select>
							<p class="description">You can set cashback type either fixed or percentage based. Cashback will be calculated based on this selection.</p>
						</td>

					</tr>

					<tr valign="top">

						<th scope="row" class="titledesc">
							<label for="mp_advanced_price_from">Cart Total From</label>
						</th>

						<td>
							<span class="error">* </span><input type="text" name="rule_price_from" id="mp_advanced_price_from" value="<?php if($wk_post) echo $wk_post[0]->rule_price_from; ?>" required>
						</td>

					</tr>

					<tr valign="top">

						<th scope="row" class="titledesc">
							<label for="mp_advanced_price_to">Cart Total To</label>
						</th>

						<td>
							<span class="error">* </span><input type="text" name="rule_price_to" id="mp_advanced_price_to" value="<?php if($wk_post) echo $wk_post[0]->rule_price_to; ?>" required>
						</td>

					</tr>

					<tr valign="top">

						<th scope="row" class="titledesc">
							<label for="mp_rule_cashback">Cashback</label>
						</th>

						<td class="">
							<span class="error">* </span><input type="text" name="mp_rule_cashback" id="mp_rule_cashback" value="<?php if($wk_post) echo $wk_post[0]->amount; ?>" required>
							<p class="description">Cashback Amount.</p>
						</td>

					</tr>

					<tr valign="top">

						<th scope="row" class="titledesc">
							<label for="mp_rule_cashback">Status</label>
						</th>

						<td class="">
							<select name="mp_rule_cashback_status" id="mp_rule_cashback_status" required>
								<option value="publish" <?php if( $wk_post && $wk_post[0]->rule_status == 'publish' ) echo 'selected="selected"'; ?> >Publish</option>
								<option value="draft" <?php if( $wk_post && $wk_post[0]->rule_status == 'draft' ) echo 'selected="selected"'; ?> >Draft</option>
							</select>
						</td>

					</tr>

					<tr valign="top">

						<th></th>

						<td><?php echo '<p class="error">'.$errmsg.'</p>'; ?></td>

					</tr>

					</tbody>
				</table>

				<p class="submit">

					<input name="cashback_rule" class="button-primary cashback-save-button" type="submit" value="Save">

					<a href="admin.php?page=manage-cashback-rule" class="button-secondary">Cancel</a>

				</p>


			</form>

		</div>

		<?php
	}

}

$res = new Cashback_Rule();

$res->set_cashback_rule();
