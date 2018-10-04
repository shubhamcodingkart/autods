<?php
global $woocommerce;

$seller_id    = dokan_get_current_user_id();
$customer_id  = isset( $_GET['customer_id'] ) ? sanitize_key( $_GET['customer_id'] ) : null;
$order_status = isset( $_GET['order_status'] ) ? sanitize_key( $_GET['order_status'] ) : 'all';
$paged        = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$limit        = 10;
$offset       = ( $paged - 1 ) * $limit;
$order_date   = isset( $_GET['order_date'] ) ? sanitize_key( $_GET['order_date'] ) : NULL;
$user_orders  = dokan_get_seller_orders( $seller_id, $order_status, $order_date, $limit, $offset, $customer_id );

if ( $user_orders ) {
    ?>
    <table class="dokan-table dokan-table-striped">
        <thead>
            <tr>
                <th><input type="checkbox" name="check_all_orders" id="check_all_orders"></th>
                <th><?php _e( 'Order ID', 'dokan-lite' ); ?></th>
                <th><?php _e( 'Picture', 'dokan-lite' ); ?></th>
                <th><?php _e( 'Customer Address', 'dokan-lite' ); ?></th>
                <th><?php _e( 'Customer Name', 'dokan-lite' ); ?></th>
                <th><?php _e( 'Date', 'dokan-lite' ); ?></th>
                <th><?php _e( 'Quantity', 'dokan-lite' ); ?></th>
                <th><?php _e( 'Total', 'dokan-lite' ); ?></th>
                <th><?php _e( 'Status', 'dokan-lite' ); ?></th>
                <th></th>
                <!-- <?php if ( current_user_can( 'dokan_manage_order' ) ): ?>
                    <th width="17%"><?php _e( 'Action', 'dokan-lite' ); ?></th>
                <?php endif ?> -->
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($user_orders as $order) {
                $the_order = new WC_Order( $order->order_id );
                ?>
                <tr >
                    <td><input type="checkbox" class="single_order_checkbox" name="order_id[]" value="<?php echo $order->order_id; ?>"></td>
                    <td class="dokan-order-id" data-title="<?php _e( 'Order', 'dokan-lite' ); ?>" >
                        <?php if ( current_user_can( 'dokan_view_order' ) ): ?>
                            <?php echo '<a href="' . wp_nonce_url( add_query_arg( array( 'order_id' => dokan_get_prop( $the_order, 'id' ) ), dokan_get_navigation_url( 'orders' ) ), 'dokan_view_order' ) . '"><strong>' . sprintf( __( 'Order %s', 'dokan-lite' ), esc_attr( $the_order->get_order_number() ) ) . '</strong></a>'; ?>
                        <?php else: ?>
                            <?php echo '<strong>' . sprintf( __( 'Order %s', 'dokan-lite' ), esc_attr( $the_order->get_order_number() ) ) . '</strong>'; ?>
                        <?php endif ?>
                    </td>
                    <td style="max-width: 100px;"><?php $user_id = $the_order->get_user_id();
                        //echo '<img src="">';
                        $order_id = $order->order_id;
                        $order = wc_get_order( $order_id );
                        foreach( $order->get_items() as $item_id => $item ) {
                                $product = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
                                echo $product->get_image();
                        }

                     ?></td>
                    <td><?php 
                        
                        $shipping_address_1 = get_user_meta( $user_id, 'shipping_address_1', true ).'&nbsp';
                        $shipping_address_2 = get_user_meta( $user_id, 'shipping_address_2', true ).'&nbsp';
                        $shipping_city = get_user_meta( $user_id, 'shipping_city', true ).', ';
                        $shipping_state = get_user_meta( $user_id, 'shipping_state', true ).'&nbsp';
                        $shipping_postcode = get_user_meta( $user_id, 'shipping_postcode', true ).'&nbsp';
                        $shipping_country = get_user_meta( $user_id, 'shipping_country', true ).'&nbsp';

                        echo $shipping_address_1.$shipping_address_2.$shipping_city.$shipping_state.$shipping_postcode.$shipping_country;

                    ?></td>
                    
                    
                    <td class="dokan-order-customer" data-title="<?php _e( 'Customer', 'dokan-lite' ); ?>" >
                        <?php

                        // reset user info
                        $user_info = '';

                        if ( $the_order->get_user_id() ) {
                            $user_info = get_userdata( $the_order->get_user_id() );
                        }

                        if ( !empty( $user_info ) ) {

                            $user = '';

                            if ( $user_info->first_name || $user_info->last_name ) {
                                $user .= esc_html( $user_info->first_name . ' ' . $user_info->last_name );
                            } else {
                                $user .= esc_html( $user_info->display_name );
                            }

                        } else {
                            $user = __( 'Guest', 'dokan-lite' );
                        }

                        echo $user;
                        ?>
                    </td>
                    <td class="dokan-order-date" data-title="<?php _e( 'Date', 'dokan-lite' ); ?>" >
                        <?php
                        if ( '0000-00-00 00:00:00' == dokan_get_date_created( $the_order ) ) {
                            $t_time = $h_time = __( 'Unpublished', 'dokan-lite' );
                        } else {
                            $t_time = get_the_time( __( 'Y/m/d g:i:s A', 'dokan-lite' ), dokan_get_prop( $the_order, 'id' ) );

                            $gmt_time = strtotime( dokan_get_date_created( $the_order ) . ' UTC' );
                            $time_diff = current_time( 'timestamp', 1 ) - $gmt_time;

                            if ( $time_diff > 0 && $time_diff < 24 * 60 * 60 )
                                $h_time = sprintf( __( '%s ago', 'dokan-lite' ), human_time_diff( $gmt_time, current_time( 'timestamp', 1 ) ) );
                            else
                                $h_time = get_the_time( __( 'Y/m/d', 'dokan-lite' ), dokan_get_prop( $the_order, 'id' ) );
                        }

                        echo '<abbr title="' . esc_attr( dokan_date_time_format( $t_time ) ) . '">' . esc_html( apply_filters( 'post_date_column_time', dokan_date_time_format( $h_time, true ) , dokan_get_prop( $the_order, 'id' ) ) ) . '</abbr>';
                        ?>
                    </td>
                    <td><?php foreach( $order->get_items() as $item_id => $item ) {
                               echo $item->get_quantity(); } ?></td>
                    <td class="dokan-order-total" data-title="<?php _e( 'Order Total', 'dokan-lite' ); ?>" >
                        <?php echo $the_order->get_formatted_order_total(); ?>
                    </td>
                    <td class="dokan-order-status" data-title="<?php _e( 'Status', 'dokan-lite' ); ?>" >
                        <?php 
                            $order_data = $order->get_data();
                            $order_status = $order_data['status'];
                            $order_all_status = wc_get_order_statuses(); 
                        ?>

                        <select data-id="<?php echo $order_id; ?>" style="min-width:170px;" class ="form-control shipping_status_selectbox">
                           <?php 
                                $valid_status = array("wc-pending", "wc-approved", "wc-shipped", "wc-cancelled", "wc-completed");
                                foreach ($order_all_status as $key => $value){
                                    if (in_array($key, $valid_status)) { ?>
                                            <option value='<?php echo $key;?>' <?php if(strtolower($value) == $order_status){ echo 'selected'; }?>><span><?php echo $value;?></span></option>
                                    <?php }
                                }
                            ?>
                         </select>
                         
                    </td>
                    <td>
                        <img class="select-img-<?php echo $order_id; ?>" src="https://www2.sunwing.ca/MyBooking/Content/images/icons/loader-orange.gif" style="display: none; max-width: 20px;">
                    </td>

                    
                    <td class="diviader"></td>
                </tr>

            <?php } ?>

        </tbody>

    </table>

    <?php 
        // calling shipping status modal 
        $shipping_status_popup = new EditOrder();
        $shipping_status_popup->codingkart_order_status_shipping_popup();

    ?>

    <?php
    $order_count = dokan_get_seller_orders_number( $seller_id );

    // if date is selected then calculate number_of_pages accordingly otherwise calculate number_of_pages =  ( total_orders / limit );
    if ( ! is_null( $order_date ) ) {
        if ( count( $user_orders ) >= $limit ) {
            $num_of_pages = ceil ( ( ( $order_count + count( $user_orders ) ) - count( $user_orders ) ) / $limit );
        } else {
            $num_of_pages = ceil( count( $user_orders ) / $limit );
        }
    } else {
        $num_of_pages = ceil( $order_count / $limit );
    }


    $base_url  = dokan_get_navigation_url( 'orders' );

    if ( $num_of_pages > 1 ) {
        echo '<div class="pagination-wrap">';
        $page_links = paginate_links( array(
            'current'   => $paged,
            'total'     => $num_of_pages,
            'base'      => $base_url. '%_%',
            'format'    => '?pagenum=%#%',
            'add_args'  => false,
            'type'      => 'array',
        ) );

        echo "<ul class='pagination'>\n\t<li>";
        echo join("</li>\n\t<li>", $page_links);
        echo "</li>\n</ul>\n";
        echo '</div>';
    }
    ?>

<?php } else { ?>

    <div class="dokan-error">
        <?php _e( 'No orders found', 'dokan-lite' ); ?>
    </div>

<?php } ?>

<script>
    (function($){
        $(document).ready(function(){
            $('.datepicker').datepicker({
                dateFormat: 'yy-m-d'
            });
        });
    })(jQuery);
</script>
