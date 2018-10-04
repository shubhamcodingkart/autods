<?php
/**
 * Dokan Settings Address form Template
 *
 * @since 2.4
 *
 * @package dokan
 */
?>

<?php

$address         = isset( $profile_info['address'] ) ? $profile_info['address'] : '';
$address_street1 = isset( $profile_info['address']['street_1'] ) ? $profile_info['address']['street_1'] : '';
$address_street2 = isset( $profile_info['address']['street_2'] ) ? $profile_info['address']['street_2'] : '';
$address_city    = isset( $profile_info['address']['city'] ) ? $profile_info['address']['city'] : '';
$address_zip     = isset( $profile_info['address']['zip'] ) ? $profile_info['address']['zip'] : '';
$address_country = isset( $profile_info['address']['country'] ) ? $profile_info['address']['country'] : '';
$address_state   = isset( $profile_info['address']['state'] ) ? $profile_info['address']['state'] : '';

?>

<input type="hidden" id="dokan_selected_country" value="<?php echo $address_country?>" />
<input type="hidden" id="dokan_selected_state" value="<?php echo $address_state?>" />
<div class="dokan-form-group">
    <label class="dokan-w3 dokan-control-label" for="setting_address"><?php _e( 'Address', 'dokan-lite' ); ?></label>

    <div class="dokan-w5 dokan-text-left dokan-address-fields">
        <?php 
        
        if ( $seller_address_fields['city'] || $seller_address_fields['zip'] ) {
        ?>
            <div class="dokan-from-group">
                <?php if ( $seller_address_fields['city'] ) { ?>
                    <div class="dokan-form-group dokan-w6 dokan-left dokan-right-margin-30">
                        <!-- <label class="control-label" for="dokan_address[city]"><?php _e( 'City', 'dokan-lite' ); ?> -->
                            <?php
                            $required_attr = '';
                            if ( $seller_address_fields['city']['required'] ) {
                                $required_attr = 'required'; ?>
                                <span class="required"> *</span>
                            <?php } ?>
                        </label>
                        <input <?php echo $required_attr; ?> <?php echo $disabled ?> id="dokan_address[city]" value="<?php echo esc_attr( $address_city ); ?>" name="dokan_address[city]" placeholder="<?php _e( 'Town / City' , 'dokan-lite' ) ?>" class="dokan-form-control input-md" type="text">
                    </div>
                <?php }
                if ( $seller_address_fields['zip'] ) { ?>
                    
                <?php } ?>
                <div class="dokan-clearfix"></div>
            </div>
        <?php }

        if ( $seller_address_fields['country'] ) {
            $country_obj   = new WC_Countries();
            $countries     = $country_obj->countries;
            $states        = $country_obj->states;
        ?>
            <div class="dokan-form-group">
                <label class="control-label" for="dokan_address[country]"><?php _e( 'Country ', 'dokan-lite' ); ?>
                    <?php
                    $required_attr = '';
                    if ( $seller_address_fields['country']['required'] ) {
                        $required_attr = 'required'; ?>
                        <span class="required"> *</span>
                    <?php } ?>
                </label>
                <select <?php echo $required_attr; ?> <?php echo $disabled ?> name="dokan_address[country]" class="country_to_state dokan-form-control" id="dokan_address_country">
                    <?php dokan_country_dropdown( $countries, $address_country, false ); ?>
                </select>
            </div>
        <?php }
        ?>
    </div>
</div>
