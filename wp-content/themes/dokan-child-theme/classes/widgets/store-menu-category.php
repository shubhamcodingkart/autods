<?php

/**
 * Category walker for generating dokan store category
 */
class Custom_Dokan_Store_Category_Walker extends Custom_Dokan_Category_Walker {

    function __construct( $seller_id ) {
        $this->store_url = dokan_get_store_url ( $seller_id );
        $this->seller_id = $seller_id;
    }

    function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
        extract( $args );
        $indent = str_repeat( "\t", $depth );

        $url = $this->store_url . 'section/' . $category->term_id;
        $selected_cat = get_query_var( 'term' );
        $a_selected_class = $selected_cat == $category->term_id ? 'class="selected"' : '';

        if ( $depth == 0 ) {
            $caret = $args['has_children'] ? ' <span class="caret-icon"><i class="fa fa-angle-right" aria-hidden="true"></i></span>' : '';
            $class_name = $args['has_children'] ? ' class="has-children parent-cat-wrap"' : ' class="parent-cat-wrap"';
            //$output .= $indent . '<li' . $class_name . '>' . "\n\t" .'<a href="' . $url . '"'. $a_selected_class .'>' . $category->name . ' ('.$category->count.')'.  $caret . '</a>' . "\n";

            $args = array(
                'author'        =>  $this->seller_id,
                'post_type'     =>  'product',
                'tax_query'    =>  array(
                    array(
                        'taxonomy' => 'product_cat',
                        'terms' =>  $category->term_id
                    )
                )
            );
            $my_query = new WP_Query( $args );

            // execute the main query
            $the_main_loop = new WP_Query($args);
            $count = 0;
            if( $my_query->have_posts() ) {
                
              while( $my_query->have_posts() ) {
                $my_query->the_post();
                $blank_post_id = get_the_id();
                //echo $blank_post_id;
                $count++;
                // Do your work...
              } // end while
            } // end if

            $output .= $indent . '<li' . $class_name . '>' . "\n\t" .'<a href="' . $url . '"'. $a_selected_class .'>' . $category->name . ' ('.$count.')'.  $caret . '</a>' . "\n";
            wp_reset_postdata();
            

        } else {
            $caret = $args['has_children'] ? ' <span class="caret-icon"><i class="fa fa-angle-right" aria-hidden="true"></i></span>' : '';
            $class_name = $args['has_children'] ? ' class="has-children"' : '';
            // $output .= $indent . '<li' . $class_name . '><a href="' . $url . '"'.$a_selected_class.'>' . $category->name . ' ('.$category->count.')'.  $caret . '</a>';

            $args = array(
                'author'        =>  $this->seller_id,
                'post_type'     =>  'product',
                'tax_query'    =>  array(
                    array(
                        'taxonomy' => 'product_cat',
                        'terms' =>  $category->term_id
                    )
                )
            );
            $my_query = new WP_Query( $args );

            // execute the main query
            $the_main_loop = new WP_Query($args);
            $count = 0;
            if( $my_query->have_posts() ) {
                
              while( $my_query->have_posts() ) {
                $my_query->the_post();
                $blank_post_id = get_the_id();
                //echo $blank_post_id;
                $count++;
                // Do your work...
              } // end while
            } // end if

            $output .= $indent . '<li' . $class_name . '>' . "\n\t" .'<a href="' . $url . '"'. $a_selected_class .'>' . $category->name . ' ('.$count.')'.  $caret . '</a>' . "\n";
            wp_reset_postdata();
        }
    }
}
