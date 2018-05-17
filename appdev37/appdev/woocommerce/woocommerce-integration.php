<?php


function mo_woocommerce_init() {


    mo_take_control_woocommerce_styles();


    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

    add_action('woocommerce_before_main_content', 'mo_remove_theme_breadcrumb', 9);
    add_action('woocommerce_before_main_content', 'mo_theme_wrapper_start', 10);
    add_action('woocommerce_after_main_content', 'mo_theme_wrapper_end', 10);

    //Move the cross sells below the shipping calculation tables
    remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
    add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display', 10);

    // Change columns in related products output to 4 and move below the product summary
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
    add_action('woocommerce_after_single_product_summary', 'mo_woocommerce_upsell_display', 15);
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
    add_action('woocommerce_after_single_product', 'mo_woocommerce_related_products', 20);

    add_filter('mo_sidebar_names', 'mo_init_woocommerce_sidebar', 10, 1);

    add_filter('mo_sidebar_descriptions', 'mo_init_woocommerce_sidebar_description', 10, 1);

    add_filter('mo_sidebar_id_suffix', 'mo_check_for_woocommerce_sidebar', 10, 1);

    add_filter('mo_theme_layout', 'mo_woocommerce_layout', 10, 1);

    add_filter('woocommerce_show_page_title', 'mo_woocommerce_show_page_title');

    add_filter('mo_show_page_title', 'mo_show_woocommerce_title');

    // Increase cross sells to 3 from 2
    add_filter('woocommerce_cross_sells_total', 'mo_woocommerce_cross_sell_number');
    add_filter('woocommerce_cross_sells_columns', 'mo_woocommerce_cross_sell_number');

    add_filter('post_class', 'mo_add_post_class');

    // Ensure cart contents update when products are added to the cart via AJAX
    add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');

    add_filter('body_class', 'mo_woocommerce_class');


}

if (!function_exists('mo_woocommerce_class')) {
    function mo_woocommerce_class($classes) {
        if (mo_is_woocommerce_activated()) {
            $classes[] = 'woocommerce-site';
        }
        return $classes;
    }
}

if (!function_exists('woocommerce_header_add_to_cart_fragment')) {
    function woocommerce_header_add_to_cart_fragment($fragments) {

        ob_start();
        mo_display_cart_in_header();
        $fragments['a.cart-contents'] = ob_get_clean();

        return $fragments;
    }
}

if (!function_exists('mo_display_cart_in_header')) {
    function mo_display_cart_in_header() {
        global $woocommerce;
        $output = '<a class="cart-contents" href="' . esc_url($woocommerce->cart->get_cart_url()) . '"';
        $output .= ' title="' . __('View your shopping cart', 'mo_theme') . '">';
        $output .= '<i class="icon-cart-2"></i>';
        $output .= '<span class="cart-count">' . sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'mo_theme'), $woocommerce->cart->cart_contents_count) . '</span>';
        $output .= '<span class="cart-amount">' . $woocommerce->cart->get_cart_total() . '</span>';
        $output .= '</a>';

        echo $output;

    }
}

if (!function_exists('mo_woocommerce_pagination')) {
    function mo_woocommerce_pagination() {
        get_template_part('loop-nav'); // Loads the loop-nav.php template.
    }
}
if (!function_exists('mo_add_post_class')) {
    function mo_add_post_class($classes) {
        if (mo_is_woocommerce_activated() && is_woocommerce()) {
            if (is_archive() && is_main_query())
                $classes[] = "";
        }
        return $classes;
    }
}

if (!function_exists('mo_woocommerce_layout')) {
    function mo_woocommerce_layout($layout) {
        if (mo_is_woocommerce_activated() && is_woocommerce()) {
            $layout_manager = mo_get_layout_manager();
            // Make shop pages one column
            if (is_shop() && !is_product_taxonomy()) {
                return $layout_manager->theme_layout_one_column();
            }
        }
        return $layout;
    }
}

if (!function_exists('mo_woocommerce_cross_sell_number')) {
    function mo_woocommerce_cross_sell_number() {
        return 3;
    }
}

if (!function_exists('mo_woocommerce_show_page_title')) {
    function mo_woocommerce_show_page_title() {
        return false;
    }
}


if (!function_exists('mo_show_woocommerce_title')) {
    function mo_show_woocommerce_title() {

        if (mo_is_woocommerce_activated() && is_woocommerce()) {
            echo '<h1 class="page-title">';
            woocommerce_page_title();
            echo '</h1>';
            return true;
        }

        return false;
    }
}

