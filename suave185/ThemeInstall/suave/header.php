<?php
/**
 * The theme header
 *
 * */
global $cg_options;
$protocol = (!empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https:" : "http:";

$cg_responsive_status = '';

if ( isset( $cg_options['cg_responsive'] ) ) {
    $cg_responsive_status = $cg_options['cg_responsive'];
}

$cg_logo = '';

$cg_favicon = '';

if ( isset( $cg_options['cg_favicon']['url'] ) ) {
    $cg_options['cg_favicon']['url'] = $protocol . str_replace( array( 'http:', 'https:' ), '', $cg_options['cg_favicon']['url'] );
    $cg_favicon = $cg_options['cg_favicon']['url'];
}

$cg_retina_favicon = '';

if ( isset( $cg_options['cg_retina_favicon']['url'] ) ) {
    $cg_options['cg_retina_favicon']['url'] = $protocol . str_replace( array( 'http:', 'https:' ), '', $cg_options['cg_retina_favicon']['url'] );
    $cg_retina_favicon = $cg_options['cg_retina_favicon']['url'];
}

$cg_topbar_display = '';

if ( isset( $cg_options['cg_topbar_display'] ) ) {
    $cg_topbar_display = $cg_options['cg_topbar_display'];
}

$cg_topbar_message = '';

if ( isset( $cg_options['cg_topbar_message'] ) ) {
    $cg_topbar_message = $cg_options['cg_topbar_message'];
}

$cg_display_cart = '';

if ( isset( $cg_options['cg_show_cart'] ) ) {
    $cg_display_cart = $cg_options['cg_show_cart'];
}

$cg_catalog = '';

if ( isset( $cg_options['cg_catalog_mode'] ) ) {
    $cg_catalog = $cg_options['cg_catalog_mode'];
}

$cg_primary_menu_layout = '';

if ( isset( $cg_options['cg_primary_menu_layout'] ) ) {
    $cg_primary_menu_layout = $cg_options['cg_primary_menu_layout'];
}

$cg_sticky_menu = '';

if ( isset( $cg_options['cg_sticky_menu'] ) ) {
    $cg_sticky_menu = $cg_options['cg_sticky_menu'];
}

if ( !empty( $_SESSION['cg_header_top'] ) ) {
    $cg_topbar_display = $_SESSION['cg_header_top'];
}

$shop_announcements = '';

if ( isset( $cg_options['cg_shop_announcements'] ) ) {
    $shop_announcements = $cg_options['cg_shop_announcements'];
}

$logo_position = '';

if ( isset( $cg_options['cg_logo_position'] ) ) {
    $logo_position = $cg_options['cg_logo_position'];
}
?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="no-js ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <?php
        if ( $cg_responsive_status == 'enabled' ) {
            ?>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php } ?>
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">        
        <link rel="shortcut icon" href="<?php
        if ( $cg_favicon ) {
            echo $cg_favicon;
        } else {
            ?><?php echo get_template_directory_uri(); ?>/favicon.png<?php } ?>"/>

        <link rel="apple-touch-icon-precomposed" href="<?php
        if ( $cg_retina_favicon ) {
            echo $cg_retina_favicon;
        } else {
            ?><?php echo get_template_directory_uri(); ?>/apple-touch-icon-precomposed.png<?php } ?>"/>
       <!--[if lte IE 9]><script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.js"></script><![endif]-->
        <?php wp_head(); ?>
    </head>
    <body id="skrollr-body" <?php body_class(); ?>>

        <div class="cg-shopping-toolbar">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-6 visible-lg">
                        <?php if ( is_active_sidebar( 'top-bar-left' ) ) : ?>
                            <?php dynamic_sidebar( 'top-bar-left' ); ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6 top-bar-right wpml">
                        <?php if ( is_active_sidebar( 'top-bar-right' ) ) : ?>
                            <?php dynamic_sidebar( 'top-bar-right' ); ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6 visible-md visible-sm visible-xs mobile-search">
                        <?php if ( is_active_sidebar( 'mobile-search' ) ) : ?>
                            <?php dynamic_sidebar( 'mobile-search' ); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="wrapper">

            <?php if ( $logo_position == 'left' ) { ?>
                <?php get_template_part( 'partials/header', 'left' ); ?>

            <?php } else if ( $logo_position == 'center' ) { ?>
                <?php get_template_part( 'partials/header', 'center' ); ?>

            <?php } else if ( $logo_position == 'center-logo-center-menu' ) { ?>
                <?php get_template_part( 'partials/header', 'centerlogocentermenu' ); ?>
                
            <?php } else { ?>
                <?php get_template_part( 'partials/header', 'centerlogocentermenu' ); ?>
            <?php } ?>


            <?php
            if ( $cg_sticky_menu == 'yes' ) {
                ?>
                <!--FIXED -->
                <div class="cg-header-fixed-wrapper">
                    <div class="cg-header-fixed">
                        <div class="container">
                            <div class="cg-wp-menu-wrapper">
                                <div class="cg-primary-menu">
                                    <div class="row">
                                        <div class="container">
                                            <div class="cg-wp-menu-wrapper">
                                                <?php if ( $cg_display_cart !== 'no' ) { ?>
                                                    <?php if ( $cg_catalog == 'disabled' ) { ?>
                                                        <div class="cart-wrap">
                                                            <?php if ( is_wc_active() ) {
                                                                ?>
                                                                <?php echo cg_woocommerce_cart_dropdown(); ?>
                                                            <?php }
                                                            ?>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>

                                                <?php
                                                if ( isset( $cg_options['site_logo']['url'] ) ) {
                                                    $cg_options['site_logo']['url'] = $protocol . str_replace( array( 'http:', 'https:' ), '', $cg_options['site_logo']['url'] );
                                                    $cg_logo = $cg_options['site_logo']['url'];
                                                }

                                                if ( $cg_logo ) {
                                                    $cg_logo_width = $cg_options['site_logo']['width'];
                                                    $cg_logo_max_width = $cg_logo_width / 2;
                                                    ?>

                                                    <div class="logo image">
                                                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" style="max-width: <?php echo esc_attr( $cg_logo_max_width ); ?>px;">
                                                            <span class="helper"></span><img src="<?php echo esc_url( $cg_logo ); ?>" alt="<?php bloginfo( 'name' ); ?>"/></a>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="logo text-logo">
                                                        <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                                                    </div>
                                                <?php } ?>
                                                <?php if ( has_nav_menu( 'primary' ) ) { ?>
                                                    <?php
                                                    wp_nav_menu( array(
                                                        'theme_location' => 'primary',
                                                        'before' => '',
                                                        'after' => '',
                                                        'link_before' => '',
                                                        'link_after' => '',
                                                        'depth' => 4,
                                                        'fallback_cb' => false,
                                                        'walker' => new primary_cg_menu() )
                                                    );
                                                    ?>
                                                <?php } else { ?>
                                                    <p class="setup-message">You can set your main menu in <strong>Appearance &gt; Menus</strong></p>
                                                <?php } ?>
                                            </div><!--/cg-wp-menu-wrapper -->
                                        </div><!--/container -->
                                    </div><!--/row -->
                                </div><!--/cg-primary-menu -->
                            </div><!--/cg-wp-menu-wrapper -->
                        </div><!--/container -->
                    </div><!--/cg-header-fixed -->
                </div><!--/cg-header-fixed-wrapper. -->
            <?php }
            ?>
            <?php
            if ( $cg_responsive_status !== 'disabled' ) {
                ?>
                <div id="mobile-menu">
                    <a id="skip" href="#cg-page-wrap" class="hidden" title="<?php esc_attr_e( 'Skip to content', 'commercegurus' ); ?>"><?php _e( 'Skip to content', 'commercegurus' ); ?></a> 
                    <?php
                    if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'mobile' ) ) {
                        wp_nav_menu( array( 'theme_location' => 'mobile', 'container' => 'ul', 'menu_id' => 'mobile-cg-mobile-menu', 'menu_class' => 'mobile-menu-wrap', 'walker' => new mobile_cg_menu() ) );
                    } elseif ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary' ) ) {
                        wp_nav_menu( array( 'theme_location' => 'primary', 'container' => 'ul', 'menu_id' => 'mobile-cg-primary-menu', 'menu_class' => 'mobile-menu-wrap', 'walker' => new mobile_cg_menu() ) );
                    }
                    ?>
                </div><!--/mobile-menu -->
            <?php } ?>

            <div id="cg-page-wrap" class="hfeed site">
                <?php do_action( 'before' ); ?>
                <?php if ( is_wc_active() ) { ?> 
                    <?php if ( function_exists( 'wc_print_notices' ) ) { ?>
                        <?php
                        $cg_wc_notices = WC()->session->get( 'wc_notices', array() );
                        if ( !empty( $cg_wc_notices ) ) {
                            ?>
                            <div class="cg-wc-messages">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <?php wc_print_notices(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                <?php } ?>