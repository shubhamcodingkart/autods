<?php

if( ! defined ( 'ABSPATH' ) )
	exit;



global $current_user;

$to       = $current_user->user_email;
$subject  = esc_html( __( 'OTP For Wallet Transaction', 'wc_wallet' ) );
$message  = __( 'The OTP for Wallet Transaction is :  ', 'wc_wallet' ) . $code;
$headers  = "From : " . bloginfo( 'name' ) . "\r\n";
$mail_check = wp_mail( $to, $subject, $message, $headers );

if ( ! $mail_check ) {

	echo '<h2 class="error">' . esc_html( __( 'Mail is not configured properly', 'wc_wallet' ) ) . '</h2>';

}
