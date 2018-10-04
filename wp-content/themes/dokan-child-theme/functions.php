<?php
function load_auto_renew_function(){

    require_once('classes/rest/BaseRest.php');
    require_once('classes/rest/BaseNewOrderApi.php');

    require_once('classes/account/BaseAccount.php');
	require_once('classes/account/EditAddress.php');
    require_once('classes/account/EditOrder.php');
    require_once('classes/account/EditShipping.php');
    require_once('classes/account/EditPayment.php');
    require_once('classes/account/EditProduct.php');
	
	require_once('classes/customers/BaseCustomer.php');
	
    require_once('classes/products/BaseProduct.php');
	require_once('classes/products/ProductCustom.php');
	
	require_once('classes/dokan/DokanCustom.php');

	require_once('classes/cart/BaseCart.php');

    // product integration
    require_once('product-integration/classes/BaseProductIntegration.php');

}
add_action('init','load_auto_renew_function');


// wallet files
include_once('wallet/BaseWallet.php');
include_once('wallet/CustomEndpoint.php');
include_once('wallet/CustomWalletCheckout.php');

// custom product category Widtet include file
include_once('classes/widgets/CustomCategoryWidget.php');

// custom product category(vendor store) Widtet include file
include_once('classes/widgets/Custom_Dokan_Store_Category_Menu.php');
include_once('classes/widgets/store-menu-category.php');

// register the custom category Widtet
function myplugin_register_widgets() {
    register_widget( 'Custom_Dokan_Category_Widget');
    register_widget( 'Custom_Dokan_Store_Category_Menu');
}

add_action( 'widgets_init', 'myplugin_register_widgets' );



//custom scripts
function my_custom_scripts() {  
    // BaseProduct Custom js 
    wp_enqueue_script( 'my_custom_script',get_stylesheet_directory_uri(). '/classes/products/js/product-custom.js' );

    // BaseAccount Custom js
    wp_enqueue_script( 'my_custom_script-BaseAccount',get_stylesheet_directory_uri(). '/classes/account/js/account-custom.js' );

    // BaseCart Custom js
    wp_enqueue_script( 'my_custom_script-BaseCart',get_stylesheet_directory_uri(). '/classes/cart/js/cart-custom.js' );

    // js code for print order pdf
    $bulk_actions = array();
    $documents = WPO_WCPDF()->documents->get_documents();
    foreach ($documents as $document) {
        $bulk_actions[$document->get_type()] = "PDF " . $document->get_title();
    }
    $bulk_actions = apply_filters( 'wpo_wcpdf_bulk_actions', $bulk_actions );
    
    wp_localize_script(
        'my_custom_script-BaseAccount',
        'wpo_wcpdf_ajax',
        array(
            'ajaxurl'       => admin_url( 'admin-ajax.php' ), // URL to WordPress ajax handling page  
            'nonce'         => wp_create_nonce('generate_wpo_wcpdf'),
            'bulk_actions'  => array_keys( $bulk_actions ),
        )
    );

    // select2 js
    wp_enqueue_script( 'my_custom_script-select2','https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js' );
}
add_action('wp_enqueue_scripts', 'my_custom_scripts');


//custom styles
function my_custom_styles() { 
    // select2 css  
    wp_enqueue_style( 'my_custom_style','https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css' );

    // BaseProduct Custom CSS
    wp_enqueue_style( 'my_custom_product_style',get_stylesheet_directory_uri(). '/classes/products/css/custom-product-styles.css' );

    // BaseAccount Custom CSS
    wp_enqueue_style( 'account_custom_style',get_stylesheet_directory_uri(). '/classes/account/css/account-custom.css' );

}
add_action('wp_enqueue_scripts', 'my_custom_styles');

// Plugin activate by detault 
function plugin_activation( $plugin ) {
    if( ! function_exists('activate_plugin') ) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    if( ! is_plugin_active( $plugin ) ) {
        activate_plugin( $plugin );
    }
}

plugin_activation('custom-export-import/export-import.php');
?>