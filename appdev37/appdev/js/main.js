/* jshint undef: true, unused: false */
/* global jQuery, mo_options, mo_theme, template_dir  */

jQuery.noConflict();

var MO_THEME; // theme namespace

/*================================== Theme Function init =======================================*/

(function ($) {

    "use strict";

    MO_THEME = {

        timers: {},

        // Helps to avoid continuous method execution as can happen in the case of scroll or window resize. Useful specially
        // when DOM access/manipulation is involved
        wait_for_final_event: function (callback, ms, uniqueId) {

            if (!uniqueId) {
                uniqueId = "Don't call this twice without a uniqueId";
            }
            if (MO_THEME.timers[uniqueId]) {
                clearTimeout(MO_THEME.timers[uniqueId]);
            }
            MO_THEME.timers[uniqueId] = setTimeout(callback, ms);
        },

        is_mobile: function () {

            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                return true;
            }
            return false;
        },

        add_body_classes: function () {
            if (MO_THEME.is_mobile()) {
                $('body').addClass('mobile-device');
            }
            if (/iPhone|iPad|iPod/i.test(navigator.userAgent)) {
                $('body').addClass('ios-device');
            }
            if (navigator.userAgent.match(/Android/i) !== null) {
                $('body').addClass('android-device');
            }
        },

        get_internal_link: function (urlString) {
            var internal_link = null;
            if (urlString.indexOf("#") !== -1) {
                var arr = urlString.split('#');
                if (arr.length === 2) {
                    var url = arr[0];
                    internal_link = '#' + arr[1];
                    // check if this internal link belongs to current URL
                    if (url === (document.URL + '/') || url === document.URL) {
                        return internal_link;
                    }
                }
                else if (arr.length === 1) {
                    internal_link = '#' + arr[0];
                    return internal_link;
                }
            }
            return internal_link;

        },

        init_page_navigation: function () {

            // make the parent of current page active for lavalamp highlight
            $('#primary-menu > ul > li.current_page_ancestor').first().addClass('active');

            // make the current page active for lavalamp highlight - top list cannot have both a parent and current page item
            $('#primary-menu > ul > li.current_page_item').first().addClass('active');

            // make the parent of current page active for lavalamp highlight
            $('#primary-menu > ul > li.current-page-ancestor').first().addClass('active');

            // make the current page active for lavalamp highlight - top list cannot have both a parent and current page item
            $('#primary-menu > ul > li.current-page-item').first().addClass('active');

            // make the parent of current page active for lavalamp highlight
            $('#primary-menu > ul > li.current-menu-ancestor').first().addClass('active');

            // make the current page active for lavalamp highlight - top list cannot have both a parent and current page item
            $('#primary-menu > ul > li.current-menu-item').first().addClass('active');

            var lavaLamp = jQuery('html:not(".ie") #primary-menu > ul.menu').lavaLamp({
                speed: 600
            });

            var delay = (function () {
                var timer = 0;
                return function (callback, ms) {
                    clearTimeout(timer);
                    timer = setTimeout(callback, ms);
                };
            })();

            /*--- Sticky Menu -------*/
            var width = $(window).width();
            if (width > 768 && mo_options.sticky_menu) {
                $('#header').waypoint('sticky', {
                    stuckClass: 'sticky',
                    offset: -100,
                    handler: function (direction) {
                        if (direction === "up") {
                            /* Reached the top and hence highlight current page link */
                            $('#primary-menu > ul > li').not(".hover-bg").each(function () {
                                $(this).removeClass('active');
                                var menu_link = $(this).find('a').attr('href').replace(/\/?$/, '/'); // add a trailing slash if not present
                                var current_url = document.URL.replace(/\/?$/, '/');
                                if (menu_link === current_url) {
                                    $(this).addClass('active');
                                    if (lavaLamp !== null) {
                                        lavaLamp.moveLava($(this)[0]);// pass the list element to update lavaLamp menu highlight
                                    }
                                }
                            });
                        }
                        else {
                            if (lavaLamp !== null) {
                                lavaLamp.moveLava(); // Update the position of lavaLamp on reaching the top
                            }
                        }

                    }
                });

                $('#container').waypoint(function () {
                    var height = $('#header').height();
                    $('.sticky-wrapper').height(height);
                    if (lavaLamp !== null) {
                        lavaLamp.moveLava(); // Update the position of lavaLamp on reaching the top
                    }
                });


                $(window).resize(function () {
                    MO_THEME.wait_for_final_event(function () {
                        if ($(document).scrollTop() < 50) {
                            var height = $('#header').height();
                            $('.sticky-wrapper').height(height);
                        }
                    }, 200, 'sticky-wrapper-resize');

                });

            }

            /* ----- Smooth Scroll --------*/

            if (typeof $().smoothScroll !== 'undefined') {
                $('#primary-menu > ul > li > a[href*="#"]').smoothScroll(
                    {easing: 'swing', speed: 700, offset: -50, excludeWithin: ['li.external']});
                $('#mobile-menu a[href*="#"]').smoothScroll(
                    {easing: 'swing', speed: 700, offset: 0, excludeWithin: ['li.external']});
            }


            /* --------- One Page Menu --------- */
            $('#primary-menu > ul > li > a[href*="#"]').click(function () {
                $(this).closest('ul').children('li').each(function () {
                    $(this).removeClass('active');
                });
                $(this).parent('li').addClass('active');
            });
            $('#primary-menu > ul > li > a[href*="#"]').each(function () {
                var current_div_selector = MO_THEME.get_internal_link($(this).attr('href')); // Give ids of div's with ids like #work,#service, #portfolio etc.

                $(current_div_selector).waypoint(function (direction) {
                        if (direction === "up") {
                            $('#primary-menu > ul > li').not(".hover-bg").each(function () {
                                $(this).removeClass('active');
                                if ($(this).find('a').attr('href').indexOf(current_div_selector) !== -1) {
                                    $(this).addClass('active');
                                    lavaLamp.moveLava($(this)[0]);// pass the list element to update lavaLamp menu highlight
                                }
                            });
                        }
                    }, { offset: function () {
                        var half_browser_height = $.waypoints('viewportHeight') / 2;
                        var element_height = $(this).height();
                        var result = 0;
                        if (element_height > half_browser_height) {
                            result = -( element_height - (half_browser_height)); // enable when top of the div is half exposed on the screen
                        }
                        else {
                            result = -(element_height / 2); // enable the menu when everything is visible
                        }
                        return result;
                    }
                    }
                );
                $(current_div_selector).waypoint(function (direction) {
                    if (direction === "down") {
                        $('#primary-menu > ul > li').not(".hover-bg").each(function () {
                            $(this).removeClass('active');
                            if ($(this).find('a').attr('href').indexOf(current_div_selector) !== -1) {
                                $(this).addClass('active');
                                lavaLamp.moveLava($(this)[0]);
                            }
                        });
                    }
                }, { offset: '50%' });
            });

        },

        init_menus: function () {
            /* For sticky and primary menu navigation */
            $('.dropdown-menu-wrap > ul').superfish({
                delay: 100, // one second delay on mouseout
                animation: {height: 'show'}, // fade-in and slide-down animation
                speed: 'fast', // faster animation speed
                autoArrows: false // disable generation of arrow mark-up
            });
            /* Hide all first and open only the top parent next */
            $('#mobile-menu-toggle').click(function () {
                $("#mobile-menu > ul").slideToggle(500);
                return false;
            });
            $("#mobile-menu ul li").each(function () {
                var sub_menu = $(this).find("> ul");
                if (sub_menu.length > 0 && $(this).addClass("has-ul")) {
                    $(this).find("> a").append('<span class="sf-sub-indicator"><i class="icon-arrow-down-3"></i></span>');
                }
            });
            $('#mobile-menu ul li:has(">ul") > a').click(function () {
                $(this).parent().toggleClass("open");
                $(this).parent().find("> ul").stop(true, true).slideToggle();
                return false;
            });

            this.init_page_navigation();
        },

        scroll_effects: function () {
            if (typeof $().waypoint === 'undefined') {
                return;
            }
            /*------- Load the feature showcase image with feature bubbles -----------*/


            $("#featured-app").waypoint(function (direction) {
                $('#featured-app .app-screenshot').addClass('visible');
                var delay = 1200;
                $('#feature-pointers img').each(function () {
                    $(this).delay(delay).animate({ opacity: 1}, 200);
                    delay = delay + 200;
                });
            }, { offset: $.waypoints('viewportHeight') - 300,
                triggerOnce: true});
        },

        smooth_page_load_effect: function () {
            $('#title-area .inner, #custom-title-area .inner, #content, .first-segment .heading2, .first-segment .heading1').css({opacity: 1});
            $('.sidebar-right-nav, .sidebar-left-nav').css({opacity: 1});
        },

        prettyPhoto: function () {

            if (typeof $().prettyPhoto === 'undefined') {
                return;
            }

            var theme_selected = 'pp_default';

            $("a[rel^='prettyPhoto']").prettyPhoto({
                "theme": theme_selected, /* light_rounded / dark_rounded / light_square / dark_square / facebook */
                social_tools: false
            });
        },

        toggle_state: function (toggle_element) {
            var active_class;
            var current_content;

            active_class = 'active-toggle';

            // close all others first
            toggle_element.siblings().removeClass(active_class);
            toggle_element.siblings().find('.toggle-content').slideUp("fast");

            current_content = toggle_element.find('.toggle-content');

            if (toggle_element.hasClass(active_class)) {
                toggle_element.removeClass(active_class);
                current_content.slideUp("fast");
            }
            else {
                toggle_element.addClass(active_class);
                current_content.slideDown("fast");
            }
        },

        validate_contact_form: function () {
            /* ------------------- Contact Form Validation ------------------------ */
            var rules = {
                contact_name: {
                    required: true,
                    minlength: 5
                },
                contact_email: {
                    required: true,
                    email: true
                },
                contact_phone: {
                    required: false,
                    minlength: 5
                },
                contact_url: {
                    required: false,
                    url: true
                },
                human_check: {
                    required: true,
                    range: [13, 13]
                },
                message: {
                    required: true,
                    minlength: 15
                }
            };
            var messages = {
                contact_name: {
                    required: mo_theme.name_required,
                    minlength: mo_theme.name_format
                },
                contact_email: mo_theme.email_required,
                contact_url: mo_theme.url_required,
                contact_phone: {
                    minlength: mo_theme.phone_required
                },
                human_check: mo_theme.human_check_failed,
                message: {
                    required: mo_theme.message_required,
                    minlength: mo_theme.message_format
                }
            };
            $("#content .contact-form").validate({
                rules: rules,
                messages: messages,
                errorClass: 'form-error',
                submitHandler: function (theForm) {
                    $.post(
                        theForm.action,
                        $(theForm).serialize(),
                        function (response) {
                            $("#content .feedback").html('<div class="success-msg">' + mo_theme.success_message + '</div>');
                            theForm.reset();
                        });
                }

            });
            $(".widget .contact-form").validate({
                rules: rules,
                messages: messages,
                errorClass: 'form-error',
                submitHandler: function (theForm) {
                    $.post(
                        theForm.action,
                        $(theForm).serialize(),
                        function (response) {
                            $(".widget .feedback").html('<div class="success-msg">' + mo_theme.success_message + '</div>');
                            theForm.reset();
                        });
                }

            });
        },

        imageOverlay: function () {
            $(".hfeed .post .image-area, .image-grid .image-area").hover(function () {
                $(this).find(".image-info").fadeTo(400, 1);
            }, function () {
                $(this).find(".image-info").fadeTo(400, 0);
            });
        }


    };

})(jQuery);

