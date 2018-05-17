<?php

/* Code Shortcode -

Usage: Wraps the content in HTML tag pre with required class name to enable custom theme styling for source code.

[code]

[one_half]Content[/one_half]

[one_half_last]Another content[/one_half_last]

[/code]

Parameters -

None


*/
function mo_sourcecode_shortcode($atts, $content = null, $shortcode_name = "") {
    return '<pre class="code">' . $content . '</pre>';
}

add_shortcode('code', 'mo_sourcecode_shortcode');

/* Shortcodes for dropcaps. Four types of dropcaps supported with multiple colors*/
function mo_dropcap_shortcode($atts, $content = null, $shortcode_name = "") {

    extract(shortcode_atts(array('color' => false, 'text_color' => false, 'style' => ''), $atts));

    if (isset($shortcode_name))
        $style_class = $shortcode_name;

    if (in_array($shortcode_name, array('dropcap2', 'dropcap3', 'dropcap4', 'dropcap5'))) {
        $style .= $color ? 'background-color:' . $color . ';' : '';
    }

    $style .= $text_color ? 'color:' . $text_color . ';' : '';

    if (!empty($style))
        $style = ' style="' . $style . '"';

    $dropcap_code = '<span class="' . $style_class . '"' . $style . '>' . do_shortcode($content) . '</span>';

    return $dropcap_code;

}

add_shortcode('dropcap1', 'mo_dropcap_shortcode');
add_shortcode('dropcap2', 'mo_dropcap_shortcode');
add_shortcode('dropcap3', 'mo_dropcap_shortcode');
add_shortcode('dropcap4', 'mo_dropcap_shortcode');
add_shortcode('dropcap5', 'mo_dropcap_shortcode');


/* Pullquote Shortcodes -

Usage:

[pullquote align="right"]Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.[/pullquote]

Parameters -

align - Can be left, right, center or none. The default is none. (optional).
author - A string indicating author information. (optional)

*/

/* Shortcode for pull quotes with optional alignment = left or right or none */
function mo_pullquote_shortcode($atts, $content = null, $shortcode_name = "") {

    extract(shortcode_atts(array('align' => 'none', 'author' => false,), $atts));

    $pullquote_code = '<div class="quote-wrap align' . $align . '"><div class="' . $shortcode_name . '">' . do_shortcode($content) . '</div></div>';

    return $pullquote_code;

}

add_shortcode('pullquote', 'mo_pullquote_shortcode');

/* Blockquote Shortcode -

Usage:

[blockquote align="right" author="Tom Bodett"]They say a person needs just three things to be truly happy in this world: someone to love, something to do, and something to hope for.[/blockquote]

Parameters -

align - Can be left, right, center or none. The default is none. (optional).
id - The element id to be set for the blockquote element created (optional).
style - Inline CSS styling applied for the blockquote element created (optional)
class - Custom CSS class name to be set for the blockquote element created (optional)
author - A string value indicating the author of the quote.
affiliation - The entity to which the author of the quote belongs to.
affiliation_url - The URL of the entity to which this quote is attributed to.

*/

/* Shortcode for blockquotes with optional alignment = left or right and citation attributes*/
function mo_blockquote_shortcode($atts, $content = null, $shortcode_name = "") {

    extract(shortcode_atts(array('align' => 'none', 'author' => false, 'affiliation' => false, 'affiliation_url' => false, 'id' => '', 'class' => '', 'style' => ''), $atts));

    if (!empty($id))
        $id = ' id="' . $id . '"';
    if (!empty($class))
        $class = ' ' . $class;
    if (!empty($style))
        $style = ' style="' . $style . '"';

    $author_info = '';

    if ($author || $affiliation) {
        $author_info = '<p class="author">- ';
        $author_info .= $author ? $author : '';
        $author_info .= $affiliation ? ', ' : '';
        if ($affiliation && $affiliation_url)
            $author_info .= '<a href="' . $affiliation_url . '" title="' . $affiliation . '">' . $affiliation . '</a>';
        elseif ($affiliation)
            $author_info .= $affiliation;

        $author_info .= '</p>';
    }

    $output = '<blockquote ' . $id . ' class="align' . $align . $class . '"' . $style . '>' . $content . $author_info . '</blockquote>';

    return $output;

}

add_shortcode('blockquote', 'mo_blockquote_shortcode');

/* Highlight Shortcodes -

Highlights the text wrapped by the shortcode. Useful for highlighting text. Has two variations - highlight1 and highlight2.

Usage:

[highlight1]Lorem ipsum dolor sit amet, consetetur[/highlight1]

[highlight2]Lorem ipsum dolor sit amet, consetetur[/highlight2]

Parameters -

None


*/

/* Shortcode for highlighting text within the content */
function mo_highlight_shortcode($atts, $content = null, $shortcode_name = "") {

    $output = '<span class="' . $shortcode_name . '">' . do_shortcode($content) . '</span>';

    return $output;

}

