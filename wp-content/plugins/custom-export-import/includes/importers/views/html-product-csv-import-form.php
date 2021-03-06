<?php
/**
 * Admin View: Product import form
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>
<form class="wc-progress-form-content woocommerce-importer" enctype="multipart/form-data" method="post">
    <header>
        <h2><?php esc_html_e( 'Update products from a CSV file', 'dokan' ); ?></h2>
        <p><?php esc_html_e( 'This tool allows you to update product data to your store from a CSV file.', 'dokan' ); ?></p>
    </header>
    <section>
        <table class="form-table woocommerce-importer-options">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="upload">
                            <?php _e( 'Choose a CSV file from your computer:', 'dokan' ); ?>
                        </label>
                    </th>
                    <td>
                        <?php
                        if ( !empty( $upload_dir['error'] ) ) {
                            ?><div class="inline error">
                                <p><?php esc_html_e( 'Before you can upload your import file, you will need to fix the following error:', 'dokan' ); ?></p>
                                <p><strong><?php echo esc_html( $upload_dir['error'] ); ?></strong></p>
                            </div><?php
                        } else {
                            ?>
                            <input type="file" id="upload" name="import" size="25" />
                            <input type="hidden" name="action" value="save" />
                            <input type="hidden" name="max_file_size" value="<?php echo esc_attr( $bytes ); ?>" />
                            <br><small><?php
                                /* translators: %s: maximum upload size */
                                printf(
                                __( 'Maximum size: %s', 'dokan' ), $size
                                );
                                ?></small>
                                <?php
                        }
                        ?>
                    </td>
                </tr>
<!--				<tr>
                        <th><label for="woocommerce-importer-update-existing"><?php _e( 'Update existing products', 'dokan' ); ?></label><br/></th>
                        <td>
                                <input type="hidden" name="update_existing" value="0" />
                                <input type="checkbox" id="woocommerce-importer-update-existing" name="update_existing" value="1" />
                                <label for="woocommerce-importer-update-existing"><?php esc_html_e( 'If a product being imported matches an existing product by ID or SKU, update the existing product rather than creating a new product or skipping the row.', 'dokan' ); ?></label>
                        </td>
                </tr>-->
<!--				<tr class="woocommerce-importer-advanced hidden">
                        <th>
                                <label for="woocommerce-importer-file-url"><?php _e( '<em>or</em> enter the path to a CSV file on your server:', 'dokan' ); ?></label>
                        </th>
                        <td>
                                <label for="woocommerce-importer-file-url" class="woocommerce-importer-file-url-field-wrapper">
                                        <code><?php echo esc_html( ABSPATH ) . ' '; ?></code><input type="text" id="woocommerce-importer-file-url" name="file_url" />
                                </label>
                        </td>
                </tr>
                <tr class="woocommerce-importer-advanced hidden">
                        <th><label><?php _e( 'CSV Delimiter', 'dokan' ); ?></label><br/></th>
                        <td><input type="text" name="delimiter" placeholder="," size="2" /></td>
                </tr>-->
            </tbody>
        </table>
    </section>
    <script type="text/javascript">
            jQuery( function () {
                jQuery( '.woocommerce-importer-toggle-advanced-options' ).on( 'click', function () {
                    var elements = jQuery( '.woocommerce-importer-advanced' );
                    if ( elements.is( '.hidden' ) ) {
                        elements.removeClass( 'hidden' );
                        jQuery( this ).text( jQuery( this ).data( 'hidetext' ) );
                    } else {
                        elements.addClass( 'hidden' );
                        jQuery( this ).text( jQuery( this ).data( 'showtext' ) );
                    }
                    return false;
                } );
            } );
    </script>
    <div class="wc-actions">
            <!--<a href="#" class="woocommerce-importer-toggle-advanced-options" data-hidetext="<?php esc_html_e( 'Hide advanced options', 'dokan' ); ?>" data-showtext="<?php esc_html_e( 'Hide advanced options', 'dokan' ); ?>"><?php esc_html_e( 'Show advanced options', 'dokan' ); ?></a>-->
        <input type="submit" class="button button-primary button-next" value="<?php esc_attr_e( 'Continue', 'dokan' ); ?>" name="save_step" />
        <?php wp_nonce_field( 'woocommerce-csv-importer' ); ?>
    </div>
</form>
