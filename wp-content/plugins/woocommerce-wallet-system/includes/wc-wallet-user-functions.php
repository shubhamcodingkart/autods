<?php

if( ! defined ( 'ABSPATH' ) )
		exit;

if( ! class_exists( 'WC_Wallet_User' ) ){

	final class WC_Wallet_User{

		public static function phone_number( $id ){
				return get_user_meta( $id, 'wp_user_phone', true );
		}

		public static function get_id(){
				return get_current_user_ID();
		}

		public static function wallet_amount( $id ){
				return get_user_meta( $id, 'wallet-amount', true );
		}

		public static function set_user_wallet( $id, $secondary_user_email, $amount ) {
			global $wc_transaction;
			$secondary_user = get_user_by( 'email', $secondary_user_email );
			if ( ! $secondary_user ) {
				return false;
			}

			$secondary_user_id = $secondary_user->ID;

			$wallet_amount = self::wallet_amount( $id );

			$seconday_user_wallet_amount = self::wallet_amount( $secondary_user_id );
			if ( $wallet_amount >= $amount ) {
				$updated_amount = $wallet_amount - $amount;

				if ( ! empty( $seconday_user_wallet_amount ) ) {
					$secondary_user_updated_amount = $seconday_user_wallet_amount + $amount;
				} else {
					$secondary_user_updated_amount = $amount;
				}

				update_user_meta( $id, 'wallet-amount', $updated_amount );
				update_user_meta( $secondary_user_id, 'wallet-amount', $secondary_user_updated_amount );
			} else {
				return false;
			}
			$offset   = get_option( 'gmt_offset' );
			$offset = $offset * 60 . ' minutes';
			$data = array(
				'transaction_type' => 'debit',
				'amount'           => $amount,
				'sender'           => get_current_user_ID(),
				'customer'         => $secondary_user_id,
				'transaction_note' => '',
				'transaction_date' => date( 'Y-m-d H:i:s',strtotime( $offset ) ),
				'reference'        => __( 'Transfer To Customer', 'wc_wallet' ),
			);

			$wc_transaction->generate( $data );
			return true;
		}

	}

}
