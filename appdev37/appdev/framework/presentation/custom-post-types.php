<?php

if (!function_exists('mo_register_custom_post_types')) {
    function mo_register_custom_post_types() {

        mo_register_portfolio_type();

        //mo_register_gallery_type();

        if (current_theme_supports('single-page-site'))
            mo_register_page_section_type();

        mo_register_showcase_slide_type();

        mo_register_team_profile_post_type();

        mo_register_testimonials_post_type();

        mo_register_pricing_post_type();

        /* Manage Portfolio Columns */
        //add_filter('manage_edit-portfolio_columns', 'mo_portfolio_type_edit_columns');
        //add_action('manage_posts_custom_column', 'mo_portfolio_type_custom_columns');

        /*add_filter('manage_edit-gallery_columns', 'mo_gallery_type_edit_columns');
        add_action('manage_posts_custom_column', 'mo_gallery_type_custom_columns');*/

        /* Manage Testimonials Columns */
        add_filter('manage_edit-testimonials_columns', 'mo_testimonials_edit_columns');
        add_action('manage_posts_custom_column', 'mo_testimonials_columns');

        /* Manage Team Columns */
        add_filter('manage_edit-team_columns', 'mo_team_edit_columns');
        add_action('manage_posts_custom_column', 'mo_team_columns');

        add_filter('manage_edit-pricing_columns', 'mo_pricing_edit_columns');
        add_action('manage_posts_custom_column', 'mo_pricing_columns');
    }
}

if (!function_exists('mo_register_testimonials_post_type')) {
    function mo_register_testimonials_post_type() {
        $labels = array(
            'name' => _x('Testimonials', 'post type general name', 'mo_theme'),
            'singular_name' => _x('Testimonial', 'post type singular name', 'mo_theme'),
            'menu_name' => _x('Testimonials', 'post type menu name', 'mo_theme'),
            'add_new' => _x("Add New", "testimonial item", 'mo_theme'),
            'add_new_item' => __('Add New Testimonial', 'mo_theme'),
            'edit_item' => __('Edit Testimonial', 'mo_theme'),
            'new_item' => __('New Testimonial', 'mo_theme'),
            'view_item' => __('View Testimonial', 'mo_theme'),
            'search_items' => __('Search Testimonials', 'mo_theme'),
            'not_found' => __('No Testimonials found', 'mo_theme'),
            'not_found_in_trash' => __('No Testimonials in the trash', 'mo_theme'),
            'parent_item_colon' => '',
        );

        register_post_type('testimonials', array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'exclude_from_search' => true,
            'query_var' => true,
            'rewrite' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 10,
            'menu_icon' => MO_THEME_URL . '/images/admin/balloon-quotation.png',
            'supports' => array('title', 'editor', 'thumbnail', 'page-attributes')
        ));
    }
}

if (!function_exists('mo_register_pricing_post_type')) {
    function mo_register_pricing_post_type() {
        $labels = array(
            'name' => _x('Pricing Plans', 'post type general name', 'mo_theme'),
            'singular_name' => _x('Pricing Plan', 'post type singular name', 'mo_theme'),
            'menu_name' => _x('Pricing Plan', 'post type menu name', 'mo_theme'),
            'add_new' => _x('Add New', 'pricing plan item', 'mo_theme'),
            'add_new_item' => __('Add New Pricing Plan', 'mo_theme'),
            'edit_item' => __('Edit Pricing Plan', 'mo_theme'),
            'new_item' => __('New Pricing Plan', 'mo_theme'),
            'view_item' => __('View Pricing Plan', 'mo_theme'),
            'search_items' => __('Search Pricing Plans', 'mo_theme'),
            'not_found' => __('No Pricing Plans found', 'mo_theme'),
            'not_found_in_trash' => __('No Pricing Plans in the trash', 'mo_theme'),
            'parent_item_colon' => ''
        );

        register_post_type('pricing', array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'exclude_from_search' => true,
            'query_var' => true,
            'rewrite' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 10,
            'menu_icon' => MO_THEME_URL . '/images/admin/price-tag.png',
            'supports' => array('title', 'editor', 'page-attributes')
        ));
    }
}

