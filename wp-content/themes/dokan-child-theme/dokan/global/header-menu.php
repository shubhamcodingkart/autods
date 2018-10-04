<?php
/**
 * Dokan Header Menu Template
 *
 * @since 2.4
 *
 * @package dokan
 */
$wallet_user = new WC_Wallet_User(); 
$user_id=$wallet_user->get_id();
$wallet_amount=$wallet_user->wallet_amount($user_id);
if($wallet_amount==""){$wallet_amount=0;}
// calling function codingkart_customer_check_user_type with help of BaseCustomer class object
if (is_user_logged_in()) {
    $mBaseCustomer=new BaseCustomer();
    $check_type=$mBaseCustomer->codingkart_customer_check_user_type('subscriber');
}

?>

<ul class="nav navbar-nav navbar-right">
    <?php 
    if (is_user_logged_in()) {
        if($check_type){ ?>
            <li>
                <a id="autods-tokens-dropdown-toggle">Store 
                    <?php  
                        $set_autods_token = $mBaseCustomer->codingkart_get_set_autods_token($user_id); 
                        if ( !empty($set_autods_token) ) {
                            echo '( '.$set_autods_token.' )';
                        }
                    ?>
                    <b class="caret"></b>
                </a>
            
                <div id="autods-tokens-dropdown-content" style='display:none'>
                    <select name="selectbox_autods_tokens">
                         <option value="">Select one...</option>
                        <?php 
                            $autods_tokens = $mBaseCustomer->codingkart_get_user_all_tokens($user_id);
                            foreach ($autods_tokens as $key => $tokens) {
                                echo '<option value="'.$tokens.'">'.$tokens.'</option>';
                            }
                        ?>
                    </select>
                </div>
            </li>

            <li>
                <a href="<?php echo wc_get_endpoint_url( 'customer-wallet', '', get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>" class="">Gift Balance <span class="dokan-cart-amount-top">(<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"><?php echo get_woocommerce_currency_symbol("USD"); ?></span><?php echo $wallet_amount; ?></span>)</span></a>
            </li>
    <?php } }?>
    <li>
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php printf( __( 'Cart %s', 'dokan-lite' ), '<span class="dokan-cart-amount-top">(' . WC()->cart->get_cart_total() . ')</span>' ); ?> <b class="caret"></b></a>

        <ul class="dropdown-menu">
            <li>
                <div class="widget_shopping_cart_content"></div>
            </li>
        </ul>
    </li>

    <?php if ( is_user_logged_in() ) { ?>

        <?php

        if ( dokan_is_user_seller( $user_id ) ) {
            ?>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php _e( 'Vendor Dashboard', 'dokan-lite' ); ?> <b class="caret"></b></a>

                <ul class="dropdown-menu">
                    <li><a href="<?php echo dokan_get_store_url( $user_id ); ?>" target="_blank"><?php _e( 'Visit your store', 'dokan-lite' ); ?> <i class="fa fa-external-link"></i></a></li>
                    <li class="divider"></li>
                    <?php
                    foreach ( $nav_urls as $key => $item ) {
                        printf( '<li><a href="%s">%s &nbsp;%s</a></li>', $item['url'], $item['icon'], $item['title'] );
                    }
                    ?>
                </ul>
            </li>
        <?php } ?>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo esc_html( $current_user->display_name ); ?> <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo dokan_get_page_url( 'my_orders' ); ?>"><?php _e( 'My Orders', 'dokan-lite' ); ?></a></li>
                <li><a href="<?php echo dokan_get_page_url( 'myaccount', 'woocommerce' ); ?>"><?php _e( 'My Account', 'dokan-lite' ); ?></a></li>
                <li><a href="<?php echo wc_customer_edit_account_url(); ?>"><?php _e( 'Edit Account', 'dokan-lite' ); ?></a></li>
                <li class="divider"></li>
                <li><a href="<?php echo wc_get_endpoint_url( 'edit-address', 'billing', get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>"><?php _e( 'Billing Address', 'dokan-lite' ); ?></a></li>
                <li><a href="<?php echo wc_get_endpoint_url( 'edit-address', 'shipping', get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>"><?php _e( 'Shipping Address', 'dokan-lite' ); ?></a></li>
            </ul>
        </li>

        <li><?php wp_loginout( home_url() ); ?></li>

    <?php } else { ?>
        <li><a href="<?php echo dokan_get_page_url( 'myaccount', 'woocommerce' ); ?>"><?php _e( 'Log in', 'dokan-lite' ); ?></a></li>
        <li><a href="<?php echo site_url(); ?>/index.php/sign-up/"><?php _e( 'Sign Up', 'dokan-lite' ); ?></a></li>
    <?php } ?>
</ul>
