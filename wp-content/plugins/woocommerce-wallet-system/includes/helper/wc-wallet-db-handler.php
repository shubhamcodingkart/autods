<?php

if( ! defined( 'ABSPATH' ) )
    exit;

if( ! class_exists( 'WC_Wallet_SQL_Helper' ) ){

  final class WC_Wallet_SQL_Helper{

    public static function insert_otp_code( $id, $code ){

      global $wpdb;

      $time = new DateTime('now');

      $now = (array)$time;

      $date_time = strtotime($time->date);

      $phone_number = WC_Wallet_User::phone_number($id);

      $table_name = $wpdb->prefix . 'wallet_verification_code';

      $result = $wpdb->get_results("SELECT * FROM $table_name WHERE phone_number = $phone_number");

      if( ! $result ){

        $status = $wpdb->get_results("INSERT INTO $table_name ( phone_number, verification_code, expiry ) VALUES ( '$phone_number', $code, $date_time ) ");

      }
      else{

        $status = $wpdb->get_results("UPDATE $table_name SET phone_number = $phone_number, verification_code = $code, expiry = $date_time");

      }

    }

    public static function get_otp_code( $id ){

      global $wpdb;

      $table_name = $wpdb->prefix . 'wallet_verification_code';

      $phone_number = WC_Wallet_User::phone_number( $id );

      $result = $wpdb->get_results("SELECT * FROM $table_name WHERE phone_number = $phone_number");

      return $result;

    }

    public static function delete_otp_code( $code_id ){

      global $wpdb;

      $table_name = $wpdb->prefix . 'wallet_verification_code';

      $result = $wpdb->query( "DELETE FROM $table_name WHERE id = $code_id" );

      if($result){
        return true;
      }
      else{
        return false;
      }

    }

  }

}
