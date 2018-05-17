<?php

/**
 * Slider Manager - Single handedly manages the various types of sliders in Livemesh Framework
 *
 * @package Livemesh_Framework
 */

if (!class_exists('MO_Slider_Manager')) {

    class MO_Slider_Manager {

        protected static $instance;

        /**
         * Constructor method for the MO_Slider_Manager class.
         *

         */
        protected function __construct() {
        }

        /**
         * Constructor method for the MO_Slider_Manager class.
         *

         */
        public static function getInstance() {
            if (!isset(self::$instance)) {
                // Check if this is at least PHP 5.3 version
                if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
                    $class = get_called_class();
                    self::$instance = new $class;
                }
                else {
                    $c = __CLASS__;
                    self::$instance = new $c;
                }
            }
            return self::$instance;
        }

        /**
         * Prevent cloning of this singleton
         *

         */
        public function __clone() {
            trigger_error('Clone is not allowed.', E_USER_ERROR);
        }

        /**
         * Init method for the MO_Slider_Manager class.
         * Called during theme setup.
         *

         */
        function initialize() {

        }

        function display_slider_area() {

            $slider_type = get_post_meta(get_the_ID(), 'mo_slider_choice', true);

            if (empty($slider_type) || $slider_type == 'None')
                return;

            echo '<div id="slider-area" class="clearfix">';

            $this->display_slider($slider_type);

            echo '</div> <!-- #slider-area -->';
        }

        function display_slider($slider_type) {

            switch ($slider_type) {
                case 'Nivo':
                    $this->display_nivo_slider();
                    break;
                case 'FlexSlider':
                    $this->display_flex_slider();
                    break;
                case 'Revolution':
                    $this->display_revolution_slider();
                    break;
                default:
                    $this->display_nivo_slider(); // Go ahead and populate Nivo anyway
            }
        }

        function display_revolution_slider() {
            $chosen_slider = mo_get_theme_option('mo_revolution_slider_choice');
            if (!empty($chosen_slider) && $chosen_slider !== 'none') {
                $shortcode = '[rev_slider ' . $chosen_slider . ']';
                echo do_shortcode($shortcode);
            }
            else {
                echo do_shortcode('[segment id="slider-error"]' . __('Revolution Slider Plugin Not Installed/Deactivated or No Revolution Slider entries created. Prepare the Revolution Slider or choose a different slider type in page edit window.', 'mo_theme') . '[/segment]');
            }
        }

        function display_nivo_slider() {

            $post_count = mo_get_theme_option('mo_nivo_slider_post_count', 8);

            $query = array('post_type' => 'showcase_slide', 'posts_per_page' => $post_count, 'orderby' => 'menu_order', 'order' => 'ASC');

            $loop = new WP_Query($query);

            $slide_image_array = array();
            $nivo_caption_array = array();

            if ($loop->have_posts()) :

                $count = 0;

                $disable_caption = mo_get_theme_option('mo_disable_nivo_slider_caption');

                while ($loop->have_posts()) : $loop->the_post();

                    $title = get_the_title();

                    $slide_link = get_post_meta(get_the_ID(), '_slide_link_field', true);

                    $slide_caption_index = 'slide-caption' . ++$count;

                    $slide_info = htmlspecialchars_decode(get_post_meta(get_the_ID(), '_slide_info_field', true));

                    $before_html = '<a title="' . $title . '" href="' . $slide_link . '">';
                    $after_html = '</a>';

                    $args = array('image_size' => 'full',
                        'size' => 'full',
                        'before_html' => $before_html,
                        'after_html' => $after_html,
                        'image_alt' => $title,
                        'image_title' => ('#' . $slide_caption_index),
                        'image_class' => 'slider-image',
                        'wrapper' => false /* Do not generate unwanted wrappers around images - spoils the nivo effects */
                    );

                    // Make sure you use the slide caption as image alt attribute
                    $thumbnail_url = mo_get_thumbnail($args);

                    $nivo_caption = '<div id="' . $slide_caption_index . '" class="nivo-html-caption"><h3><a href="' . $slide_link . '" title="' . $title . '">' . $title . '</a></h3><div class="nivo-summary">' . $slide_info . '</div></div>';

                    // In sliders, always skip the post if it does not have a thumbnail
                    if (!empty($thumbnail_url)) {
                        $slide_image_array[] = $thumbnail_url;

                        if (!$disable_caption) {
                            $nivo_caption_array[] = $nivo_caption;
                        }
                    }

                endwhile;

            endif;

            wp_reset_postdata();


            if (!empty($slide_image_array)) :

                echo '<div id="nivo-slider-wrap">';

                echo '<div id="nivo-slider" class="loading">';

                foreach ($slide_image_array as $slider_image_url):

                    echo $slider_image_url . "\n";

                endforeach;

                echo '</div> <!-- #nivo-silder -->';

                foreach ($nivo_caption_array as $nivo_rich_caption):

                    echo $nivo_rich_caption . "\n";

                endforeach;

                echo '</div> <!-- #nivo-silder-wrap -->';

            endif;
        }

        function display_flex_slider() {

            $post_count = mo_get_theme_option('mo_flex_slider_post_count', 8);

            $query = array('post_type' => 'showcase_slide', 'posts_per_page' => $post_count, 'orderby' => 'menu_order', 'order' => 'ASC');

            $loop = new WP_Query($query);
            ?>

            <?php
            if ($loop->have_posts()) :

                $count = 1;
                ?>

                <div class="flexslider">

                    <ul class="slides">

                        <?php
                        while ($loop->have_posts()) : $loop->the_post();

                            $title = get_the_title();

                            $slide_link = get_post_meta(get_the_ID(), '_slide_link_field', true);

                            $slide_info = htmlspecialchars_decode(get_post_meta(get_the_ID(), '_slide_info_field', true));

                            $disable_caption = mo_get_theme_option('mo_disable_flex_slider_caption');

                            $slider_caption = '';

                            if (!$disable_caption) {
                                $slider_caption = '<div class="flex-caption"><a href="' . $slide_link . '" title="' . $title . '">' . $title . '</a><div class="flex-summary">' . $slide_info . '</div></div>';
                            }

                            $before_html = '<a title="' . $title . '" href="' . $slide_link . '">';
                            $after_html = '</a>';

                            // Make sure you use the slide caption as image alt attribute (in this case post title).
                            // Also, let the image size be same as that of Nivo, helps when WP generates thumbnails
                            $args = array('image_size' => 'full',
                                'size' => 'full',
                                'before_html' => $before_html,
                                'after_html' => $after_html,
                                'image_alt' => $title,
                                'image_title' => $title,
                                'image_class' => 'slider-image',
                                'wrapper' => false
                            );

                            $thumbnail_url = mo_get_thumbnail($args);

                            // In sliders, always skip the post if it does not have a thumbnail
                            if (!empty($thumbnail_url)) {
                                echo '<li>';
                                echo $thumbnail_url;
                                echo $slider_caption;
                                echo '</li>';
                            }

                        endwhile;

                        ?>

                    </ul>
                    <!-- #flex-slider -->

                </div>

            <?php
            endif;

            wp_reset_postdata();
        }

    }

    function mo_get_revolution_slider_options() {
        $rev_sliders = array();
        if (class_exists('RevSlider')) {
            $slider = new RevSlider();
            $rev_sliders = $slider->getArrSliders();
        }
        $options_array = array();
        if (!empty($rev_sliders)) {
            foreach ($rev_sliders as $rev_slider) {
                $slider_option[] = array();
                $slider_option['value'] = $rev_slider->getID();
                $slider_option['label'] = $rev_slider->getTitle();
                $slider_option['src'] = '';
                $options_array[] = $slider_option;
            }
        }
        else {
            $slider_option[] = array();
            $slider_option['value'] = 'none';
            $slider_option['label'] = __('Plugin Not Activated or No Sliders Created', 'mo_theme');
            $slider_option['src'] = '';
            $options_array[] = $slider_option;

        }
        return $options_array;
    }

}

/* Avoid defining global functions here - for child theme sake */