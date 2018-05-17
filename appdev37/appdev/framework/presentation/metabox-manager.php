<?php
/**
 * Custom Meta Boxes using Option Tree framework
 * @package Livemesh_Framework
 */

/**
 * Initialize the meta boxes.
 */
add_action('admin_init', 'mo_custom_meta_boxes');

if (!function_exists('mo_custom_meta_boxes')) {
    function mo_custom_meta_boxes() {

        mo_build_testimonials_meta_boxes();

        mo_build_pricing_plan_meta_boxes();

        mo_build_team_profile_meta_boxes();

        mo_build_one_page_metabox();

        mo_build_advanced_page_meta_boxes();

        mo_build_header_metabox();

        mo_build_custom_header_metabox();

        mo_build_custom_sidebar_metabox();

        /*$page_meta_box = array(
            'id' => 'mo_page_options',
            'title' => 'Page Options',
            'desc' => '',
            'pages' => array('page'),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(

                array(
                    'id'          => 'mo_featured_portfolio',
                    'label'       => 'Select the featured portfolio',
                    'desc'        => 'Select the featured portfolio item to be displayed on top of the page.',
                    'std'         => '',
                    'type'        => 'custom-post-type-select',
                    'rows'        => '',
                    'post_type'   => 'portfolio',
                    'taxonomy'    => '',
                    'class'       => ''
                )

            )
        );

        ot_register_meta_box($page_meta_box);*/

        mo_build_custom_post_metabox();

    }
}
if (!function_exists('mo_build_custom_post_metabox')) {
    function mo_build_custom_post_metabox() {
        $post_meta_box = array(
            'id' => 'mo_post_thumbnail_detail',
            'title' => 'Post Thumbnail Options',
            'desc' => '',
            'pages' => array('post'),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(

                array(
                    'label' => __('Use Video as Thumbnail', 'mo_theme'),
                    'id' => 'mo_use_video_thumbnail',
                    'type' => 'checkbox',
                    'desc' => 'Specify if video will be used as a thumbnail instead of a featured image.',
                    'choices' => array(
                        array(
                            'label' => __('Yes', 'mo_theme'),
                            'value' => 'Yes'
                        )
                    ),
                    'std' => '',
                    'rows' => '',
                    'class' => ''
                ),

                array(
                    'label' => __('Video URL', 'mo_theme'),
                    'id' => 'mo_video_thumbnail_url',
                    'type' => 'text',
                    'desc' => 'Specify the URL of the video (Youtube or Vimeo only).',
                    'std' => '',
                    'rows' => '',
                    'class' => ''
                ),

                array(
                    'label' => __('Use Slider as Thumbnail', 'mo_theme'),
                    'id' => 'mo_use_slider_thumbnail',
                    'type' => 'checkbox',
                    'desc' => 'Specify if slider will be used as a thumbnail instead of a featured image or a video.',
                    'choices' => array(
                        array(
                            'label' => __('Yes', 'mo_theme'),
                            'value' => 'Yes'
                        )
                    ),
                    'std' => '',
                    'rows' => '',
                    'class' => ''
                ),

                array(
                    'label' => __('Images for thumbnail slider', 'mo_theme'),
                    'id' => 'post_slider',
                    'desc' => 'Specify the images to be used a slider thumbnails for the post',
                    'type' => 'list-item',
                    'class' => '',
                    'settings' => array(
                        array(
                            'id' => 'slider_image',
                            'label' => __('Image', 'mo_theme'),
                            'desc' => '',
                            'std' => '',
                            'type' => 'upload',
                            'class' => '',
                            'choices' => array()
                        )
                    )
                )

            )
        );

        ot_register_meta_box($post_meta_box);
    }
}

