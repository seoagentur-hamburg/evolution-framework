<?php
/**
* The Evolution Framework
*
* WARNING: This file is part of the core Evolution Framework. DO NOT edit this file under any circumstances.
* Please do all modifications in the form of a child theme.
*
* This file handles the WooCommerce support
*
* @package Evolution\WooCommerce\
* @author  Andreas Hecht
* @license GPL-2.0+
* @link    https://andreas-hecht.com/wordpress-themes/evolution
*/

/**
* Theme Support für WooCommerce
*/
add_theme_support( 'woocommerce' );  

/**
 * Add Theme Support for WooCommerce Gallery Lightbox
 */ 
add_theme_support( 'wc-product-gallery-lightbox' );

//remove function attached to woocommerce_before_main_content hook
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );

//remove function attached to woocommerce_after_main_content hook
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );





if ( ! function_exists( 'evolution_wrapper_start' ) ) :
/**
 * Adding theme's starter container for WooCommerce support
 */
function evolution_wrapper_start() {
    
echo '<div id="primary" class="content-area"><main id="main" class="site-main" role="main">';
    
}
add_action( 'woocommerce_before_main_content', 'evolution_wrapper_start', 10 );
endif;





if ( ! function_exists( 'evolution_wrapper_end' ) ) :
/**
 * Adding theme's ending container for WooCommerce support
 */
function evolution_wrapper_end() {
    
    echo '</div></main>';
}
add_action( 'woocommerce_after_main_content', 'evolution_wrapper_end', 10 );
endif;





if ( ! function_exists( 'evolution_replace_breadcrumbs' ) ) :
/**
 * Replace WooCommerce Breadcrumbs with Yoast breadcrumbs
 * Breadcrumbs are part of header.php
 */
function evolution_replace_breadcrumbs() {

    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
    

    function evolution_yoast_breadcrumb() {

        if ( function_exists('yoast_breadcrumb') ) { 
            yoast_breadcrumb('<nav class="woocommerce-breadcrumb">','</nav>');
        }
    }
}
add_action( 'init', 'evolution_replace_breadcrumbs' );
endif;





if (!function_exists('evolution_loop_columns') ) :
/**
 * Change number of products per row to 3
 */ 
    function evolution_loop_columns() {
        return 3; // 3 products per row
    }

add_filter('loop_shop_columns', 'evolution_loop_columns');
endif;






if (!function_exists('evolution_related_products_args') ) {
/**
 * WooCommerce Extra Feature
 * --------------------------
 *
 * Change number of related products on product page
 * Set your own value for 'posts_per_page'
 *
 */ 
function evolution_related_products_args( $args ) {

        $args['posts_per_page'] = 4; // 4 related products
        $args['columns'] = 4; // arranged in 4 columns
        return $args;

    }
}
add_filter( 'woocommerce_output_related_products_args', 'evolution_related_products_args' );





if (!function_exists('evolution_loop_shop_per_page') ) {
/**
 * Custom Product Cols
 * @return 12 Products per Page
 */
function evolution_loop_shop_per_page( $cols ) {

        $cols = 12;
        return $cols;

    }
}
add_filter( 'loop_shop_per_page', 'evolution_loop_shop_per_page', 20 );





if (!function_exists('evolution_hide_shipping_when_free_is_available') ) {
    /**
 * Hide shipping rates when free shipping is available.
 * Updated to support WooCommerce 2.6 Shipping Zones.
 *
 * @param array $rates Array of rates found for the package.
 * @return array
 */
function evolution_hide_shipping_when_free_is_available( $rates ) {
    
        $free = array();
        foreach ( $rates as $rate_id => $rate ) {
            if ( 'free_shipping' === $rate->method_id ) {
                $free[ $rate_id ] = $rate;
                break;
            }
        }
    
        return ! empty( $free ) ? $free : $rates;
    }
}
add_filter( 'woocommerce_package_rates', 'evolution_hide_shipping_when_free_is_available', 100 );