/*======================== Plugins ======================*/

(function ($) {

    "use strict";

    $.fn.lavaLamp = function (o) {

        o = $.extend({ fx: "easeOutBack", speed: 500, click: function () {
        } }, o || {});

        if (this.length === 0) {
            return null;
        }

        function setCurr(el) {
            hover_element.css({ left: el.offsetLeft + "px", width: el.offsetWidth + "px" });
            current = el;
        }

        function move(el) {
            hover_element.each(function () {
                    $.dequeue(this, "fx");
                }
            ).animate({
                    width: el.offsetWidth,
                    left: el.offsetLeft
                }, o.speed, o.fx);
        }

        var me = $(this), noop = function () {
            },
            hover_element = $('<li class="hover-bg"></li>').appendTo(me),
            list_element = $(">li", this),
            current = $("li.active", this)[0] || $(list_element[0]).addClass("active")[0];

        var delay = (function () {
            var timer = 0;
            return function (callback, ms) {
                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
        })();

        var moveLava = function (el) {
            if (el) {
                setCurr(el);
            }
            else {
                hover_element.css({left: current.offsetLeft + "px", width: current.offsetWidth + "px"});
            }
        };

        if (!(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))) {
            list_element.not(".hover-bg").hover(function () {
                move(this);
            }, noop);

            $(this).hover(noop, function () {
                move(current);
            });
        }

        list_element.click(function (e) {
            setCurr(this);
            return o.click.apply(this, [e, this]);
        });
        setCurr(current);

        $(window).resize(function () {
            delay(function () {
                hover_element.css({left: current.offsetLeft + "px", width: current.offsetWidth + "px"});
            }, 200);

        });

        return {
            moveLava: moveLava
        };

    };
})(jQuery);