if (!function_exists('mo_build_custom_sidebar_metabox')) {
    function mo_build_custom_sidebar_metabox() {
        $my_sidebars = mo_get_user_defined_sidebars();

        $sidebar_meta_box = array(
            'id' => 'mo_sidebar_options',
            'title' => __('Choose Custom Sidebar', 'mo_theme'),
            'desc' => '',
            'pages' => array(
                'post',
                'page'
            ),
            'context' => 'side',
            'priority' => 'default',
            'fields' => array(
                array(
                    'id' => 'mo_primary_sidebar_choice',
                    'label' => __('Custom Sidebar Choice', 'mo_theme'),
                    'desc' => 'Custom sidebar for the post/page. <i>Useful if the post/page is not designated as full width.</i>',
                    'std' => '',
                    'type' => 'select',
                    'rows' => '',
                    'post_type' => '',
                    'taxonomy' => '',
                    'class' => '',
                    'choices' => $my_sidebars
                )
            )
        );

        ot_register_meta_box($sidebar_meta_box);
    }
}
if (!function_exists('mo_build_custom_header_metabox')) {
    function mo_build_custom_header_metabox() {
        $custom_header_meta_box = array(
            'id' => 'mo_custom_entry_header_options',
            'title' => __('Custom Header Options', 'mo_theme'),
            'desc' => '',
            'pages' => array(
                'post',
                'page',
                'portfolio'
            ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'id' => 'mo_custom_heading_background',
                    'label' => __('Custom Heading Background', 'mo_theme'),
                    'desc' => '',
                    'std' => '',
                    'type' => 'background',
                    'desc' => 'Specify a background for custom heading content that replaces the regular page/post title area. Spans entire screen width or maximum available width (boxed layout).'
                ),
                array(
                    'id' => 'mo_custom_heading_content',
                    'label' => __('Custom Heading Content', 'mo_theme'),
                    'desc' => '',
                    'std' => '',
                    'type' => 'textarea',
                    'desc' => 'Enter custom heading content HTML markup that replaces the regular page/post title area. This can be any of these - image, a slider, a slogan, purchase/request quote button, an invitation to signup or any plain marketing material.<br><br>Shown under the logo area. Be aware of SEO implications and <strong>use heading tags appropriately</strong>.',
                    'rows' => '8'
                ),
                array(
                    'id' => 'mo_wide_heading_layout',
                    'label' => __('Custom Heading Content spans entire screen width', 'mo_theme'),
                    'desc' => '',
                    'std' => '',
                    'type' => 'checkbox',
                    'desc' => 'Make the heading content span the entire screen width. While the background graphics or color spans entire screen width for custom heading content, the HTML markup consisting of heading text and content is restricted to the 1140px grid in the center of the window. <br>Choosing this option will make the content span the entire screen width or max available width(boxed layout).<br><strong>Choose this option when when you want to go for a custom heading with maps or a wide slider like the revolution slider in the custom heading area</strong>.',
                    'choices' => array(
                        array(
                            'value' => 'Yes',
                            'label' => __('Yes', 'mo_theme'),
                            'src' => ''
                        )
                    )
                )
            )
        );

        ot_register_meta_box($custom_header_meta_box);
    }
}
if (!function_exists('mo_build_header_metabox')) {
    function mo_build_header_metabox() {
        $header_meta_box = array(
            'id' => 'mo_entry_header_options',
            'title' => __('Header Options', 'mo_theme'),
            'desc' => '',
            'pages' => array(
                'post',
                'page',
                'portfolio'
            ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'id' => 'mo_description',
                    'label' => __('Description', 'mo_theme'),
                    'desc' => '',
                    'std' => '',
                    'type' => 'textarea',
                    'desc' => 'Enter the description of the page/post. Shown under the entry title.',
                    'rows' => '2'
                ),
                array(
                    'id' => 'mo_entry_title_background',
                    'label' => __('Entry Title Background', 'mo_theme'),
                    'desc' => '',
                    'std' => '',
                    'type' => 'background',
                    'desc' => 'Specify a background for your page/post title and description.'
                ),
                array(
                    'id' => 'mo_entry_title_height',
                    'label' => __('Page/Post Title Height', 'mo_theme'),
                    'desc' => 'Specify the approximate height in pixel units that the entry title area for a page/post occupies along with the background. <br><br> Does not apply when custom heading content is specified. ',
                    'type' => 'text',
                    'std' => '',
                    'rows' => '',
                    'class' => ''
                ),
                array(
                    'id' => 'mo_disable_breadcrumbs_for_entry',
                    'label' => __('Disable Breadcrumbs on this Post/Page', 'mo_theme'),
                    'desc' => '',
                    'std' => '',
                    'type' => 'checkbox',
                    'desc' => 'Disable Breadcrumbs on this Post/Page. Breadcrumbs can be a hindrance in many pages that showcase marketing content. Home pages and wide layout pages will have no breadcrumbs displayed.',
                    'choices' => array(
                        array(
                            'value' => 'Yes',
                            'label' => __('Yes', 'mo_theme'),
                            'src' => ''
                        )
                    )
                )
            )
        );

        ot_register_meta_box($header_meta_box);
    }
}