if (!function_exists( 'evolution_manage_woocommerce_styles' ) ) :
/**
* Optimize WooCommerce Scripts
* Removes WooCommerce styles and scripts from non WooCommerce pages.
*/  
function evolution_manage_woocommerce_styles() {

    //first check that woo exists to prevent fatal errors
    if ( function_exists( 'is_woocommerce' ) ) {

        //dequeue scripts and styles
        if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {          
            wp_dequeue_style( 'woocommerce-layout' );
            wp_dequeue_style( 'woocommerce-smallscreen' );
            wp_dequeue_style( 'woocommerce-general' );
            wp_dequeue_style( 'evolution-woostyles' );
            wp_dequeue_script( 'wc_price_slider' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-add-to-cart' );
            wp_dequeue_script( 'wc-cart-fragments' );
            wp_dequeue_script( 'wc-checkout' );
            wp_dequeue_script( 'wc-add-to-cart-variation' );
            wp_dequeue_script( 'wc-single-product' );
            wp_dequeue_script( 'wc-cart' );
            wp_dequeue_script( 'wc-chosen' );
            wp_dequeue_script( 'woocommerce' );
            wp_dequeue_script( 'prettyPhoto' );
            wp_dequeue_script( 'prettyPhoto-init' );
            wp_dequeue_script( 'jquery-blockui' );
            wp_dequeue_script( 'jquery-placeholder' );
            wp_dequeue_script( 'fancybox' );
            wp_dequeue_script( 'jqueryui' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'evolution_manage_woocommerce_styles', 99 );
endif;



// Remove the original hook from WooCommerce template
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
/**
 * Get the spezial sidebar for the WooCommerce Templates
 * 
 * @overrides the woocommerce function
 * 
 */
function evolution_woocommerce_sidebar() {
    
    get_sidebar( 'woocommerce' );
    
}
add_action( 'woocommerce_sidebar', 'evolution_woocommerce_sidebar', 10 );





if (!function_exists( 'evolution_remove_woocommerce_generator_tag' ) ) :
/**
 * Removes the WooCommerce generator tag
 */
function evolution_remove_woocommerce_generator_tag() {
    
    remove_action('get_the_generator_html','wc_generator_tag', 10,2);
    
    remove_action('get_the_generator_xhtml','wc_generator_tag', 10,2); 
    
}
add_action( 'get_header', 'evolution_remove_woocommerce_generator_tag' );
endif;





if (!function_exists( 'evolution_phone_not_required' ) ) :
/**
 * Make the phone number a optional entry
 * 
 * @hooked woocommerce_billing_fields()
 * 
 * @return filter
 */
function evolution_phone_not_required( $address_fields ) {

    $address_fields['billing_phone']['required'] = false;

    return $address_fields;
}
add_filter( 'woocommerce_billing_fields', 'evolution_phone_not_required', 10, 1 );
endif;





if (!function_exists( 'evolution_header_add_to_cart_fragment' ) ) :
/**
 * Ensure cart contents update when products are added to the cart via AJAX
 * 
 * @add_filter woocommerce_add_to_cart_fragments
 */
function evolution_header_add_to_cart_fragment( $fragments ) {

    ob_start();
?>
<a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php esc_html__( 'View your shopping cart', 'evolution' ); ?>"><?php echo sprintf ( _n( '%d item', '%d items','evolution', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?> - <?php echo WC()->cart->get_cart_total(); ?></a> 
<?php

    $fragments['a.cart-contents'] = ob_get_clean();

    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'evolution_header_add_to_cart_fragment' );
endif;





if (!function_exists( 'evolution_add_payment_method_to_admin_new_order' ) ) :
/**
 * Add user payment method to admin email
 * 
 * @hooked woocommerce_email_after_order_table()
 */
function evolution_add_payment_method_to_admin_new_order( $order, $is_admin_email ) {

    if ( $is_admin_email ) {

        echo '<p><strong>Payment Method:</strong> ' . $order->payment_method_title . '</p>';

    }
}
add_action( 'woocommerce_email_after_order_table', 'evolution_add_payment_method_to_admin_new_order', 15, 2 );
endif;





if ( !function_exists( 'evolution_hide_shipping_when_free_is_available' ) ) :
/**
 * Hide shipping rates when free shipping is available.
 * Updated to support WooCommerce 2.6 Shipping Zones.
 *
 * @param array $rates Array of rates found for the package.
 * @return array
 */
    function evolution_hide_shipping_when_free_is_available( $rates ) {
        $free = array();
        foreach ( $rates as $rate_id => $rate ) {
            if ( 'free_shipping' === $rate->method_id ) {
                $free[ $rate_id ] = $rate;
                break;
            }
        }
        return ! empty( $free ) ? $free : $rates;
    }
add_filter( 'woocommerce_package_rates', 'evolution_hide_shipping_when_free_is_available', 100 );
endif;




if ( !function_exists( 'evolution_custom_sales_price' ) ) :
/**
 * Show percent savings on sale - Only for WooCommerce version 3.0+
 * 
 * @add filter to products
 * 
 * @return filter
 */
function evolution_custom_sales_price( $price, $regular_price, $sale_price ) {
    
    $percentage = round( ( $regular_price - $sale_price ) / $regular_price * 100 ).'%';
    
    $percentage_txt = __(' Save ', 'evolution' ).$percentage;
    
    $price = '<del>' . ( is_numeric( $regular_price ) ? wc_price( $regular_price ) : $regular_price ) . '</del> <ins>' . ( is_numeric( $sale_price ) ? wc_price( $sale_price ) . $percentage_txt : $sale_price . $percentage_txt ) . '</ins>';
    return $price;
    
}
add_filter( 'woocommerce_format_sale_price', 'evolution_custom_sales_price', 10, 3 );
endif;
