<?php
/**
 *  Dahsboard Widget Template
 *
 *  Get dokan dashboard widget template
 *
 *  @since 2.4
 *
 *  @package dokan
 *
 */
?>

<?php
    $obj = new EditOrder;
    $orders_counts        = $obj->dokan_count_orders_with_custom_orders( dokan_get_current_user_id() ); 

    $order_datas = array(
            array( 'value' => $orders_counts->{'wc-completed'}, 'color' => '#73a724', 'label' => __( 'Completed', 'dokan-lite' ) ),
            array( 'value' => $orders_counts->{'wc-pending'}, 'color' => '#999', 'label' => __( 'Pending', 'dokan-lite' ) ),
            array( 'value' => $orders_counts->{'wc-cancelled'}, 'color' => '#d54e21', 'label' => __( 'Cancelled', 'dokan-lite' ) ),
            array( 'value' => $orders_counts->{'wc-shipped'}, 'color' => '#3498DB', 'label' => __( 'Shipped', 'dokan-lite' ) ),
            array( 'value' => $orders_counts->{'wc-approved'}, 'color' => '#1ABC9C', 'label' => __( 'Approved', 'dokan-lite' ) ),
    );

?>

<div class="dashboard-widget orders">
    <div class="widget-title"><i class="fa fa-shopping-cart"></i> <?php _e( 'Orders', 'dokan-lite' ); ?></div>

    <div class="content-half-part">
        <ul class="list-unstyled list-count">
            <li>
                <a href="<?php echo $orders_url; ?>">
                    <span class="title"><?php _e( 'Total', 'dokan-lite' ); ?></span> <span class="count"><?php echo $orders_counts->total; ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo add_query_arg( array( 'order_status' => 'wc-completed' ), $orders_url ); ?>" style="color: <?php echo $order_datas[0]['color']; ?>">
                    <span class="title"><?php _e( 'Completed', 'dokan-lite' ); ?></span> <span class="count"><?php echo number_format_i18n( $orders_counts->{'wc-completed'}, 0 ); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo add_query_arg( array( 'order_status' => 'wc-pending' ), $orders_url ); ?>" style="color: <?php echo $order_datas[1]['color']; ?>">
                    <span class="title"><?php _e( 'Pending', 'dokan-lite' ); ?></span> <span class="count"><?php echo number_format_i18n( $orders_counts->{'wc-pending'}, 0 );; ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo add_query_arg( array( 'order_status' => 'wc-cancelled' ), $orders_url ); ?>" style="color: <?php echo $order_datas[2]['color']; ?>">
                    <span class="title"><?php _e( 'Cancelled', 'dokan-lite' ); ?></span> <span class="count"><?php echo number_format_i18n( $orders_counts->{'wc-cancelled'}, 0 ); ?></span>
                </a>
            </li>
             <li>
                <a href="<?php echo add_query_arg( array( 'order_status' => 'wc-shipped' ), $orders_url ); ?>" style="color: <?php echo $order_datas[3]['color']; ?>">
                    <span class="title"><?php _e( 'Shipped', 'dokan-lite' ); ?></span> <span class="count"><?php echo number_format_i18n( $orders_counts->{'wc-shipped'}, 0 ); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo add_query_arg( array( 'order_status' => 'wc-approved' ), $orders_url ); ?>" style="color: <?php echo $order_datas[4]['color']; ?>">
                    <span class="title"><?php _e( 'Approved', 'dokan-lite' ); ?></span> <span class="count"><?php echo number_format_i18n( $orders_counts->{'wc-approved'}, 0 ); ?></span>
                </a>
            </li>
        </ul>
    </div>
    <div class="content-half-part">
        <canvas id="order-stats"></canvas>
    </div>
</div> <!-- .orders -->

<script type="text/javascript">
    jQuery(function($) {
        var order_stats = <?php echo json_encode( wp_list_pluck( $order_datas, 'value' ) ); ?>;
        var colors = <?php echo json_encode( wp_list_pluck( $order_datas, 'color' ) ); ?>;
        var labels = <?php echo json_encode( wp_list_pluck( $order_datas, 'label' ) ); ?>;

        var ctx = $("#order-stats").get(0).getContext("2d");
        var donn = new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: order_stats,
                    backgroundColor: colors
                }],
                labels: labels,
            },
            options: {
                legend: {
                    display: false
                }
            }
        });
    });
</script>