if (!function_exists('mo_build_advanced_page_meta_boxes')) {

    function mo_build_advanced_page_meta_boxes() {

        $menu_array = array(array('value' => 'default',
                                  'label' => __('Default', 'mo_theme'),
                                  'src' => ''
                            ));

        $menu_items = get_terms('nav_menu', array('hide_empty' => true));
        foreach ($menu_items as $wp_menu) {
            $menu_array[] = array('value' => $wp_menu->slug,
                                  'label' => $wp_menu->name,
                                  'src' => ''
            );
        };

        $advanced_page_meta_box = array(
            'id' => 'mo_advanced_entry_options',
            'title' => 'Advanced Entry Options',
            'desc' => '',
            'pages' => array(
                'post',
                'page',
                'portfolio'
            ),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'id' => 'mo_slider_choice',
                    'label' => 'Display Slider and Remove Title Header',
                    'desc' => 'Select your choice of Slider type to be shown in the top section of the page, replacing the default page/post title header for this page. This option is often used with full width page templates like Home Page or Composite Page, although one can choose to display sliders in any page.',
                    'std' => '',
                    'type' => 'select',
                    'section' => 'general_default',
                    'rows' => '',
                    'post_type' => 'page,post,portfolio',
                    'taxonomy' => '',
                    'class' => '',
                    'choices' => array(
                        array(
                            'value' => 'None',
                            'label' => 'None',
                            'src' => ''
                        ),
                        array(
                            'value' => 'Revolution',
                            'label' => 'Revolution Slider',
                            'src' => ''
                        ),
                        array(
                            'value' => 'FlexSlider',
                            'label' => 'FlexSlider',
                            'src' => ''
                        ),
                        array(
                            'value' => 'Nivo',
                            'label' => 'Nivo',
                            'src' => ''
                        )
                    ),
                ),
                array(
                    'id' => 'mo_revolution_slider_choice',
                    'label' => 'Revolution Slider Choice',
                    'desc' => 'If Revolution Slider type is chosen above, choose the instance of Revolution Slider to be displayed in the page/post/portfolio. <strong><i>The Revolution Slider plugin bundled with the theme must be installed and activated before you can choose the slider for display.</i></strong>',
                    'std' => '',
                    'type' => 'select',
                    'section' => 'general_default',
                    'rows' => '',
                    'post_type' => 'page,post,portfolio',
                    'taxonomy' => '',
                    'class' => '',
                    'choices' => mo_get_revolution_slider_options(),
                ),
                array(
                    'id' => 'mo_remove_title_header',
                    'label' => __('Remove Title Header', 'mo_theme'),
                    'desc' => '',
                    'std' => '',
                    'type' => 'checkbox',
                    'post_type' => 'page',
                    'desc' => 'Do not display normal title headers for this entry (disables both custom or default headers specified in heading options below). Useful if normal headers with page/post title and description (or custom HTML) need to be replaced with custom content for a entry as is often the case for pages that use Composite Page template or Home Page template.',
                    'choices' => array(
                        array(
                            'value' => 'Yes',
                            'label' => __('Yes', 'mo_theme'),
                            'src' => ''
                        )
                    )
                ),
                array(
                    'id' => 'mo_custom_primary_navigation_menu',
                    'label' => __('Choose Custom Primary Navigation Menu', 'mo_theme'),
                    'desc' => '',
                    'std' => '',
                    'type' => 'select',
                    'desc' => 'Choose the page specific header navigation menu created using tools in ' . mo_get_menu_admin_url() . '. Useful for one page/single page templates with multiple internal navigation links. Users can choose to any of the custom menu designed in that screen for this page. <br/>Leave "Default" selected to display any global WordPress Menu set by you in ' . mo_get_menu_admin_url() . '.',
                    'choices' => $menu_array
                )

            )
        );

        ot_register_meta_box($advanced_page_meta_box);
    }
}


