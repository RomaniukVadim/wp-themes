<?php
/* Subscribe to RSS feed */

/* Subscribe RSS Shortcode -

Displays a box that lets users invite users to subscribe to the RSS feed for the blog.

Usage:

[subscribe_rss]

Parameters -

None

*/

function mo_subscribe_rss() {
    $feed_url = home_url() . '/feed';
    return '<div class="rss-block">Like what you see? <a href="' . $feed_url . '">Subscribe to RSS feed</a> to receive updates!</div>';

}

add_shortcode('subscribe_rss', 'mo_subscribe_rss');

/* Social List Shortcode -

Displays a list of social icons with links to various social network pages.
Usage:

[social_list googleplus_url="http://plus.google.com" facebook_url="http://www.facebook.com" twitter_url="http://www.twitter.com" youtube_url="http://www.youtube.com/" linkedin_url="http://www.linkedin.com" include_rss="true"]

Parameters -

email - The email address to be used.
facebook_url - The URL of the Facebook page.
twitter_url - The URL of the Twitter account.
flickr_url - The URL of the Flickr page.
youtube_url - The URL for the YoutTube channel.
linkedin_url - The URL for the LinkedIn profile.
googleplus_url - The URL for the Google Plus page.
include_rss - A boolean value(true/false string) indicating that the link to the RSS feed be included. Default is false.
align - Can be left, right, center or none. The default is left. (optional).

*/

function mo_social_list_shortcode($atts, $content = null, $code) {
    extract(shortcode_atts(array(
        'facebook_url' => false,
        'twitter_url' => false,
        'flickr_url' => false,
        'youtube_url' => false,
        'linkedin_url' => false,
        'googleplus_url' => false,
        'include_rss' => false,
        'align' => 'left'
    ), $atts));


    $output = '<ul class="social-list clearfix';
    if ($align == 'center')
        $output .= ' center';
    $output .= '">';

    if ($facebook_url)
        $output .= '<li><a class="facebook" href="' . $facebook_url . '" target="_blank" title="Follow on Facebook">Facebook</a></li>';
    if ($twitter_url)
        $output .= '<li><a class="twitter" href="' . $twitter_url . '" target="_blank" title="Subscribe to Twitter Feed">Twitter</a></li>';
    if ($flickr_url)
        $output .= '<li><a class="flickr" href="' . $flickr_url . '" target="_blank" title="View Flickr Portfolio">Flickr</a></li>';
    if ($youtube_url)
        $output .= '<li><a class="youtube" href="' . $youtube_url . '" target="_blank" title="Subscribe to the YouTube channel">YoutTube</a></li>';
    if ($linkedin_url)
        $output .= '<li><a class="linkedin" href="' . $linkedin_url . '" target="_blank" title="View LinkedIn Profile">LinkedIn</a></li>';
    if ($googleplus_url)
        $output .= '<li><a class="googleplus" href="' . $googleplus_url . '" target="_blank" title="Follow on Google Plus">Google Plus</a></li>';

    if ($include_rss) {
        $rss = get_bloginfo('rss2_url');
        $output .= '<li><a class="rss" href="' . $rss . '" target="_blank" title="Subscribe to our RSS Feed">RSS</a></li>';
    }

    $output .= '</ul>';

    return $output;

}

add_shortcode('social_list', 'mo_social_list_shortcode');

/*------- Paypal Donate Button - http://blue-anvil.com/archives/8-fun-useful-shortcode-functions-for-wordpress/ ----*/

/* Paypal Donate Shortcode -

Lets you display a Paypal donate button wherever you need - inside a post or a page.

Usage:

[donate title="Please Donate to John Smith Foundation" account="email@example.com" display_card_logos="true" cause="Earthquake Relief"]

Parameters -

title - The title of the link that displays the Paypal donate button.
account - The Paypal account for which the donate button is being created.
display_card_logos - A boolean value to specify display of the logo images of the credit cards accepted for Paypal donations
cause - The text indicating the purpose for which the donation is being collected.

*/

function mo_paypal_donate_shortcode($atts) {
    extract(shortcode_atts(array(
        'title' => 'Make a donation',
        'account' => 'REPLACE ME',
        'cause' => '',
        'display_card_logos' => "Yes",
    ), $atts));

    $display_card_logos = mo_to_boolean($display_card_logos);

    global $post;

    if (!$cause)
        $cause = str_replace(" ", "+", $post->post_title);

    if ($display_card_logos)
        $class = 'donate-button-plus';
    else
        $class = 'donate-button';

    return '<a class="' . $class . '" href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=' . $account . '&item_name=Donation+for+' . urlencode($cause) . '" title="' . $title . '"></a>';

}

add_shortcode('donate', 'mo_paypal_donate_shortcode');
/*------ Credit - https://github.com/jeherve/jp-sd-shortcode. Enable JetPack sharing module to use this shortcode. ----*/

if (!function_exists('tweakjp_sd_shortcode')) {
    function tweakjp_sd_shortcode() {
        if (class_exists('Jetpack') && method_exists('Jetpack', 'get_active_modules') && in_array('sharedaddy', Jetpack::get_active_modules()))
            return sharing_display();
    }
}

add_shortcode('jpshare', 'tweakjp_sd_shortcode');