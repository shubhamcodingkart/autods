<?php

if( ! defined( 'ABSPATH' ) )
    exit;
    

require WC_WALLET . 'vendor/autoload.php';

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

// Your Account SID and Auth Token from twilio.com/console
$sid = get_option( 'woocommerce_wallet_twilio_sid', true );

$token = get_option( 'woocommerce_wallet_twilio_auth_token', true );

$twilio_number = get_option( 'woocommerce_wallet_twilio_number', true );

$client = new Client($sid, $token);

$callback_url = WP_WALLET . 'includes/front/save-wallet-data.php';

// Use the client to do fun stuff like send text messages!
$client->messages->create(
    // the number you'd like to send the message to
    $phone_number,
    array(
        // A Twilio phone number you purchased at twilio.com/console
        'from' => $twilio_number,
        // the body of the text message you'd like to send
        'body' => $code
        // 'statusCallback' => $callback_url
    )
);