if (!function_exists('mo_register_team_profile_post_type')) {
    function mo_register_team_profile_post_type() {
        // Labels
        $labels = array(
            'name' => _x("Team", "post type general name", 'mo_theme'),
            'singular_name' => _x("Team", "post type singular name", 'mo_theme'),
            'menu_name' => _x('Team Profiles', 'post type menu name', 'mo_theme'),
            'add_new' => _x("Add New", "team item", 'mo_theme'),
            'add_new_item' => __("Add New Profile", 'mo_theme'),
            'edit_item' => __("Edit Profile", 'mo_theme'),
            'new_item' => __("New Profile", 'mo_theme'),
            'view_item' => __("View Profile", 'mo_theme'),
            'search_items' => __("Search Profiles", 'mo_theme'),
            'not_found' => __("No Profiles Found", 'mo_theme'),
            'not_found_in_trash' => __("No Profiles Found in Trash", 'mo_theme'),
            'parent_item_colon' => ''
        );

        // Register post type
        register_post_type('team', array(
            'labels' => $labels,
            'public' => false,
            'show_ui' => true,
            'hierarchical' => false,
            'publicly_queryable' => false,
            'query_var' => true,
            'exclude_from_search' => true,
            'show_in_nav_menus' => false,
            'menu_position' => 20,
            'has_archive' => false,
            'menu_icon' => get_template_directory_uri() . '/images/admin/users.png',
            'rewrite' => false,
            'supports' => array('title', 'editor', 'thumbnail', 'page-attributes')
        ));

        // Labels
        $labels = array(
            'name' => _x('Departments', "taxonomy general name", 'mo_theme'),
            'singular_name' => _x('Department', "taxonomy singular name", 'mo_theme'),
            'search_items' => __("Search Department", 'mo_theme'),
            'all_items' => __("All Departments", 'mo_theme'),
            'parent_item' => __("Parent Department", 'mo_theme'),
            'parent_item_colon' => __("Parent Department:", 'mo_theme'),
            'edit_item' => __("Edit Department", 'mo_theme'),
            'update_item' => __("Update Department", 'mo_theme'),
            'add_new_item' => __("Add New Department", 'mo_theme'),
            'new_item_name' => __("New Department Name", 'mo_theme'),
        );

        // Register and attach to 'team' post type
        register_taxonomy('department', 'team', array(
            'public' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'hierarchical' => true,
            'query_var' => true,
            'rewrite' => false,
            'labels' => $labels
        ));
    }

}

if (!function_exists('mo_register_portfolio_type')) {
    function mo_register_portfolio_type() {

        $labels = array(
            'name' => _x('Portfolio', 'portfolio name', 'mo_theme'),
            'singular_name' => _x('Portfolio Entry', 'portfolio type singular name', 'mo_theme'),
            'menu_name' => _x('Portfolio', 'portfolio type menu name', 'mo_theme'),
            'add_new' => _x('Add New', 'portfolio item', 'mo_theme'),
            'add_new_item' => __('Add New Portfolio Entry', 'mo_theme'),
            'edit_item' => __('Edit Portfolio Entry', 'mo_theme'),
            'new_item' => __('New Portfolio Entry', 'mo_theme'),
            'view_item' => __('View Portfolio Entry', 'mo_theme'),
            'search_items' => __('Search Portfolio Entries', 'mo_theme'),
            'not_found' => __('No Portfolio Entries Found', 'mo_theme'),
            'not_found_in_trash' => __('No Portfolio Entries Found in Trash', 'mo_theme'),
            'parent_item_colon' => ''
        );

        register_post_type('portfolio', array('labels' => $labels,

                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'capability_type' => 'post',
                'hierarchical' => false,
                'publicly_queryable' => true,
                'query_var' => true,
                'exclude_from_search' => false,
                'rewrite' => array('slug' => 'portfolio'),
                'taxonomies' => array('portfolio_category'),
                'show_in_nav_menus' => false,
                'menu_position' => 20,
                'menu_icon' => MO_THEME_URL . '/images/admin/portfolio.png',
                'supports' => array('title', 'editor', 'thumbnail', 'comments', 'excerpt', 'custom-fields')
            )
        );

        register_taxonomy('portfolio_category', array('portfolio'), array('hierarchical' => true,
            'label' => __('Portfolio Categories', 'mo_theme'),
            'singular_label' => __('Portfolio Category', 'mo_theme'),
            'rewrite' => true,
            'query_var' => true
        ));
    }
}

