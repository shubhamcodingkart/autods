<?php

if( ! defined ( 'ABSPATH' ) )

	exit;

function send_wallet_verification_code(){

	$code = rand(100000, 999999);

	$id = $_POST['wc-wallet-user'];

	$phone_number = WC_Wallet_User::phone_number( $id );

	if ( get_option( 'woocommerce_customer_otp_verification', true ) === 'on' && ( get_option( 'woocommerce_customer_otp_access_method' ) === 'sms' || get_option( 'woocommerce_customer_otp_access_method' ) === 'mail' ) ) {

		if ( get_option( 'woocommerce_customer_otp_access_method' ) === 'sms' && empty( $phone_number ) ) {
			wc_add_notice( 'Phone number is missing. So, please provide your phone number for OTP verification via SMS.', 'error' );

			wp_safe_redirect( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '/wallet' );

			exit();
		}

		do_action( 'wc_wallet_send_verification_code', $code, $phone_number );

		do_action( 'wc_wallet_after_send_verification_code', $id, $code );

	}

	return true;

}

function get_wallet_verification_code() {

	$id = $_POST['wc-wallet-user'];

	$code = $_POST['wallet-verification-code'];

	$secondary_user = $_POST['wc-wallet-transfer-user'];

	$transfer_amount = $_POST['wc-wallet-user-transfer-amount'];

	$result = WC_Wallet_SQL_Helper::get_otp_code( $id );

	if ( ! empty( $result ) ) {

		$start_time = $result[0]->expiry;
		$time = (-1) * get_option( 'woocommerce_wallet_twilio_otp_validation_limit', true );

		if ( $start_time < strtotime( $time . ' seconds' ) ) {

			return 'Code Expired. Please retry.';
		}

		if ( $result[0]->verification_code === $code ) {

			$response = WC_Wallet_SQL_Helper::delete_otp_code( $result[0]->id );

			$wallet_updation = WC_Wallet_User::set_user_wallet( $id, $secondary_user, $transfer_amount );

			if( $wallet_updation == 'true' ) {

				if($response){

					return true;

				}
				else{

					return 'Code Verification Failed.';

				}

			}
			else{

				return 'Insufficient Amount.';

			}

			return 'Code Verification Failed.';

		}
		else{

			$message = 'Please enter a valid code.';

			return $message;

		}

	}
	else{

		return 'Please enter a valid code.';

	}

}
