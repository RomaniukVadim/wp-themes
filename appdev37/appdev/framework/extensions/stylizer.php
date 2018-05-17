<?php

if (!function_exists('mo_set_background')) {

/* Handy  function to set the background for any element with various background css attributes specified */
function mo_set_background($background) {
    $output = '';
    if ($background) {
        $color = $background['background-color'];
        $image = $background['background-image'];

        if (!empty ($color)) {
            $output .= 'background-color:' . $color . ';';
            // Allow color to replace existing image unless a new one is specified
            if (empty($image))
                $output .= 'background-image: none;';
        }
        // Do not allow the background attributes to change if no custom image or color is specified.
        // If desperate to change it, best place to change these attributes alone is in Custom CSS.
        if (!empty($color) || !empty($image)) {
            $repeat = $background['background-repeat'];
            $attachment = $background['background-attachment'];
            $position = $background['background-position'];
            $size = $background['background-size'];
            if (!empty ($repeat))
                $output .= 'background-repeat:' . $repeat . ';';
            if (!empty ($attachment))
                $output .= 'background-attachment:' . $attachment . ';';
            if (!empty ($position))
                $output .= 'background-position:' . $position . ';';
            if (!empty ($size))
                $output .= 'background-size:' . $size . ';';
            else
                $output .= 'background-size: cover;'; //assume background is big enough to be stretched across the entire width

        }
        if (!empty ($image)) {
            $output .= 'background-image:url(' . $image . ');';
            // Remove the background color if no background color has been specified. Will help some transparent images to achieve the effect desired
            if (empty($color))
                $output .= 'background-color: none;';
        }
        return $output;
    }
    return $output;
}
}

if (!function_exists('mo_custom_css_for_entry')) {
function mo_custom_css_for_entry() {

    $output = '';

    $background = get_post_meta(get_queried_object_id(), 'mo_entry_title_background', true);
    $height = intval(get_post_meta(get_queried_object_id(), 'mo_entry_title_height', true));
    $background = mo_set_background($background);
    if (!empty($background) || !empty ($height)) {
        $output .= mo_get_entry_title_background($background, $height);
    }

    $background = get_post_meta(get_queried_object_id(), 'mo_custom_heading_background', true);
    $background = mo_set_background($background);
    if (!empty($background)) {
        $output .= "\n" . '#custom-title-area  {';
        $output .= $background;
        //$output .= 'background-size:cover;'; //assume background is big enough to be stretched across the entire width
        $output .= '}';
    }

    return $output;

}
}

if (!function_exists('mo_get_entry_title_background')) {

function mo_get_entry_title_background($background, $height) {
    $output = "\n" . '#title-area  {';

    if (!empty ($background)) {
        $output .= $background;
        //$output .= 'background-size:cover;'; //assume background is big enough to be stretched across the entire width
        $output .= 'border: none;'; // remove the existing skin based border and box-shadow
        $output .= 'box-shadow: none;';
    }

    if (!empty($height) && $height > 120) {
        $outside_padding = ($height - 70) / 2; // Subtract the existing height of single line entry title contents and then derive equal padding on top and bottom
        $output .= 'padding: ' . $outside_padding . 'px 0;';
    }


    $output .= '}';
    return $output;
}
}