/*======================== Document event handling ======================*/

jQuery(document).ready(function ($) {

    "use strict";

    /* -------------------------- Initialize document based on platform type -------------------- */

    MO_THEME.add_body_classes();

    /* ---------------------------------- Drop-down Menu.-------------------------- */

    MO_THEME.init_menus();

    /* --------- Back to top function ------------ */
    $(window).scroll(function () {
        MO_THEME.wait_for_final_event(function () {
            var width = $(window).width();
            var yPos = $(window).scrollTop();
            /* show back to top after screen has scrolled down 200px from the top in desktop and big size tablets only */
            if (width > 768 && (yPos > 200)) {
                if (!mo_options.disable_back_to_top) {
                    $("#go-to-top").fadeIn();
                }
            } else {
                $("#go-to-top").fadeOut();
            }
        }, 200, 'go-to-top');
    });


    // Animate the scroll to top
    $('#go-to-top').click(function (event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, 600);
    });

    /* ------------------- Scroll Effects ----------------------------- */


    if (!mo_options.disable_animations_on_page) {
        MO_THEME.scroll_effects();
    }
    else {
        // Show elements rightaway without animation
        $('#featured-app .app-screenshot').addClass('visible');
        $('#feature-pointers img').css({ opacity: 1});
    }


    if (!mo_options.disable_smooth_page_load) {
        MO_THEME.smooth_page_load_effect();
    }

    /* ------------------- Tabs and Accordions ------------------------ */

    $("ul.tabs").tabs(".pane");

    $(".accordion").tabs("div.pane", {
        tabs: 'div.tab',
        effect: 'slide',
        initialIndex: 0
    });

    /* ------------------- Back to Top and Close ------------------------ */

    $(".back-to-top").click(function (e) {
        $('html,body').animate({
            scrollTop: 0
        }, 600);
        e.preventDefault();
    });

    $('a.close').click(function (e) {
        e.preventDefault();
        $(this).closest('.message-box').fadeOut();
    });


    $(".toggle-label").toggle(
        function () {
            MO_THEME.toggle_state($(this).parent());
        },
        function () {
            MO_THEME.toggle_state($(this).parent());
        }
    );

    MO_THEME.validate_contact_form();
    /* -------------------------------- PrettyPhoto Lightbox --------------------------*/


    MO_THEME.prettyPhoto();

    /* -------------------------------- Image Overlay ------------------------------ */


    MO_THEME.imageOverlay();

    /*-----------------------------------------------------------------------------------*/
    /*	jQuery isotope functions and Infinite Scroll
     /*-----------------------------------------------------------------------------------*/

    $(function () {

        if (typeof $().isotope === 'undefined') {
            return;
        }

        var post_snippets = $('.post-snippets').not('.bx-wrapper .post-snippets');

        post_snippets.imagesLoaded(function () {
            post_snippets.isotope({
                // options
                itemSelector: '.entry-item',
                layoutMode: 'fitRows'
            });
        });

        var container = $('#portfolio-items');
        if (container.length === 0) {
            return;
        }

        container.imagesLoaded(function () {
            container.isotope({
                // options
                itemSelector: '.portfolio-item',
                layoutMode: 'fitRows'
            });

            $('#portfolio-filter a').click(function (e) {
                e.preventDefault();

                var selector = $(this).attr('data-value');
                container.isotope({ filter: selector });
                return false;
            });
        });

        if (mo_options.ajax_portfolio) {
            if (typeof $().infinitescroll !== 'undefined' && $('.pagination').length) {

                container.infinitescroll({
                        navSelector: '.pagination', // selector for the paged navigation
                        nextSelector: '.pagination .next', // selector for the NEXT link (to page 2)
                        itemSelector: '.portfolio-item', // selector for all items you'll retrieve
                        loading: {
                            msgText: mo_theme.loading_portfolio,
                            finishedMsg: mo_theme.finished_loading,
                            img: template_dir + '/images/loader.gif',
                            selector: '#main'
                        }
                    },
                    // call Isotope as a callback
                    function (newElements) {
                        MO_THEME.imageOverlay();
                        var $newElems = $(newElements);
                        $newElems.imagesLoaded(function () {
                            container.isotope('appended', $newElems);
                        });
                        MO_THEME.prettyPhoto();
                    });
            }
        }
    });

    /*-----------------------------------------------------------------------------------*/
    /*	Handle videos in responsive layout - Credit - http://css-tricks.com/NetMag/FluidWidthVideo/Article-FluidWidthVideo.php
     /*-----------------------------------------------------------------------------------*/

    $("#container").fitVids();

// Take care of maps too - https://github.com/davatron5000/FitVids.js - customSelector option
    $("#content").fitVids({ customSelector: "iframe[src^='http://maps.google.com/']"});
    $("#content").fitVids({ customSelector: "iframe[src^='https://maps.google.com/']"});

    /*var mo_twitter_id = mo_twitter_id || 'twitter';
     var mo_tweet_count = mo_tweet_count || 3;*/
    if (typeof mo_options.mo_twitter_id !== 'undefined') {
        jQuery('#twitter').jtwt({
            username: mo_options.mo_twitter_id,
            count: mo_options.mo_tweet_count,
            image_size: 32,
            loader_text: 'Loading Tweets'
        });
    }

    /*----------------- Parallax Effects - only on desktop ------------------ */

    var width = $(window).width();
    if (width > 1100) {
        $.stellar({
            horizontalScrolling: false,
            verticalOffset: 40,
            responsive: false
        });
    }

});