add_shortcode('highlight1', 'mo_highlight_shortcode');
add_shortcode('highlight2', 'mo_highlight_shortcode');

/* List Shortcode -

A shortcode to create a styled unordered list element UL.

Usage:

[list]

<li>Item 1</li>
<li>Item 2</li>

[/list]

Parameters -

style - Inline CSS styling applied for the UL element created (optional)
type - Custom CSS class name to be set for the UL element created (optional). Possible values are from list1, list2, list3 to list10. Default is list1.


*/

function mo_list_shortcode($atts, $content = null) {
    extract(shortcode_atts(array('style' => '', 'type' => 'list1'), $atts));

    $list_content = do_shortcode($content);

    if (!empty($style))
        $style = ' style="' . $style . '"';


    $styled_list = '<ul class="' . $type . '"' . $style . '>';

    $output = str_replace('<ul>', $styled_list, $list_content);

    return $output;
}

add_shortcode('list', 'mo_list_shortcode');

/* Heading Shortcodes -

Heading shortcodes are used across all pages in the theme as introductory texts/titles to the page sections.

Usage:

[heading2
title="Connect with us on our <strong>blog</strong>"
pitch="Lorem ipsum dolor sit amet, consectetuer elit. Aenean leo ligula, porttitor eu, consequat vitae. Sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat"]
Parameters -

title - A string value indicating the title of the heading.
pitch - The text displayed below the heading title.
separator - boolean (false) - Specify if a separator needs to be inserted between the title and the pitch text (optional)

*/
function mo_heading2_shortcode($atts, $content = null) {
    extract(shortcode_atts(array(
            'class' => '',
            'style' => '',
            'title' => '',
            'separator' => false,
            'pitch' => ''),
        $atts));

    if (!empty($style))
        $style = ' style="' . $style . '"';
    if (!empty($class))
        $class = ' ' . $class;
    $output = '<div class="heading2' . $class . '"' . $style . '>';
    if (!empty ($title))
        $output .= '<h2 class="title">' . $title . '</h2>';
    if (mo_to_boolean($separator))
        $output .= '<div class="mini-separator center"></div>';
    if (!empty ($pitch))
        $output .= '<p class="pitch">' . html_entity_decode($pitch) . '</p>';

    $output .= do_shortcode($content);

    $output .= '</div>';

    return $output;
}

add_shortcode('heading2', 'mo_heading2_shortcode');

/* Segment Shortcode -

Usage:

[segment]

[one_half]Content[/one_half]

[one_half_last]Another content[/one_half_last]

[/segment]

Parameters -

id - The element id to be set for the div element created (optional).
style - Inline CSS styling applied for the div element created (optional)
class - Custom CSS class name to be set for the div element created (optional)


*/
function mo_segment_shortcode($atts, $content = null) {
    extract(shortcode_atts(array(
            'id' => '',
            'class' => '',
            'style' => '',
            'background_pattern' => '',
            'background_color' => '',
            'background_image' => '',
            'parallax_background' => 'true',
            'background_speed' => '0.5'),
        $atts));

    if ($id)
        $id = 'id="' . $id . '"';

    if (!empty($style) || !empty ($background_image) || !empty($background_color) || !empty($background_pattern)) {
        $inline_style = ' style="';
        $parallax_markup = '';
        if (!empty($background_image)) {
            $inline_style .= 'background-image:url(' . $background_image . '); background-color:' . $background_color . ';';
            if ($parallax_background == 'true') {
                $inline_style .= 'background-attachment:fixed;';
                $parallax_markup = ' data-stellar-background-ratio="' . $background_speed . '"';
            }
        }
        elseif (!empty($background_pattern)) {
            $inline_style .= 'background:url(' . $background_pattern . ') repeat scroll left top ' . $background_color . ';';
        }
        elseif (!empty($background_color)) {
            $inline_style .= 'background-color:' . $background_color . ';';
        }
        $inline_style .= $style . '"'; // let the style override what we specify above using background shorthand
        $output = '<div ' . $id . $parallax_markup . ' class="segment clearfix ' . $class . '" ' . $inline_style . '>';
        $output .= '<div class="segment-content">' . do_shortcode(mo_remove_wpautop($content)) . '</div>';
        $output .= '</div><!-- .segment-->';
    }
    else {
        $output = '<div ' . $id . ' class="segment clearfix ' . $class . '"><div class="segment-content">' . do_shortcode(mo_remove_wpautop($content)) . '</div></div><!-- .segment-->';
    }

    return $output;
}

add_shortcode('segment', 'mo_segment_shortcode');