if (!function_exists('mo_register_gallery_type')) {
    function mo_register_gallery_type() {

        $labels = array(
            'name' => _x('Gallery', 'gallery name', 'mo_theme'),
            'singular_name' => _x('Gallery Entry', 'gallery type singular name', 'mo_theme'),
            'menu_name' => _x('Gallery', 'gallery type menu name', 'mo_theme'),
            'add_new' => _x('Add New', 'gallery', 'mo_theme'),
            'add_new_item' => __('Add New Gallery Entry', 'mo_theme'),
            'edit_item' => __('Edit Gallery Entry', 'mo_theme'),
            'new_item' => __('New Gallery Entry', 'mo_theme'),
            'view_item' => __('View Gallery Entry', 'mo_theme'),
            'search_items' => __('Search Gallery Entries', 'mo_theme'),
            'not_found' => __('No Gallery Entries Found', 'mo_theme'),
            'not_found_in_trash' => __('No Gallery Entries Found in Trash', 'mo_theme'),
            'parent_item_colon' => ''
        );

        register_post_type('gallery', array('labels' => $labels,

                'public' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'capability_type' => 'post',
                'hierarchical' => false,
                'publicly_queryable' => true,
                'query_var' => true,
                'exclude_from_search' => false,
                'rewrite' => array('slug' => 'gallery'),
                'taxonomies' => array('gallery_category'),
                'show_in_nav_menus' => false,
                'menu_position' => 20,
                'menu_icon' => MO_THEME_URL . '/images/admin/portfolio.png',
                'supports' => array('title', 'thumbnail', 'excerpt')
            )
        );

        register_taxonomy('gallery_category', array('gallery'), array('hierarchical' => true,
            'label' => __('Gallery Categories', 'mo_theme'),
            'singular_label' => __('Gallery Category', 'mo_theme'),
            'rewrite' => true,
            'query_var' => true
        ));
    }

}

if (!function_exists('mo_register_page_section_type')) {
    function mo_register_page_section_type() {

        $labels = array(
            'name' => _x('Page Section', 'page section general name', 'mo_theme'),
            'singular_name' => _x('Page Section', 'page section singular name', 'mo_theme'),
            'menu_name' => _x('Page Sections', 'post type menu name', 'mo_theme'),
            'add_new' => _x('Add New', 'page ', 'mo_theme'),
            'add_new_item' => __('Add New Page Section', 'mo_theme'),
            'edit_item' => __('Edit Page Section', 'mo_theme'),
            'new_item' => __('New Page Section', 'mo_theme'),
            'view_item' => __('View Page Section', 'mo_theme'),
            'search_items' => __('Search Page Sections', 'mo_theme'),
            'not_found' => __('No Page Sections Found', 'mo_theme'),
            'not_found_in_trash' => __('No Page Sections Found in Trash', 'mo_theme'),
            'parent_item_colon' => ''
        );

        register_post_type('page_section', array('labels' => $labels,
                'description' => __('A custom post type which represents a section like about, work, services, team etc. part of a typical single page site. Can be made up of one or more segments.', 'mo_theme'),
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'capability_type' => 'page',
                'hierarchical' => false,
                'publicly_queryable' => true,
                'query_var' => true,
                'exclude_from_search' => true,
                'show_in_nav_menus' => false,
                'menu_position' => 15,
                'menu_icon' => MO_THEME_URL . '/images/admin/blogs-stack.png',
                'rewrite' => array('slug' => 'page-section'),
                'supports' => array('title', 'editor', 'page-attributes', 'revisions')
            )
        );

    }
}

if (!function_exists('mo_register_showcase_slide_type')) {
    function mo_register_showcase_slide_type() {
        register_post_type('showcase_slide', array(
            'labels' => array(
                'name' => __('Showcase Slides', 'mo_theme'),
                'singular_name' => __('Showcase Slide', 'post type singular name', 'mo_theme'),
                'menu_name' => _x('Showcase Slides', 'post type menu name', 'mo_theme'),
                'add_new' => _x('Add New', 'showcase slide item', 'mo_theme'),
                'add_new_item' => __('Add New Slide', 'mo_theme'),
                'edit_item' => __('Edit Slide', 'mo_theme'),
                'new_item' => __('New Slide', 'mo_theme'),
                'view_item' => __('View Slide', 'mo_theme'),
                'search_items' => __('Search Slides', 'mo_theme'),
                'not_found' => __('No Slides Found', 'mo_theme'),
                'not_found_in_trash' => __('No Slides Found in Trash', 'mo_theme'),
                'parent_item_colon' => ''
            ),
            'description' => __('A custom post type which has the required information to display showcase slides in a slider', 'mo_theme'),
            'public' => false,
            'show_ui' => true,
            'publicly_queryable' => false,
            'capability_type' => 'post',
            'hierarchical' => false,
            'exclude_from_search' => true,
            'menu_position' => 20,
            'menu_icon' => MO_THEME_URL . '/images/admin/slides-stack.png',
            'supports' => array('title', 'thumbnail', 'page-attributes')
        ));
    }
}

if (!function_exists('mo_portfolio_type_edit_columns')) {
    function mo_portfolio_type_edit_columns($columns) {

        $new_columns = array(
            'portfolio_category' => __('Portfolio Categories', 'mo_theme')
        );

        $columns = array_merge($columns, $new_columns);

        return $columns;
    }
}

if (!function_exists('mo_portfolio_type_custom_columns')) {
    function mo_portfolio_type_custom_columns($column) {
        global $post;
        switch ($column) {
            case 'portfolio_category':
                echo get_the_term_list($post->ID, 'portfolio_category', '', ', ', '');
                break;
        }
    }
}

