<?php

/* Called by header.php for initializing all the options for use by the JS scripts */

if (!function_exists('mo_setup_theme_options_for_scripts')) {
    function mo_setup_theme_options_for_scripts() {

        echo '<script type="text/javascript">';

        echo 'var mo_options = mo_options || {};';

        mo_setup_general_theme_options();

        mo_setup_slider_options();

        mo_setup_animation_options();

        echo '</script>';
    }
}

if (!function_exists('mo_setup_general_theme_options')) {
    function mo_setup_general_theme_options() {

        $ajax_portfolio = mo_get_theme_option('mo_ajax_portfolio');
        if ($ajax_portfolio)
            echo 'mo_options.ajax_portfolio = true;';
        else
            echo 'mo_options.ajax_portfolio = false;';

        $disable_back_to_top = mo_get_theme_option('mo_disable_back_to_top');
        if ($disable_back_to_top)
            echo 'mo_options.disable_back_to_top = true;';
        else
            echo 'mo_options.disable_back_to_top = false;';

        $display_sticky_menu = mo_get_theme_option('mo_disable_sticky_menu') ? false : true;
        if ($display_sticky_menu)
            echo 'mo_options.sticky_menu = true;';
        else
            echo 'mo_options.sticky_menu = false;';

        $theme_skin = mo_get_theme_skin();
        echo 'mo_options.theme_skin = "' . $theme_skin . '";';

        $theme_directory_url = get_template_directory_uri();
        echo 'mo_options.theme_directory_url = "' . $theme_directory_url . '";';


    }
}

if (!function_exists('mo_setup_slider_options')) {
    function mo_setup_slider_options() {

        $slider_type = get_post_meta(get_the_ID(), 'mo_slider_choice', true);
        if (empty($slider_type) || $slider_type == 'None') {
            echo 'mo_options.slider_chosen="None";';
            return;
        }

        echo 'mo_options.slider_chosen="' . $slider_type . '";'; // output slider option chosen by the user for later use

        if ($slider_type == 'Nivo')
            mo_setup_nivo_slider_options();
        elseif ($slider_type == 'FlexSlider')
            mo_setup_flex_slider_options();

    }
}

if (!function_exists('mo_setup_flex_slider_options')) {

    function mo_setup_flex_slider_options() {

        global $mo_theme;

        $flex_slider_effect = $mo_theme->get_theme_option('mo_flex_slider_effect', 'fade');
        $flex_slider_animation_speed = $mo_theme->get_theme_option('mo_flex_slider_animation_speed', 600);
        $flex_slider_pause_time = $mo_theme->get_theme_option('mo_flex_slider_pause_time', 4000);
        $flex_slider_pause_on_hover = $mo_theme->get_theme_option('mo_flex_slider_disable_pause_on_hover') ? 'false' : 'true';
        $flex_slider_display_random_slide = $mo_theme->get_theme_option('mo_flex_slider_display_random_slide') ? 'true' : 'false';

        echo 'mo_options.flex_slider_effect="' . $flex_slider_effect . '";';
        echo 'mo_options.flex_slider_animation_speed=' . intval($flex_slider_animation_speed) . ';';
        echo 'mo_options.flex_slider_pause_time=' . intval($flex_slider_pause_time) . ';';
        echo 'mo_options.flex_slider_pause_on_hover="' . $flex_slider_pause_on_hover . '";';
        echo 'mo_options.flex_slider_display_random_slide="' . $flex_slider_display_random_slide . '";';
    }
}

if (!function_exists('mo_setup_nivo_slider_options')) {

    function mo_setup_nivo_slider_options() {

        global $mo_theme;

        $nivo_effect = $mo_theme->get_theme_option('mo_nivo_effect', array('random'));
        $nivo_effect = implode(',', $nivo_effect);
        $nivo_slices = $mo_theme->get_theme_option('mo_nivo_slices', 15);
        $nivo_animation_speed = $mo_theme->get_theme_option('mo_nivo_animation_speed', 500);
        $nivo_pause_time = $mo_theme->get_theme_option('mo_nivo_pause_time', 3000);
        $nivo_dir_navigation = $mo_theme->get_theme_option('mo_nivo_hide_dir_navigation') ? 'false' : 'true';
        $nivo_controls = $mo_theme->get_theme_option('mo_nivo_hide_controls') ? 'false' : 'true'; // 1,2,3... navigation controls
        $nivo_pause_on_hover = $mo_theme->get_theme_option('mo_nivo_disable_pause_on_hover') ? 'false' : 'true';
        $nivo_start_random_slide = $mo_theme->get_theme_option('mo_nivo_start_random_slider') ? 'true' : 'false';

        echo 'mo_options.nivo_effect="' . $nivo_effect . '";';
        echo 'mo_options.nivo_slices=' . intval($nivo_slices) . ';';
        echo 'mo_options.nivo_animation_speed=' . intval($nivo_animation_speed) . ';';
        echo 'mo_options.nivo_pause_time=' . intval($nivo_pause_time) . ';';
        echo 'mo_options.nivo_dir_navigation="' . $nivo_dir_navigation . '";';
        echo 'mo_options.nivo_controls="' . $nivo_controls . '";';
        echo 'mo_options.nivo_pause_on_hover="' . $nivo_pause_on_hover . '";';
        echo 'mo_options.nivo_start_random_slide="' . $nivo_start_random_slide . '";';
    }
}

if (!function_exists('mo_setup_animation_options')) {
    function mo_setup_animation_options() {

        global $mo_theme;

        if (mo_browser_supports_css3_animations()) {
            $disable_smooth_page_load = $mo_theme->get_theme_option('mo_disable_smooth_page_load') ? 'true' : 'false';
            $disable_animations_on_page = $mo_theme->get_theme_option('mo_disable_animations_on_page') ? 'true' : 'false';
            $disable_smooth_scroll = $mo_theme->get_theme_option('mo_disable_smooth_scroll') ? 'true' : 'false';
        }
        else {
            $disable_smooth_page_load = 'true';
            $disable_animations_on_page = 'true';
            $disable_smooth_scroll = 'true';
        }

        echo 'mo_options.disable_smooth_page_load=' . $disable_smooth_page_load . ';';
        echo 'mo_options.disable_animations_on_page=' . $disable_animations_on_page . ';';
        echo 'mo_options.disable_smooth_scroll=' . $disable_smooth_scroll . ';';
    }

}
?>