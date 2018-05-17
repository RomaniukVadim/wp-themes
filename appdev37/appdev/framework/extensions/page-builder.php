<?php

/* ----------- SiteOrigin Page Builder ------------- */

add_filter('siteorigin_widgets_widget_folders', 'mo_add_widgets_collection');

add_filter('siteorigin_panels_widget_dialog_tabs', 'mo_add_widget_tabs', 20);

add_filter('siteorigin_panels_widgets', 'mo_add_bundle_groups', 11);

add_filter('siteorigin_panels_row_style_fields', 'mo_row_style_fields');

add_filter('siteorigin_panels_row_style_attributes', 'mo_row_style_attributes', 10, 2);

// Filtering specific attributes
add_filter('siteorigin_panels_css_cell_margin_bottom', 'mo_filter_cell_bottom_margin', 10, 2);

add_filter('siteorigin_widgets_default_active', 'mo_activate_theme_widgets');


function mo_add_widgets_collection($folders) {
    $folders[] = get_template_directory() . '/framework/siteorigin/widgets/';
    return $folders;
}


// Placing all widgets under the 'SiteOrigin Widgets' Tab
function mo_add_widget_tabs($tabs) {
    $tabs[] = array(
        'title' => __('Livemesh Theme Widgets', 'mo_theme'),
        'filter' => array(
            'groups' => array('livemesh-widgets')
        )
    );
    return $tabs;
}


// Adding group for all Widgets
function mo_add_bundle_groups($widgets) {
    foreach ($widgets as $class => &$widget) {
        if (preg_match('/MO_(.*)_Widget/', $class, $matches)) {
            $widget['groups'] = array('livemesh-widgets');
        }
    }
    return $widgets;
}

function mo_row_style_fields($fields) {
    // Add the attribute fields

    $fields['row_id'] = array(
        'name' => __('Row ID for styling', 'mo_theme'),
        'type' => 'text',
        'group' => 'attributes',
        'description' => __('An ID for the row for styling purposes.', 'mo_theme'),
        'priority' => 4,
    );

    return $fields;
}

function mo_row_style_attributes($attributes, $args) {

    // Do not set id if already set. Hope to get rid of this in future when page builder has this function
    if (!empty($args['row_id']) && empty($attributes['id'])) {
        $attributes['id'] = $args['row_id'];
    }

    return $attributes;
}

/* Set the bottom margin same as that specified for container row - typically 0px for all Livemesh themes content */
function mo_filter_cell_bottom_margin($margin, $grid) {
    if (!empty($grid['style']['bottom_margin'])) {
        $margin = $grid['style']['bottom_margin'];
    }
    return $margin;
}



function mo_activate_theme_widgets($default_widgets) {

    $theme_widgets = array(

        "heading-widget" => true,
        "action-call-widget" => true,
        "button-widget" => true,
        "divider-widget" => true,
        "divider-top-widget" => true,
        "header-fancy-widget" => true,
        "hero-section-widget" => true,
        "custom-posts-widget" => true,
        "contact-form-widget" => true,
        "pricing-plans-widget" => true,
        "show-portfolio-widget" => true,
        "social-list-widget" => true,
        "toggle-widget" => true,
        "testimonials-slider-widget" => true,
        "responsive-slider-widget" => true,
        "show-post-snippets-widget" => true,
        "smartphone-slider-widget" => true,
        "tabs-widget" => true,
        "team-widget" => true,
        "show-rounded-post-snippets-widget" => true,

        // Livemesh SiteOrigin Widgets

        "lsow-accordion-widget" => true,
        "lsow-carousel-widget" => true,
        "lsow-clients-widget" => true,
        "lsow-heading-widget" => true,
        "lsow-hero-image-widget" => true,
        "lsow-odometers-widget" => true,
        "lsow-piecharts-widget" => true,
        "lsow-portfolio-widget" => true,
        "lsow-posts-carousel-widget" => true,
        "lsow-pricing-table-widget" => true,
        "lsow-services-widget" => true,
        "lsow-stats-bar-widget" => true,
        "lsow-tabs-widget" => true,
        "lsow-team-members-widget" => true,
        "lsow-testimonials-slider-widget" => true,
        "lsow-testimonials-widget" => true,

    );

    return wp_parse_args($theme_widgets, $default_widgets);

}