if (!function_exists('mo_build_one_page_metabox')) {
    function mo_build_one_page_metabox() {
        $one_page_meta_box = array(
            'id' => 'mo_one_page_options',
            'title' => __('Single Page Options', 'mo_theme'),
            'desc' => '',
            'pages' => array('page'),
            'context' => 'side',
            'priority' => 'default',
            'fields' => array(
                array(
                    'label' => __('Choose the Page Sections for display', 'mo_theme'),
                    'id' => 'mo_page_section_select_for_one_page',
                    'type' => 'custom-post-type-checkbox',
                    'desc' => 'Choose the page sections to display if Single Page Site template is chosen for this page. Creating a custom navigation menu configured in the ' . mo_get_menu_admin_url() . ' is recommended with internal links to below chosen page sections.',
                    'std' => '',
                    'rows' => '',
                    'post_type' => 'page_section',
                    'taxonomy' => '',
                    'class' => ''
                ),
            )
        );

        ot_register_meta_box($one_page_meta_box);
    }
}
if (!function_exists('mo_testimonials_meta_boxes')) {
    function mo_build_testimonials_meta_boxes() {
        $testimonials_meta_box = array(
            'id' => 'mo_testimonial_details',
            'title' => __('Testimonial Details', 'mo_theme'),
            'desc' => '',
            'pages' => array('testimonials'),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'id' => 'mo_client_name',
                    'label' => __('Client', 'mo_theme'),
                    'desc' => '',
                    'std' => '',
                    'type' => 'text',
                    'desc' => 'Enter the name of the client for the testimonial.',
                ),
                array(
                    'id' => 'mo_client_details',
                    'label' => __('Client Details', 'mo_theme'),
                    'desc' => '',
                    'std' => '',
                    'type' => 'text',
                    'desc' => 'Enter additional details like position, business name, URL etc. for the source of the testimonial.'
                )
            )
        );

        ot_register_meta_box($testimonials_meta_box);
    }
}

if (!function_exists('mo_build_pricing_plan_meta_boxes')) {
    function mo_build_pricing_plan_meta_boxes() {
        $pricing_meta_box = array(
            'id' => 'mo_pricing_details',
            'title' => __('Pricing Plan Details', 'mo_theme'),
            'desc' => '',
            'pages' => array('pricing'),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'id' => 'mo_price_tag',
                    'label' => __('Price Tag', 'mo_theme'),
                    'std' => '',
                    'type' => 'text',
                    'desc' => 'Enter the price tag for the pricing plan. <strong>HTML is accepted</strong>',
                ),
                array(
                    'id' => 'mo_pricing_tagline',
                    'label' => __('Tagline Text', 'mo_theme'),
                    'desc' => 'Provide any taglines like "Most Popular", "Best Value", "Best Selling", "Most Flexible" etc. that you would like to use for this pricing plan.',
                    'std' => '',
                    'type' => 'text',
                    'class' => ''
                ),
                array(
                    'id' => 'mo_pricing_img',
                    'label' => __('Pricing Image', 'mo_theme'),
                    'desc' => 'Choose the custom image that represents this pricing plan, if any.',
                    'std' => '',
                    'type' => 'upload',
                ),
                array(
                    'id' => 'mo_pricing_url',
                    'label' => __('URL for the Pricing link/button', 'mo_theme'),
                    'std' => '',
                    'type' => 'text',
                    'desc' => 'Provide the target URL for the link or the button shown for this pricing plan.'
                ),
                array(
                    'id' => 'mo_pricing_button_text',
                    'label' => __('Text for the Pricing link/button', 'mo_theme'),
                    'std' => '',
                    'type' => 'text',
                    'desc' => 'Provide the text for the link or the button shown for this pricing plan.'
                ),
                array(
                    'id' => 'mo_highlight_pricing',
                    'label' => __('Highlight Pricing Plan', 'mo_theme'),
                    'desc' => 'Specify if you want to highlight the pricing plan.',
                    'std' => '',
                    'type' => 'checkbox',
                    'class' => '',
                    'choices' => array(
                        array(
                            'value' => 'Yes',
                            'label' => __('Yes', 'mo_theme'),
                            'src' => ''
                        )
                    )
                )
            )
        );

        ot_register_meta_box($pricing_meta_box);
    }
}