/* Wrap Shortcodes -

This shortcode is used to create a DIV wrapper elements for other shortcodes.

Helps to display these elements in the visual editor of WordPress. The regular DIV elements entered in the HTML tab are not visible in the visual editor which leads to mistakes and lost/malformed elements.

Usage:

[ancestor_wrap class="marketing-ancestor"]

[parent_wrap id="marketing-parent"]

[wrap class="marketing-section"]

[one_half]Content[/one_half]

[one_half_last]Another content[/one_half_last]

[/wrap]

[/parent_wrap]

[/ancestor_wrap]

Each of the wrapper shortcodes accept the following parameters

Parameters -

id - The element id to be set for the DIV element created (optional).
style - Inline CSS styling applied for the DIV element created (optional)
class - Custom CSS class name to be set for the DIV element created (optional)


*/

/* Shortcode for wrapping markup as visible in the visual editor. */
function mo_wrap_shortcode($atts, $content = null, $shortcode_name = "") {

    extract(shortcode_atts(array('id' => false, 'class' => false, 'style' => ''), $atts));


    $id = empty($id) ? '' : ' id="' . $id . '"';
    $class = empty($class) ? '' : ' class="' . $class . '"';
    $style = empty($style) ? '' : ' style="' . $style . '"';

    return '<div' . $id . $class . $style . '>' . do_shortcode($content) . '</div>';
}

add_shortcode('wrap', 'mo_wrap_shortcode');
add_shortcode('parent_wrap', 'mo_wrap_shortcode');
add_shortcode('ancestor_wrap', 'mo_wrap_shortcode');

/* Icon Shortcode -

Shortcode to display one of the font icons, chosen from the list of icons listed at https://www.livemeshthemes.com/support/faqs/how-to-use-500-icon-fonts-bundled-with-the-theme/

Usage:

[icon class="icon-cart" style="font-size:32px;"]

[icon class="icon-thumbnails style="font-size:48px;"]

Parameters -

style - Inline CSS styling applied for the icon element created (optional). Useful if you want to specify font-size, color etc. for the icon inline.
class - Custom CSS class name to be set for the icon element created (optional)


*/

/* Shortcode for wrapping markup as visible in the visual editor. */
function mo_icon_shortcode($atts, $content = null, $shortcode_name = "") {

    extract(shortcode_atts(array('class' => false, 'style' => ''), $atts));

    $class = empty($class) ? '' : ' class="icon ' . $class . '"';
    $style = empty($style) ? '' : ' style="' . $style . '"';

    return '<i' . $class . $style . '></i>';
}

add_shortcode('icon', 'mo_icon_shortcode');


/* Wrap Shortcodes -

This shortcode is used to create a DIV wrapper elements for other shortcodes.

Helps to display these elements in the visual editor of WordPress. The regular DIV elements entered in the HTML tab are not visible in the visual editor which leads to mistakes and lost/malformed elements.

Usage:

Each of the wrapper shortcodes accept the following parameters

Parameters -

id - The element id to be set for the DIV element created (optional).
style - Inline CSS styling applied for the DIV element created (optional)
class - Custom CSS class name to be set for the DIV element created (optional)


*/

/* Shortcode for wrapping markup as visible for the dumb visual editor. */
function mo_dummy_shortcode($atts, $content = null, $shortcode_name = "") {

    extract(shortcode_atts(array('id' => false, 'class' => false, 'style' => ''), $atts));


    $id = empty($id) ? '' : ' id="' . $id . '"';
    $class_name = str_replace('_', '-', $shortcode_name);
    $class = ' class="' . $class_name . ' ' . $class . '"';
    $style = empty($style) ? '' : ' style="' . $style . '"';

    return '<div' . $id . $class . $style . '>' . do_shortcode($content) . '</div>';
}

add_shortcode('pricing_table', 'mo_dummy_shortcode');
/* Action Call Shortcode -

Useful to create action call segments which typically display a text urging the user to take action and a button which leads to the action.

Usage:

[action_call text="Ready to get started <strong>on your project?</strong></h3>" button_url="http://themeforest.net/user/LiveMesh" button_text="Purchase Now"]

[/code]

Parameters -

text - Text to be displayed urging for an action call.
button_text - The title to be displayed for the button.
button_color - The color of the button. Available colors are black, blue, cyan, green, orange, pink, red, teal, theme and trans.
button_url - The URL to which the button links to. The user navigates to this URL when the button is clicked.

*/
/* Shortcode for wrapping markup as visible for the dumb visual editor. */
function mo_action_call_shortcode($atts, $content = null, $shortcode_name = "") {

    extract(
        shortcode_atts(
            array('text' => false,
                'button_text' => false,
                'button_url' => '',
                'button_color' => 'theme'),
            $atts));

    $output = '<div class="action-call clearfix">';

    $output .= '<div class="eightcol">';

    $output .= '<h3>' . htmlspecialchars_decode($text) . '</h3>';

    $output .= '</div>';

    $output .= '<div class="fourcol last">';

    $output .= '<a class="button ' . $button_color . '" href="' . $button_url . '" target="_self">' . $button_text . '</a>';

    $output .= '</div>';

    $output .= '</div>';

    return $output;
}

add_shortcode('action_call', 'mo_action_call_shortcode');