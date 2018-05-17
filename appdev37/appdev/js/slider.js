/* jshint undef: true, unused: false */
/* global mo_options, jQuery */

jQuery.noConflict();

jQuery(document).ready(function ($) {

    "use strict";

    function bool_of(bool_string) {
        var bool_value = ((bool_string === "true" || bool_string === "1") ? true : false);
        return bool_value;
    }

    function toggle_of(bool_string) {
        var toggle_value = (bool_string === "true" ? 1 : 0);
        return toggle_value;
    }

    if (typeof mo_options.slider_chosen === 'undefined') {
        return;
    }

    if (mo_options.slider_chosen === 'Nivo') {

        $('#nivo-slider').nivoSlider({
            effect: mo_options.nivo_effect, // Specify sets like: 'fold,fade,sliceDown'
            slices: parseInt(mo_options.nivo_slices, 10), // For slice animations
            animSpeed: parseInt(mo_options.nivo_animation_speed, 10), // Slide transition speed
            pauseTime: parseInt(mo_options.nivo_pause_time, 10), // How long each slide will show
            startSlide: 0, // Set starting Slide (0 index)
            directionNav: bool_of(mo_options.nivo_dir_navigation), // Next & Prev navigation
            controlNav: bool_of(mo_options.nivo_controls), // 1,2,3... navigation
            keyboardNav: false, // Use left & right arrows
            pauseOnHover: bool_of(mo_options.nivo_pause_on_hover), // Stop animation while hovering
            manualAdvance: false, // Force manual transitions
            randomStart: bool_of(mo_options.nivo_start_random_slide), // Start on a random slide
            prevText: 'Prev<span></span>',
            nextText: 'Next<span></span>'
        });
    } else if (mo_options.slider_chosen === 'FlexSlider') {

        $('#slider-area .flexslider').flexslider({
            animation: mo_options.flex_slider_effect,
            slideshowSpeed: parseInt(mo_options.flex_slider_pause_time, 10), //Integer: Set the speed of the slideshow cycling, in milliseconds
            animationSpeed: parseInt(mo_options.flex_slider_animation_speed, 10),
            pauseOnHover: toggle_of(mo_options.flex_slider_pause_on_hover),
            randomize: toggle_of(mo_options.flex_slider_display_random_slide),
            nextText: 'Next<span></span>',
            prevText: 'Previous<span></span>'
        });
    }

});

jQuery(window).load(function () {
    // Wait till all images have loaded before unwrapping
    setTimeout(function () {
        jQuery('.flex-slider-container, #nivo-slider, .carousel-wrap').removeClass('loading');
    }, 400);
});