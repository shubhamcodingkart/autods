<?php

/**
 * new WordPress Widget format
 * Wordpress 2.8 and above
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class Custom_Dokan_Store_Category_Menu extends WP_Widget {

    // /**
    //  * Constructor
    //  *
    //  * @return void
    //  **/
    public function __construct() {
        $widget_ops = array( 'classname' => 'dokan-store-menu', 'description' => __( 'Custom Dokan Seller Store Menu', 'dokan-lite' ) );
        parent::__construct( 'Custom_Dokan_Store_Category_Menu', __( 'Dokan: Custom Store Category Menu', 'dokan-lite' ), $widget_ops );
    }

    /**
     * Outputs the HTML for this widget.
     *
     * @param array  An array of standard parameters for widgets in this theme
     * @param array  An array of settings for this widget instance
     * @return void Echoes it's output
     **/
    function widget( $args, $instance ) {

        if ( ! dokan_is_store_page() ) {
            return;
        }

        extract( $args, EXTR_SKIP );

        echo $before_widget;

        $title      = apply_filters( 'widget_title', $instance['title'] );
        $seller_id  = (int) get_query_var( 'author' );

        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        //dokan_store_category_menu( $seller_id, $title ); ?>

        <div id="cat-drop-stack">
        <?php
        global $wpdb;

        $categories = get_transient( 'dokan-store-category-'.$seller_id );

        if ( false === $categories ) {
            $sql = "SELECT t.term_id,t.name, tt.parent FROM $wpdb->terms as t
                    LEFT JOIN $wpdb->term_taxonomy as tt on t.term_id = tt.term_id
                    LEFT JOIN $wpdb->term_relationships AS tr on tt.term_taxonomy_id = tr.term_taxonomy_id
                    LEFT JOIN $wpdb->posts AS p on tr.object_id = p.ID
                    WHERE tt.taxonomy = 'product_cat'
                    AND p.post_type = 'product'
                    AND p.post_status = 'publish'
                    AND p.post_author = $seller_id GROUP BY t.term_id";

            $categories = $wpdb->get_results( $sql );
            set_transient( 'dokan-store-category-'.$seller_id , $categories );
        }

        $args = array(
            'taxonomy'      => 'product_cat',
            'selected_cats' => ''
        );

        $walker = new Custom_Dokan_Store_Category_Walker( $seller_id );
        echo "<ul>";
        echo call_user_func_array( array(&$walker, 'walk'), array($categories, 0, array()) );
        echo "</ul>";
        ?>
        </div>

        
        <script>
            ( function ( $ ) {

                $( '#cat-drop-stack li.has-children' ).on( 'click', '> a span.caret-icon', function ( e ) {
                    e.preventDefault();
                    var self = $( this ),
                        liHasChildren = self.closest( 'li.has-children' );

                    if ( !liHasChildren.find( '> ul.children' ).is( ':visible' ) ) {
                        self.find( 'i.fa' ).addClass( 'fa-rotate-90' );
                        if ( liHasChildren.find( '> ul.children' ).hasClass( 'level-0' ) ) {
                            self.closest( 'a' ).css( { 'borderBottom': 'none' } );
                        }
                    }

                    liHasChildren.find( '> ul.children' ).slideToggle( 'fast', function () {
                        if ( !$( this ).is( ':visible' ) ) {
                            self.find( 'i.fa' ).removeClass( 'fa-rotate-90' );

                            if ( liHasChildren.find( '> ul.children' ).hasClass( 'level-0' ) ) {
                                self.closest( 'a' ).css( { 'borderBottom': '1px solid #eee' } );
                            }
                        }
                    } );
                } );

                $(document).ready(function(){
                    var selectedLi = $('#cat-drop-stack ul').find( 'a.selected' );
                    selectedLi.css({ fontWeight: 'bold' });

                    selectedLi.parents('ul.children').each( function( i, val ) {
                        $( val ).css({ display: 'block' });
                    });
                });
            } )( jQuery );
        </script>

        <?php

        echo $after_widget;
    }



    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array  An array of new settings as submitted by the admin
     * @param array  An array of the previous settings
     * @return array The validated and (if necessary) amended settings
     **/
    function update( $new_instance, $old_instance ) {

        // update logic goes here
        $updated_instance = $new_instance;
        return $updated_instance;
    }

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  An array of the current settings for this widget
     * @return void Echoes it's output
     **/
    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array(
            'title' => __( 'Category', 'dokan-lite' ),
        ) );

        $title = $instance['title'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'dokan-lite' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }
}