if (!function_exists('mo_custom_css')) {
function mo_custom_css() {

    $system_fonts = array('Arial Black', 'Arial', 'Helvetica', 'Courier New', 'Georgia', 'Lucida Sans Unicode', 'Tahoma', 'Geneva', 'Times New Roman', 'Trebuchet MS', 'Verdana');

    $output = '';

    /* -------------------- Font Family Option and Font Import ------------------------------------ */

    $custom_fonts = array();


    $heading_font = mo_get_theme_option('mo_custom_heading_font');
    if (empty($heading_font)) {
        $heading_font = mo_get_theme_option('mo_heading_font', 'Arvo');
        $heading_font = str_replace(" *", "", $heading_font);
        $custom_fonts [] = $heading_font;
    }


    if (!empty($heading_font)) {
        $heading_font_spacing = mo_get_theme_option('mo_heading_font_spacing', '1');
        $output .= "\n" . 'h1,h2,h3,h4,h5,h6 {';
        $output .= 'font-family:"' . $heading_font . '";';
        $output .= 'letter-spacing:' . $heading_font_spacing . 'px;';
        $output .= '}';

    }

    $body_font = mo_get_theme_option('mo_custom_body_font');
    if (empty($body_font)) {
        $body_font = mo_get_theme_option('mo_body_font', 'Open Sans');
        $body_font = str_replace(" *", "", $body_font);
        $custom_fonts [] = $body_font;
    }

    if (!empty($body_font)) {
        $output .= "\n" . 'body{';
        $output .= 'font-family:"' . $body_font . '";';
        $output .= '}';
    }


    $use_text_logo = mo_get_theme_option('mo_use_text_logo') ? true : false;
    if ($use_text_logo) {
        $logo_font = mo_get_theme_option('mo_logo_font', 'Open Sans');

        if (!empty($logo_font)) {
            $logo_font = str_replace(" *", "", $logo_font);
            $custom_fonts [] = $logo_font;
            $output .= "\n" . '#site-logo a {';
            $output .= 'font-family:"' . $logo_font . '";';
            $output .= '}';
        }
    }

    $fonts_to_import = '';
    foreach ($custom_fonts as $font_name) {
        if ($fonts_to_import != '')
            $fonts_to_import .= '|';
        if (!in_array($font_name, $system_fonts))
            $fonts_to_import .= preg_replace('/ /', '+', $font_name); // replace spaces in font names with + for importing Google fonts
    }

    $fonts_import = '';
    if (!empty($fonts_to_import))
        $fonts_import .= '@import url("//fonts.googleapis.com/css?family=' . $fonts_to_import . '");';

    /* -------------------- Font Options ------------------------------------ */

    $heading_font_color = mo_get_theme_option('mo_heading_font_color');
    if (!empty($heading_font_color))
        $output .= "\n" . 'h1,h2,h3,h4,h5,h6 { color:' . $heading_font_color . ';}';

    $skin_font_color = mo_get_theme_option('mo_skin_font_color');
    if ($skin_font_color) {

        /* TODO : Simplify the skin colors */

        $output .= "\n";
        $output .= 'a, a:active, a:visited, .post-snippets .hentry .entry-title a:hover, ';
        $output .= '.sidebar a:hover, .read-more a, .top-of-page a:hover, #title-area a, #before-content-area a:active, #before-content-area a:visited';
        $output .= '{ color:' . $skin_font_color . '}';
        $output .= "\n";

        $output .= '#primary-menu > ul > li:hover, #primary-menu > ul > li.sfHover, #primary-menu > ul > li:hover:before, #primary-menu > ul >li.sfHover:before,';
        $output .= '.rounded-button, .rounded-button:active, .image-info , .pagination span.current, .page-links a:hover, .pagination a:hover, ';
        $output .= '#title-area, div.service-box1:hover, #portfolio-filter a:hover, ';
        $output .= '#slider-testimonials .flex-direction-nav li:hover';
        $output .= '{ background:' . $skin_font_color . '}';
        $output .= "\n";

        $output .= '#title-area , ';
        $output .= '{ border-bottom-color: ' . $skin_font_color . '; }';
        $output .= "\n";

        $output .= '.rounded-button, #title-area ';
        $output .= '{ border-top-color: ' . $skin_font_color . '; }';
        $output .= "\n";
    }

    $h1_font_size = mo_get_theme_option('mo_h1_font_size');
    if (!empty($h1_font_size))
        $output .= "\n" . 'h1 { font-size:' . $h1_font_size . 'px !important;}';

    $h2_font_size = mo_get_theme_option('mo_h2_font_size');
    if (!empty($h2_font_size))
        $output .= "\n" . 'h2 { font-size:' . $h2_font_size . 'px !important;}';

    $post_title_font_size = mo_get_theme_option('mo_post_title_h1_font_size');
    $post_title_font_color = mo_get_theme_option('mo_post_title_h1_font_color');
    if (!empty($post_title_font_size) || !empty($post_title_font_color)) {
        $output .= "\n" . 'h1.post-title {';
        if (!empty($post_title_font_size))
            $output .= 'font-size:' . $post_title_font_size . 'px !important;';
        if (!empty($post_title_font_color))
            $output .= 'color:' . $post_title_font_color . ' !important;';
        $output .= '}';
    }

    $page_title_font_size = mo_get_theme_option('mo_page_title_h1_font_size');
    $page_title_font_color = mo_get_theme_option('mo_page_title_h1_font_color');
    if (!empty($page_title_font_size) || !empty($page_title_font_color)) {
        $output .= "\n" . 'h1.page-title {';
        if (!empty($page_title_font_size))
            $output .= 'font-size:' . $page_title_font_size . 'px !important;';
        if (!empty($page_title_font_color))
            $output .= 'color:' . $page_title_font_color . ' !important;';
        $output .= '}';
    }

    $post_tagline_font_size = mo_get_theme_option('mo_post_tagline_h2_font_size');
    $post_tagline_font_color = mo_get_theme_option('mo_post_tagline_h2_font_color');
    if (!empty($post_tagline_font_size) || !empty($post_tagline_font_color)) {
        $output .= "\n" . '#title-area h2.tagline {';
        if (!empty($post_tagline_font_size))
            $output .= 'font-size:' . $post_tagline_font_size . 'px !important;';
        if (!empty($post_tagline_font_color))
            $output .= 'color:' . $post_tagline_font_color . ' !important;';
        $output .= '}';
    }

    $portfolio_page_font_size = mo_get_theme_option('mo_portfolio_page_h2_font_size');
    $portfolio_page_font_color = mo_get_theme_option('mo_portfolio_page_h2_font_color');
    if (!empty($portfolio_page_font_size) || !empty($portfolio_page_font_color)) {
        $output .= "\n" . '#portfolio-full-width h2, #portfolio-template h2 {';
        if (!empty($portfolio_page_font_size))
            $output .= 'font-size:' . $portfolio_page_font_size . 'px !important;';
        if (!empty($portfolio_page_font_color))
            $output .= 'color:' . $portfolio_page_font_color . ' !important;';
        $output .= '}';
    }

    $archive_pages_font_size = mo_get_theme_option('mo_archive_pages_h2_font_size');
    $archive_pages_font_color = mo_get_theme_option('mo_archive_pages_h2_font_color');
    if (!empty($archive_pages_font_size) || !empty($archive_pages_font_color)) {
        $output .= "\n" . '.archive #content h2 {';
        if (!empty($archive_pages_font_size))
            $output .= 'font-size:' . $archive_pages_font_size . 'px !important;';
        if (!empty($archive_pages_font_color))
            $output .= 'color:' . $archive_pages_font_color . ' !important;';
        $output .= '}';
    }

    $h3_font_size = mo_get_theme_option('mo_h3_font_size');
    if (!empty($h3_font_size))
        $output .= "\n" . 'h3 { font-size:' . $h3_font_size . 'px !important;}';

    $widget_title_font_size = mo_get_theme_option('mo_widget_title_h3_font_size');
    $widget_title_font_color = mo_get_theme_option('mo_widget_title_h3_font_color');
    if (!empty($widget_title_font_size) || !empty($widget_title_font_color)) {
        $output .= "\n" . 'h3.widget-title {';
        if (!empty($widget_title_font_size))
            $output .= 'font-size:' . $widget_title_font_size . 'px !important;';
        if (!empty($widget_title_font_color))
            $output .= 'color:' . $widget_title_font_color . ' !important;';
        $output .= '}';
    }

    $footer_widget_title_font_color = mo_get_theme_option('mo_footer_widget_title_h3_font_color');
    if ($footer_widget_title_font_color)
        $output .= "\n" . '#footer .widget-title { color:' . $footer_widget_title_font_color . ' !important; }';

    $services_area_font_size = mo_get_theme_option('mo_services_area_h3_font_size');
    $services_area_font_color = mo_get_theme_option('mo_services_area_h3_font_color');
    if (!empty($services_area_font_size) || !empty($services_area_font_color)) {
        $output .= "\n" . '#services-area h3 {';
        if (!empty($services_area_font_size))
            $output .= 'font-size:' . $services_area_font_size . 'px !important;';
        if (!empty($services_area_font_color))
            $output .= 'color:' . $services_area_font_color . ' !important;';
        $output .= '}';
    }

    $h4_font_size = mo_get_theme_option('mo_h4_font_size');
    if (!empty($h4_font_size))
        $output .= "\n" . 'h4 { font-size:' . $h4_font_size . 'px !important;}';


    $entry_meta_font_size = mo_get_theme_option('mo_entry_meta_font_size');
    $entry_meta_font_color = mo_get_theme_option('mo_entry_meta_font_color');

    if (!empty($entry_meta_font_color) || !empty($entry_meta_font_size)) {
        $output .= "\n" . '#content .hentry .entry-meta { ';
        if (!empty($entry_meta_font_size))
            $output .= 'font-size:' . $entry_meta_font_size . 'px;';
        if (!empty($entry_meta_font_color))
            $output .= 'color:' . $entry_meta_font_color . ';';
        $output .= '}';
    }

    if (!empty($entry_meta_font_color) || !empty($entry_meta_font_size)) {
        $output .= "\n" . 'ul.post-list .entry-meta, .post-list .byline {';
        if (!empty($entry_meta_font_color))
            $output .= 'color:' . $entry_meta_font_color . ';';
        if (!empty($entry_meta_font_size))
            $output .= 'font-size:' . $entry_meta_font_size . 'px;';
        $output .= '}';
    }

    $entry_meta_font_color = mo_get_theme_option('mo_footer_entry_meta_font_color');
    if (!empty($entry_meta_font_color)) {
        $output .= "\n" . '#footer ul.post-list .entry-meta, #footer .post-list .byline { color:' . $entry_meta_font_color . ';}';
    }

    $footer_text_font_color = mo_get_theme_option('mo_footer_text_font_color');
    if (!empty($footer_text_font_color))
        $output .= "\n" . '#footer { color:' . $footer_text_font_color . ' !important; }';

    $footer_link_font_color = mo_get_theme_option('mo_footer_link_font_color');
    if (!empty($footer_link_font_color))
        $output .= "\n" . '#footer a { color:' . $footer_link_font_color . ' !important; }';

    $footer_link_font_hover_color = mo_get_theme_option('mo_footer_link_hover_font_color');
    if (!empty($footer_link_font_hover_color))
        $output .= "\n" . '#footer a:hover { color:' . $footer_link_font_hover_color . ' !important; }';

    $footer_text_font_color = mo_get_theme_option('mo_footer_text_font_color');
    if (!empty($footer_text_font_color))
        $output .= "\n" . '#footer-bottom { color:' . $footer_text_font_color . ' !important; }';

    $footer_link_font_color = mo_get_theme_option('mo_footer_link_font_color');
    if (!empty($footer_link_font_color))
        $output .= "\n" . '#footer-bottom a { color:' . $footer_link_font_color . ' !important; }';

    $footer_link_font_hover_color = mo_get_theme_option('mo_footer_link_font_hover_color');
    if (!empty($footer_link_font_hover_color))
        $output .= "\n" . '#footer-bottom a:hover { color:' . $footer_link_font_hover_color . ' !important; }';

    $sidebar_text_font_color = mo_get_theme_option('mo_sidebar_text_font_color');
    if (!empty($sidebar_text_font_color))
        $output .= "\n" . '.sidebar { color:' . $sidebar_text_font_color . ' !important; }';

    $sidebar_link_font_color = mo_get_theme_option('mo_sidebar_link_font_color');
    if (!empty($sidebar_link_font_color))
        $output .= "\n" . '.sidebar a, .sidebar a:active, .sidebar a:visited { color:' . $sidebar_link_font_color . ' !important; }';

    $sidebar_link_hover_font_color = mo_get_theme_option('mo_sidebar_link_hover_font_color');
    if (!empty($sidebar_link_hover_font_color))
        $output .= "\n" . '.sidebar a:hover { color:' . $sidebar_link_hover_font_color . ' !important; }';


    //$output .= "\n\n/* ---- Body Font Styles ---- */\n\n";

    /* -------------------- Body Font Styles ------------------------------------ */

    $body_font_size = mo_get_theme_option('mo_body_font_size', 'Default');
    $body_font_color = mo_get_theme_option('mo_body_font_color', '');


    if (!empty($body_font_size) || !empty($body_font_color)) {
        $output .= "\n" . 'body{';
        if ($body_font_size != 'Default')
            $output .= 'font-size:' . $body_font_size . 'px;';

        if (!empty($body_font_color))
            $output .= 'color:' . $body_font_color . ';';
        $output .= '}'; // end body font
    }

    /* -------------------- Header Styling ------------------------------------ */

    $header_height = mo_get_theme_option('mo_header_height');
    if (!empty ($header_height)) {
        $output .= "\n" . '#header .inner .wrap {';
        $output .= 'height:' . $header_height . 'px !important;';
        $output .= '}';
    }

    $social_container_margin_top = mo_get_theme_option('mo_social_container_margin_top');
    $social_container_margin_right = mo_get_theme_option('mo_social_container_margin_right');
    if ($social_container_margin_top || $social_container_margin_right) {
        $output .= "\n" . '.social-container { ';
        if ($social_container_margin_top)
            $output .= 'top:' . $social_container_margin_top . 'px;';
        if ($social_container_margin_right)
            $output .= 'right:' . $social_container_margin_right . 'px;';
        $output .= '}';
    }

    $tagline_height = mo_get_theme_option('mo_tagline_height');
    $background = mo_get_theme_option('mo_tagline_background');
    $background = mo_set_background($background);
    if (!empty($background) || !empty ($tagline_height)) {

        $output .= mo_get_entry_title_background($background, $tagline_height);

    }

    /* -------------------- Primary Menu Options ------------------------------------ */


    $primary_menu_font_size = mo_get_theme_option('mo_primary_menu_font_size');
    $primary_menu_font_color = mo_get_theme_option('mo_primary_menu_font_color');
    if (!empty($primary_menu_font_size) || !empty($primary_menu_font_color)) {
        $output .= "\n" . '#primary-menu > ul.menu > li > a {';
        if (!empty($primary_menu_font_size))
            $output .= 'font-size:' . $primary_menu_font_size . 'px !important;';
        if (!empty($primary_menu_font_color))
            $output .= 'color:' . $primary_menu_font_color . ' !important;';
        $output .= '}';
    }

    $primary_menu_hover_font_color = mo_get_theme_option('mo_primary_menu_hover_font_color');
    if (!empty($primary_menu_hover_font_color))
        $output .= "\n" . '#primary-menu ul.menu > li.sfHover > a, #primary-menu > ul.menu > li > a:hover { color:' . $primary_menu_hover_font_color . ' !important; }';

    $primary_menu_hover_background_color = mo_get_theme_option('mo_primary_menu_hover_background_color');
    if (!empty($primary_menu_hover_background_color))
        $output .= "\n" . '#primary-menu > ul.menu > li:hover, #primary-menu > ul.menu > li.sfHover { background:' . $primary_menu_hover_background_color . ' !important; }';

    $menu_background_color = mo_get_theme_option('mo_dropdown_menu_background_color');
    if (!empty($menu_background_color)) {
        $output .= "\n" . '.dropdown-menu-wrap ul.sub-menu { background-color: ' . $menu_background_color . ';}';
        $output .= "\n" . '.dropdown-menu-wrap ul.sub-menu li { border: none;}';
    }

    $dropdown_menu_font_size = mo_get_theme_option('mo_dropdown_menu_font_size');
    $dropdown_menu_font_color = mo_get_theme_option('mo_dropdown_menu_font_color');

    if (!empty($dropdown_menu_font_color) || !empty($dropdown_menu_font_size)) {
        $output .= "\n" . '.dropdown-menu-wrap ul.sub-menu > li a { ';
        if (!empty($dropdown_menu_font_color))
            $output .= 'color:' . $dropdown_menu_font_color . ' !important;';
        if (!empty($dropdown_menu_font_size))
            $output .= 'font-size:' . $dropdown_menu_font_size . 'px !important;';
        $output .= '}';
    }

    $menu_background_color = mo_get_theme_option('mo_dropdown_menu_hover_background_color');
    if (!empty($menu_background_color)) {
        $output .= "\n" . '.dropdown-menu-wrap ul.sub-menu li:hover, .dropdown-menu-wrap ul.sub-menu li.sfHover { background-color: ' . $menu_background_color . '}';
    }

    $dropdown_menu_hover_font_color = mo_get_theme_option('mo_dropdown_menu_hover_font_color');
    if (!empty($dropdown_menu_hover_font_color))
        $output .= "\n" . '.dropdown-menu-wrap ul.sub-menu li:hover a, .dropdown-menu-wrap ul.sub-menu li.sfHover a { color:' . $dropdown_menu_hover_font_color . ' !important;}';

    /* ------------------- SITE LOGO OPTIONS ------------------------------- */

    $logo_margin_top = mo_get_theme_option('mo_logo_margin_top');
    if ($logo_margin_top)
        $output .= "\n" . '#site-logo { top:' . $logo_margin_top . 'px; }';

    $use_text_logo = mo_get_theme_option('mo_use_text_logo') ? true : false;
    $logo_text_color = mo_get_theme_option('mo_logo_text_color');
    if ($use_text_logo && $logo_text_color) {
        $output .= "\n" . '#site-logo a {';
        if ($logo_text_color)
            $output .= 'color:' . $logo_text_color . ';';
        $output .= '}';
    }

    /* -------------------- Slider Area Home Page Options  ------------------------------------ */

    $nivo_slider_height = mo_get_theme_option('mo_nivo_slider_height');
    if ($nivo_slider_height) {

        $output .= "\n" . '#nivo-slider-wrap { background: none; /* get rid of that existing shadow */ }';
        $output .= "\n" . '#nivo-slider {';
        $output .= 'box-shadow: 0px 0px 15px -3px black; /* Replace the existing shadow */';
        $output .= '}';

    }

    $nivo_disable_caption = mo_get_theme_option('mo_disable_nivo_slider_caption');
    if ($nivo_disable_caption) {
        $output .= "\n" . '.nivo-caption { display: none; }';
    }

    /* -------------------- Sidebar Options ------------------------------------ */


    $right_sidebar_background = mo_get_theme_option('mo_sidebar_background');
    $right_sidebar_background = mo_set_background($right_sidebar_background);
    if (!empty($right_sidebar_background)) {
        $output .= "\n" . '.sidebar-right-nav {';
        $output .= $right_sidebar_background;
        $output .= '}';
    }

    $left_sidebar_background = mo_get_theme_option('mo_left_sidebar_background');
    $left_sidebar_background = mo_set_background($left_sidebar_background);
    if (!empty($left_sidebar_background)) {
        $output .= "\n" . '.sidebar-left-nav {';
        $output .= $left_sidebar_background;
        $output .= '}';
    }

    /* -------------------- Background Options and Boxed Layout ------------------------------------ */


    $background = mo_get_theme_option('mo_theme_background');
    $background = mo_set_background($background);
    if (!empty($background))
        $output .= "\n" . '#container, #header {' . $background . '}';

    $background = mo_get_theme_option('mo_header_background');
    $background = mo_set_background($background);
    if (!empty($background)) {
        $output .= "\n" . '#header {';
        if (!empty($background))
            $output .= $background;
        $output .= '}';
    }

    $background = mo_get_theme_option('mo_content_area_background');
    $background = mo_set_background($background);
    if (!empty($background))
        $output .= "\n" . '#main {' . $background . '}';

    $background = mo_get_theme_option('mo_footer_background');
    $background = mo_set_background($background);
    if (!empty($background)) {
        $output .= "\n" . '#footer {';
        $output .= $background;
        $output .= '}';
    }

    $background = mo_get_theme_option('mo_footer_bottom_background');
    $background = mo_set_background($background);
    if (!empty($background)) {
        $output .= "\n" . '#footer-bottom {';
        $output .= $background;
        $output .= '}';
    }

    $boxed_layout = (mo_get_theme_option('mo_theme_layout') == 'Boxed') ? true : false;
    if ($boxed_layout) {
        $output .= "\n" . 'body.boxed {';
        $background = mo_get_theme_option('mo_boxed_layout_background');
        $output .= mo_set_background($background);
        $background_stretched = mo_get_theme_option('mo_stretch_boxed_background') ? true : false;
        if ($background_stretched)
            $output .= 'background-size:cover;';
        $output .= '}';
    }

    if (mo_get_theme_option('mo_add_boxed_margins')) {
        $output .= "\n" . '.boxed #container { ';
        $output .= 'margin: 40px auto;';
        $output .= '}';
    }

    $remove_comments_background = (mo_get_theme_option('mo_remove_comments_background'));
    if ($remove_comments_background) {
        // Make the comment area transparent
        $output .= "\n" . '.comment-text-wrap, .comment-list .avatar-wrap { background: none !important; }';
    }

    $comment_background = mo_get_theme_option('mo_comment_background');
    if (!empty($comment_background)) {
        $output .= "\n" . '.comment-text-wrap, .comment-list li li li .comment-text-wrap { background:' . $comment_background . '; }';
        // Get rid of fancy arrows
        $output .= "\n" . '.comment-list .avatar-wrap, .comment-list li li li .avatar-wrap  { background: none; }';
    }

    $alternate_comment_background = mo_get_theme_option('mo_alternate_comment_background');
    if (!empty($alternate_comment_background)) {
        $output .= "\n" . '.comment-list li li .comment-text-wrap, .comment-list li li li li .comment-text-wrap { background-color:' . $alternate_comment_background . ';}';
        // Get rid of fancy arrows
        $output .= "\n" . '.comment-list li li .avatar-wrap, .comment-list li li li li .avatar-wrap { background: none; }';
    }

    /* -------------- Animations for in page elements -------------------- */

    if (mo_browser_supports_css3_animations()) {
        $disable_smooth_page_load = mo_get_theme_option('mo_disable_smooth_page_load');

        if (empty($disable_smooth_page_load)) {
            $output .= "\n" . '#title-area .inner, #custom-title-area .inner, ' . "\n";
            $output .= "\n" . '#content, .sidebar-right-nav, .sidebar-left-nav ' . "\n";
            $output .= '{ opacity: 0; }';
        }

        $disable_animations_on_page = mo_get_theme_option('mo_disable_animations_on_page');
        if (empty($disable_animations_on_page)) {
            $output .= "\n" . '#pricing-action .pointing-arrow img { opacity: 0 }';
        }
    }


    /* -------------------- Custom CSS defined by user ------------------------------------ */

    $custom_css = mo_get_theme_option('mo_custom_css');

    // Output the custom css last
    if ($custom_css) {
        $output .= "\n" . $custom_css;
    }

    $output = $fonts_import . "\n" . $output;

    $output .= mo_custom_css_for_entry();

    return $output;

}
}
