<?php

if( ! defined( 'ABSPATH' ) )
		exit;

if( ! class_exists( 'WC_Wallet_Install' ) ){

	class WC_Wallet_Install{

		public function __construct(){

			$this->wallet_verification_code();

		}

		private function wallet_verification_code(){

			global $wpdb;

			$table_name = $wpdb->prefix . 'wallet_verification_code';

			$table_check = $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" );

			if( $table_check != $table_name ){

				/* ----->>> Table not in database <<<----- */

				$charset_collate = $wpdb->get_charset_collate();

				$sql = "CREATE TABLE $table_name (
								id int(250) NOT NULL AUTO_INCREMENT,
								phone_number varchar(50) DEFAULT NULL,
								verification_code int(11) DEFAULT NULL,
								expiry int(250) NOT NULL,
								UNIQUE KEY id (id)
				) $charset_collate;";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
			}

			$table_name1 = $wpdb->prefix.'cashback_rules';

			if($wpdb->get_var("show tables like '$table_name1'") != $table_name1) {

				$sql1 = "CREATE TABLE $table_name1 (
							id bigint(20) NOT NULL AUTO_INCREMENT,
							rule_type varchar(10) NOT NULL,
							rule_price_from int(10) NOT NULL,
							rule_price_to int(10) NOT NULL,
							amount int(10) NOT NULL,
							rule_status varchar(10) DEFAULT 'publish',
						 PRIMARY KEY (`id`)
					);";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

				dbDelta( $sql1 );
			}

			$table_name2 = $wpdb->prefix . 'wallet_transactions';

			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name2'" ) !== $table_name2 ) {

				$sql2 = "CREATE TABLE $table_name2 (
							id bigint(20) NOT NULL AUTO_INCREMENT,
							order_id varchar(250),
							reference varchar(100) NOT NULL,
							sender int(10) NOT NULL,
							customer int(10) NOT NULL,
							amount int(10) NOT NULL,
							transaction_type varchar(10) NOT NULL,
							transaction_date datetime NOT NULL,
							transaction_status varchar(10) DEFAULT 'completed',
							transaction_note varchar(250),
						 PRIMARY KEY (`id`)
					);";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

				dbDelta( $sql2 );
			}

		}

	}

	new WC_Wallet_Install();

}
