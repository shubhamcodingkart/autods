
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
				    <?php $user_id = get_current_user_id(); ?>

				        <!-- Magento integration -->
				        <?php 
				        	$check_import_products_magento_status = get_user_meta( $user_id, '_import_products_magento_status', true );
				        	$import_products_magento_data = get_user_meta( get_current_user_id(), '_import_products_magento_crednetials', true );
							$unserialize_data = unserialize($import_products_magento_data); 
						?>
				        <div class="col-xs-12 col-sm-6 col-md-2 rwrapper import-products-box-magento <?php if($check_import_products_magento_status == 1){echo 'product_validation_enabled';}?>">
					     	<div class="rlisting">
						        <div class="col-md-12 nopad">
						          <img src="<?php echo get_stylesheet_directory_uri(). '/product-integration/img/mangento-integration-img.png'; ?>" class="img-responsive" style="cursor: pointer;">
						        </div>
						        <div class="col-md-12 nopad">
						          	<h5>Magento</h5>
						         	<p>An open-source e-commerce platform written in PHP.</p>
						        </div>
					      	</div>
					    </div>
					    <!-- Magento integration Popup-->
					    <div id="import_products_magento_modal" class="modal fade" role="dialog">
							<div class="modal-dialog">
							  	<!-- Modal content-->
							    <div class="modal-content">
							     	<div class="modal-body">
							     		<h4 class="modal-title">Magento Product Integration</h4>
										<form class="import_products_magento_credentials_form" method="post">
									        <div class="form-group">
											    <label>URL: </label>
											    <input type="text" class="form-control required" name="url" placeholder="Enter SNS URL" value="<?php echo $unserialize_data['url'];?>">
											</div>
											<div class="form-group">
											    <label>Consumer Key:</label>
											    <input type="text" class="form-control required" name="consumer_key" placeholder="Enter Access Key" value="<?php echo $unserialize_data['consumer_key'];?>">
											</div>
											<div class="form-group">
											    <label>Token:</label>
											    <input type="text" class="form-control required" name="token" placeholder="Enter Secret Key" value="<?php echo $unserialize_data['token'];?>">
											</div>
									        <input type="hidden" name="post_id" id="post_id" value="<?php echo get_the_ID(); ?>">
									        <button type="submit" class="btn btn-primary">Submit</button>
								    	</form>
							      	</div>
							      	<div class="modal-footer">
								        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								    </div>
							    </div>
							</div>
						</div>

						<!-- ------------------------------------------------------------------------- -->
						
						<!-- CSV Products Import -->
						<div class="col-xs-12 col-sm-6 col-md-2 rwrapper">
							<a href="<?php echo dokan_get_navigation_url( 'tools/csv-import' ) ?>">
						     	<div class="rlisting">
							        <div class="col-md-12 nopad">
							          <img src="<?php echo get_stylesheet_directory_uri(). '/product-integration/img/product-import-img.png'; ?>" class="img-responsive" style="cursor: pointer;">
							        </div>
							        <div class="col-md-12 nopad">
							          	<h5>Import from CSV</h5>
							         	<p>Import product data to your store from a CSV file.</p>
							        </div>
						      	</div>
					      	</a>
					    </div>
					
					<?php 
						// Successful integration popup
						$successful_integration_popup = new BaseProductIntegration;
						$successful_integration_popup->codingkart_successful_integration_popup();
					?>
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