if (!function_exists('mo_team_profile_meta_boxes')) {
    function mo_build_team_profile_meta_boxes() {
        $team_meta_box = array(
            'id' => 'mo_team_profile_options',
            'title' => __('Team Profile Details', 'mo_theme'),
            'desc' => '',
            'pages' => array('team'),
            'context' => 'normal',
            'priority' => 'high',
            'fields' => array(
                array(
                    'id' => 'mo_position',
                    'label' => __('Position', 'mo_theme'),
                    'desc' => '',
                    'std' => '',
                    'type' => 'text',
                    'desc' => 'Enter the position of the team member.',
                ),
                array(
                    'id' => 'mo_email',
                    'label' => __('Email', 'mo_theme'),
                    'desc' => '',
                    'std' => '',
                    'type' => 'text',
                    'desc' => 'Provide email for the team member.'
                ),
                array(
                    'id' => 'mo_phone',
                    'label' => 'Phone',
                    'desc' => 'Provide phone number of the team member.',
                    'std' => '',
                    'type' => 'text',
                    'class' => ''
                ),
                array(
                    'id' => 'mo_twitter',
                    'label' => __('Twitter', 'mo_theme'),
                    'desc' => 'URL of the Twitter page of the team member.',
                    'std' => '',
                    'type' => 'text',
                    'class' => ''
                ),
                array(
                    'id' => 'mo_linkedin',
                    'label' => __('LinkedIn', 'mo_theme'),
                    'desc' => 'URL of the LinkedIn profile of the team member.',
                    'std' => '',
                    'type' => 'text',
                    'class' => ''
                ),
                array(
                    'id' => 'mo_facebook',
                    'label' => __('Facebook', 'mo_theme'),
                    'desc' => 'URL of the Facebook page of the team member.',
                    'std' => '',
                    'type' => 'text',
                    'class' => ''
                ),
                array(
                    'id' => 'mo_dribbble',
                    'label' => __('Dribbble', 'mo_theme'),
                    'desc' => 'URL of the Dribbble profile of the team member.',
                    'std' => '',
                    'type' => 'text',
                    'class' => ''
                ),
                array(
                    'id' => 'mo_googleplus',
                    'label' => __('Google Plus', 'mo_theme'),
                    'desc' => 'URL of the Google Plus page of the team member.',
                    'std' => '',
                    'type' => 'text',
                    'class' => ''
                )
            )
        );

        ot_register_meta_box($team_meta_box);
    }
}

if (!function_exists('mo_get_user_defined_sidebars')) {
    function mo_get_user_defined_sidebars() {
        $my_sidebars = array(
            array(
                'label' => __('Default', 'mo_theme'),
                'value' => 'default'
            )
        );

        $sidebar_list = mo_get_theme_option('mo_sidebar_list');

        if (!empty($sidebar_list)) {
            foreach ($sidebar_list as $sidebar_item) {
                $sidebar = array(
                    'label' => $sidebar_item['title'],
                    'value' => $sidebar_item['id']
                );
                $my_sidebars [] = $sidebar;
            }
        }

        return $my_sidebars;
    }
}

if (!function_exists('mo_get_menu_admin_url')) {
    function mo_get_menu_admin_url() {
        $menu_admin_url = get_home_url() . '/wp-admin/nav-menus.php';

        $menu_admin_url = '<a href="' . $menu_admin_url . '" title="' . __('Appearances Menu Screen',
                'mo_theme') . '">' . __('Appearances Menu Screen', 'mo_theme') . '</a>';

        return $menu_admin_url;
    }
}