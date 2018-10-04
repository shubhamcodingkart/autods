<?php

if( ! defined( 'ABSPATH' ) )
    exit;

?>

      <p class="form-row form-row-wide woocommerce-FormRow woocommerce-FormRow--wide">

         <label for="reg_user_phone_number"><?php _e( 'Phone Number', 'woocommerce' ); ?><span class="required">*</span></label>

         <input type="text" class="input-text" name="reg_user_phone_number" id="reg_phone_number" value="<?php if ( ! empty( $_POST['reg_user_phone_number'] ) ) esc_attr_e( $_POST['reg_user_phone_number'] ); ?>" />

      </p>