if (!function_exists('mo_check_for_woocommerce_sidebar')) {
    function mo_check_for_woocommerce_sidebar($suffix) {
        //If woocommerce template
        if (mo_is_woocommerce_activated()) {
            if (is_singular('product')) {
                $suffix = 'product';
            }
            elseif (is_woocommerce() || is_checkout() || is_cart() || is_order_received_page()) {
                $suffix = 'shop';
            }
        }

        return $suffix;
    }
}

if (!function_exists('mo_init_woocommerce_sidebar')) {
    function mo_init_woocommerce_sidebar($sidebar_names) {

        $sidebar_names['primary-shop'] = __('Primary WooCommerce Shop Sidebar', 'mo_theme');

        $sidebar_names['primary-product'] = __('Primary WooCommerce Product Sidebar', 'mo_theme');

        return $sidebar_names;
    }
}

if (!function_exists('mo_init_woocommerce_sidebar_description')) {
    function mo_init_woocommerce_sidebar_description($sidebar_descriptions) {

        $sidebar_descriptions['primary-shop'] = __('Primary Sidebar displayed for WooCommerce templates', 'mo_theme');

        $sidebar_descriptions['primary-product'] = __('Primary Sidebar displayed for WooCommerce Single Product', 'mo_theme');

        return $sidebar_descriptions;
    }
}


if (!function_exists('mo_is_woocommerce_activated')) {
    function mo_is_woocommerce_activated() {
        if (class_exists('woocommerce')) {
            return true;
        }
        else {
            return false;
        }
    }
}


if (!function_exists('mo_take_control_woocommerce_styles')) {
    function mo_take_control_woocommerce_styles() {

        //Disable all woocommerce stylesheets
        add_filter('woocommerce_enqueue_styles', '__return_false');

        // Prioritize this - loads this stylesheet prior to theme stylesheet so that theme stylesheets can override it
        add_action('wp_enqueue_scripts', 'mo_woocommerce_enqueue_styles', 9);

        //Do this after woocommerce queues its stylesheets
        add_action('wp_enqueue_scripts', 'mo_woocommerce_dequeue_styles', 99);
    }
}

if (!function_exists('mo_woocommerce_enqueue_styles')) {
    function mo_woocommerce_enqueue_styles() {

        if (mo_is_woocommerce_activated() && is_checkout()) {
            $chosen_en = get_option('woocommerce_enable_chosen') == 'yes' ? true : false;
            if ($chosen_en)
                wp_enqueue_style('woocommerce-chosen', plugins_url() . '/woocommerce/assets/css/chosen.css');
        }

        // Load the custom woocommerce styles after the theme stylesheets
        if (mo_is_woocommerce_activated())
            wp_enqueue_style('woocommerce-custom', get_template_directory_uri() . '/woocommerce/css/woocommerce-mod.css', array('style-responsive'));
    }
}

if (!function_exists('mo_woocommerce_dequeue_styles')) {
    function mo_woocommerce_dequeue_styles() {
        wp_dequeue_style('woocommerce_chosen_styles');
    }
}
if (!function_exists('mo_remove_theme_breadcrumb')) {
    function mo_remove_theme_breadcrumb() {
        /* Do not display theme native breadcrumbs for woocommerce */
        $prefix = mo_get_prefix();
        remove_action("{$prefix}_start_content", 'mo_display_breadcrumbs', 15); // same priority 15 for which it was set initially
    }
}

if (!function_exists('mo_theme_wrapper_start')) {
    function mo_theme_wrapper_start() {
        mo_exec_action('before_content');
        echo '<div id="content" class="' . mo_get_content_class() . '">';
        mo_exec_action('start_content');

    }
}

if (!function_exists('mo_theme_wrapper_end')) {
    function mo_theme_wrapper_end() {
        mo_exec_action('end_content');
        echo '</div><!-- #content -->';
        mo_exec_action('after_content');
    }
}

if (!function_exists('mo_woocommerce_upsell_display')) {
    function mo_woocommerce_upsell_display() {
        woocommerce_upsell_display(3, 3);
    }
}

if (!function_exists('mo_woocommerce_related_products')) {
    function mo_woocommerce_related_products() {
        // 3 products, 3 columns - good layout for responsive
        $args = array(
            'posts_per_page' => 3,
            'columns' => 3,
            'orderby' => 'rand'
        );
        woocommerce_related_products($args);
    }
}


?>