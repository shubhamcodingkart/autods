
<?php
/**
 *  Dokan Dashboard Template
 *
 *  Dokan Main Dahsboard template for Fron-end
 *
 *  @since 2.4
 *
 *  @package dokan
 */
?>
<div class="dokan-dashboard-wrap">
    <?php
        /**
         *  dokan_dashboard_content_before hook
         *
         *  @hooked get_dashboard_side_navigation
         *
         *  @since 2.4
         */
        do_action( 'dokan_dashboard_content_before' );
    ?>

    <div class="dokan-dashboard-content">

        <?php
            /**
             *  dokan_dashboard_content_before hook
             *
             *  @hooked show_seller_dashboard_notice
             *
             *  @since 2.4
             */
            do_action( 'dokan_help_content_inside_before' );
        ?>

        <article class="product_inetgration-content-area">
	        <div class="container bg">
				<div class="row">
			    	<!-- CSV Products Update -->
					<div class="col-xs-12 col-sm-6 col-md-2 rwrapper">
						<a href="<?php echo dokan_get_navigation_url( 'update_products/csv-import' ) ?>">
					     	<div class="rlisting">
						        <div class="col-md-12 nopad">
						          <img src="<?php echo get_stylesheet_directory_uri(). '/product-integration/img/product-import-img.png'; ?>" class="img-responsive" style="cursor: pointer;">
						        </div>
						        <div class="col-md-12 nopad">
						          	<h5>Update from CSV</h5>
						         	<p>Update product data to your store from a CSV file.</p>
						        </div>
					      	</div>
				      	</a>
				    </div>
				</div>
			</div>
		</article><!-- .dashboard-content-area -->

         <?php
            /**
             *  dokan_dashboard_content_inside_after hook
             *
             *  @since 2.4
             */
            do_action( 'dokan_dashboard_content_inside_after' );
        ?>


    </div><!-- .dokan-dashboard-content -->

    <?php
        /**
         *  dokan_dashboard_content_after hook
         *
         *  @since 2.4
         */
        do_action( 'dokan_dashboard_content_after' );
    ?>

</div><!-- .dokan-dashboard-wrap -->

</div><!-- .row -->
</div><!-- .container -->
</div><!-- #main .site-main -->
<?php get_footer(); ?>