if (!function_exists('mo_gallery_type_edit_columns')) {
    function mo_gallery_type_edit_columns($columns) {

        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Gallery Title', 'mo_theme'),
            'gallery_thumbnail' => __('Thumbnail', 'mo_theme'),
            'gallery_category' => __('Category', 'mo_theme')
        );

        return $columns;
    }
}

if (!function_exists('mo_gallery_type_custom_columns')) {

    function mo_gallery_type_custom_columns($column) {
        global $post;
        switch ($column) {
            case 'gallery_category':
                echo get_the_term_list($post->ID, 'gallery_category', '', ', ', '');
                break;
            case 'gallery_thumbnail':
                mo_thumbnail(array('image_size' => 'mini', 'wrapper' => false, 'image_alt' => get_the_title(), 'size' => 'thumbnail'));
                break;
        }
    }
}

if (!function_exists('mo_team_edit_columns')) {
    function mo_team_edit_columns($columns) {

        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Team Member Name', 'mo_theme'),
            'team_thumbnail' => __('Thumbnail', 'mo_theme'),
            'team_position' => __('Position', 'mo_theme'),
            'team_category' => __('Department', 'mo_theme'),
            'team_order' => __('Team Order', 'mo_theme')
        );

        return $columns;
    }
}

if (!function_exists('mo_team_columns')) {

    function mo_team_columns($column) {
        global $post;
        switch ($column) {
            case 'team_category':
                echo get_the_term_list($post->ID, 'department', '', ', ', '');
                break;
            case 'team_thumbnail':
                mo_thumbnail(array('image_size' => 'mini', 'wrapper' => false, 'image_alt' => get_the_title(), 'size' => 'thumbnail'));
                break;
            case 'team_position':
                echo get_post_meta($post->ID, 'mo_position', true);
                break;
            case 'team_order':
                echo $post->menu_order;
                break;
        }
    }
}

if (!function_exists('mo_testimonials_edit_columns')) {
    function mo_testimonials_edit_columns($columns) {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Title', 'mo_theme'),
            'testimonial' => __('Testimonial', 'mo_theme'),
            'testimonial-client-image' => __('Client\'s Image', 'mo_theme'),
            'testimonial-client-name' => __('Client\'s Name', 'mo_theme'),
            'testimonial-client-details' => __('Client Details', 'mo_theme'),
            'testimonial-order' => __('Testimonial Order', 'mo_theme')
        );

        return $columns;
    }
}

/**
 * Customizing the list view columns
 *
 * This functions is attached to the 'manage_posts_custom_column' action hook.
 */
if (!function_exists('mo_testimonials_columns')) {
    function mo_testimonials_columns($column) {
        global $post;
        switch ($column) {
            case 'testimonial':
                the_excerpt();
                break;
            case 'testimonial-client-image':
                mo_thumbnail(array('image_size' => 'mini', 'wrapper' => false, 'image_alt' => get_the_title(), 'size' => 'thumbnail'));
                break;
            case 'testimonial-client-name':
                echo get_post_meta($post->ID, 'mo_client_name', true);
                break;
            case 'testimonial-client-details':
                echo get_post_meta($post->ID, 'mo_client_details', true);
                break;
            case 'testimonial-order':
                echo $post->menu_order;
                break;
        }
    }
}

if (!function_exists('mo_pricing_edit_columns')) {
    function mo_pricing_edit_columns($columns) {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Pricing Plan Name', 'mo_theme'),
            'pricing-plan-price-tag' => __('Price Tag', 'mo_theme'),
            'pricing-tagline' => __('Tagline', 'mo_theme'),
            'pricing-image' => __('Image', 'mo_theme'),
            'pricing-plan-url' => __('Pricing Plan URL', 'mo_theme'),
            'pricing-plan-order' => __('Pricing Plan Order', 'mo_theme')
        );

        return $columns;
    }
}

/**
 * Customizing the list view columns
 *
 * This functions is attached to the 'manage_posts_custom_column' action hook.
 */
if (!function_exists('mo_pricing_columns')) {
    function mo_pricing_columns($column) {
        global $post;
        switch ($column) {
            case 'pricing-plan-price-tag':
                echo get_post_meta($post->ID, 'mo_price_tag', true);
                break;
            case 'pricing-plan-url':
                echo get_post_meta($post->ID, 'mo_pricing_url', true);
                break;
            case 'pricing-tagline':
                echo get_post_meta($post->ID, 'mo_pricing_tagline', true);
                break;
            case 'pricing-image':
                $image_url = get_post_meta($post->ID, 'mo_pricing_img', true);
                if (!empty($image_url))
                    echo '<img alt="' . $post->post_title . '" src="' . $image_url . '" /><br>';
                break;
            case 'pricing-plan-order':
                echo $post->menu_order;
                break;

        }
    }
}
