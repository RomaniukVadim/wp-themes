<?php
/* Testimonial Slider Shortcode -

Displays a slider of testimonials. These testimonials are entered by creating Testimonial custom post types in the Testimonials tab of the WordPress Admin.
Usage:

[responsive_slider type="testimonials2" animation="slide" control_nav="true" direction_nav=false pause_on_hover="true" slideshow_speed=4500]

[testimonials post_count=4 testimonial_ids="234,235,236"]

[/responsive_slider]

The testimonial shortcode need to be wrapped inside [responsive_slider] shortcode to enable slider function. By default, the [testimonials] and [testimonials2] shortcode
displays a unordered list (UL element) of testimonial elements which can be styled differently if a slider is not desired. Separating out the slider part also helps control
the slider properties like animation speed, slider controls, pause on hover etc. as explained in the documentation for [responsive_slider] shortcode.

Parameters -

post_count - The number of testimonials to be displayed. By default displays all of the custom posts entered as testimonial in the Testimonials tab of the WordPress Admin (optional).
testimonial_ids - A comma separated post ids of the Testimonial custom post types created in the Testimonials tab of the WordPress Admin. Helps to filter the testimonials for display (optional).

*/

if (!function_exists('mo_get_testimonial')) {
    function mo_get_testimonial($post_count = -1, $testimonial_ids = null) {
        $query_args = array(
            'posts_per_page' => (int)$post_count,
            'post_type' => 'testimonials',
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'no_found_rows' => true,
        );
        if (!empty($testimonial_ids))
            $query_args['post__in'] = explode(',', $testimonial_ids);

        $query = new WP_Query($query_args);

        $testimonials = '';
        if ($query->have_posts()) {

            // Gather output
            ob_start(); ?>

            <ul>

                <?php

                while ($query->have_posts()) : $query->the_post();
                    $post_id = get_the_ID();
                    $client_name = htmlspecialchars_decode(get_post_meta($post_id, 'mo_client_name', true));
                    $client_details = htmlspecialchars_decode(get_post_meta($post_id, 'mo_client_details', true));

                    $client_name = (empty($client_name)) ? '' : $client_name;
                    $client_details = (empty($client_details)) ? '' : $client_details;
                    ?>


                    <li>
                        <blockquote>
                            <p><?php echo get_the_content() ?></p>

                            <div class="footer">

                                <?php mo_thumbnail(array('before_html' => '<p>', 'after_html' => '</p>', 'image_size' => 'square', 'image_class' => 'alignleft img-circle', 'wrapper' => false, 'image_alt' => 'Testimonial', 'size' => 'full')); ?>

                                <span><?php echo $client_name . ', ' . $client_details ?></span>
                            </div>
                        </blockquote>
                    </li>
                <?php

                endwhile;

                wp_reset_postdata();

                ?>

            </ul>

            <?php
            // Save output
            $testimonials = ob_get_contents();
            ob_end_clean();
        }

        return $testimonials;
    }
}

if (!function_exists('mo_testimonial_shortcode')) {
    function mo_testimonial_shortcode($atts) {
        extract(shortcode_atts(array(
            'post_count' => '-1',
            'testimonial_ids' => '',
        ), $atts));

        return mo_get_testimonial($post_count, $testimonial_ids);
    }
}

add_shortcode('testimonials', 'mo_testimonial_shortcode');
