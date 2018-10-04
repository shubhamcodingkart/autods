<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package dokan
 * @package dokan - 2014 1.0
 */
?>
</div><!-- .row -->
</div><!-- .container -->
</div><!-- #main .site-main -->

<footer id="colophon" class="site-footer" role="contentinfo">
    <div class="footer-widget-area">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <?php dynamic_sidebar( 'footer-1' ); ?>
                </div>

                <div class="col-md-3">
                    <?php dynamic_sidebar( 'footer-2' ); ?>
                </div>

                <div class="col-md-3">
                    <?php dynamic_sidebar( 'footer-3' ); ?>
                </div>

                <div class="col-md-3">
                    <?php //dynamic_sidebar( 'footer-4' ); ?>
                    <aside id="" class="widget widget_pages">
                        <ul>
                            <li class=""><a href="<?php echo dokan_get_navigation_url(); ?>">Dashboard</a></li>
                            <li class=""><a href="<?php echo dokan_get_navigation_url( 'orders' ); ?>">Orders</a></li>
                            <li class=""><a href="<?php echo dokan_get_navigation_url( 'products' ); ?>">Products</a></li>
                        </ul>
                    </aside>
                </div>
            </div> <!-- .footer-widget-area -->
        </div>
    </div>

    <div class="copy-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="footer-copy">
                        <div class="col-md-12 site-info text-center">
                            <?php
                            $footer_text = get_theme_mod( 'footer_text' );

                            if ( empty( $footer_text ) ) {
                                printf( __( '2018 AutoBooster, All rights are reserved to AutoDS LTD.', 'dokan-theme' ), date( 'Y' ), get_bloginfo( 'name' ) );
                                printf( __( '', 'dokan-theme' ), esc_url( 'http://wedevs.com/theme/dokan/?utm_source=dokan&utm_medium=theme_footer&utm_campaign=product' ), esc_url( 'http://wedevs.com/?utm_source=dokan&utm_medium=theme_footer&utm_campaign=product' ) );
                            } else {
                                echo $footer_text;
                            }
                            ?>
                        </div><!-- .site-info -->
                        <!-- hide for now -->
                        <!-- <div class="col-md-6 footer-gateway">
                            <?php
                                wp_nav_menu( array(
                                    'theme_location'  => 'footer',
                                    'depth'           => 1,
                                    'container_class' => 'footer-menu-container clearfix',
                                    'menu_class'      => 'menu list-inline pull-right',
                                ) );
                            ?>
                        </div> -->
                    </div>
                </div>
            </div><!-- .row -->
        </div><!-- .container -->
    </div> <!-- .copy-container -->
</footer><!-- #colophon .site-footer -->
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

<!-- Add new product tag on vendor product page -->
<script type="text/javascript">
    jQuery(document).ready(function($) {
        jQuery("select#custom_product_tag").select2({
          placeholder: "Select product tags",    
          tags: true,
          width: '100%',
          createTag: function (params) {
            return {
              id: params.term,
              text: params.term,
              newOption: true
            }
          },
           templateResult: function (data) {
            var $result = jQuery("<span></span>");

            $result.text(data.text);

            if (data.newOption) {
                $result.prepend("Add ");
                $result.append("...");
            }

            return $result;
          }
        });
    });
</script>

<div id="yith-wcwl-popup-message" style="display:none;"><div id="yith-wcwl-message"></div></div>
</body>
</html>