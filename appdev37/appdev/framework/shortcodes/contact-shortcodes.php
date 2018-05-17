<?php

/* Contact Form Shortcode -

Usage: Pls refer to https://www.livemeshthemes.com/appdev/contact-form-shortcode/

[contact_form mail_to="receipient@mydomain.com" phone=true web_url=true subject=true button_color="default"]

Parameters -

class - Custom CSS class name to be set for the div element created (optional)
mail_to - A string field specifying the recipient email where all form submissions will be received.
web_url - Can be true or false. A boolean indicating that the user should be requested for Web URL via an input field.
phone - Can be true or false. Request for phone number of the user. A phone number field is displayed.
subject - Can be true or false. A mail subject field is displayed if the value is set to true.
button_color - Color of the submit button. Available colors are black, blue, cyan, green, orange, pink, red, teal, theme and trans.

*/

if (!function_exists('mo_contact_form_shortcode')) {

    function mo_contact_form_shortcode($atts, $content = null, $code) {
        extract(shortcode_atts(array(
            'class' => '',
            'mail_to' => '',
            'web_url' => true,
            'phone' => true,
            'subject' => true,
            'human_check' => false,
            'button_color' => 'default'
        ), $atts));

        if (empty($mail_to))
            $mail_to = get_bloginfo('admin_email');
        $mail_script_url = MO_THEME_URL . '/framework/scripts/sendmail.php';

        $output = '<div class="feedback"></div>';

        $output .= '<form class="contact-form ' . $class . '" action="' . $mail_script_url . '" method="post">';

        $output .= '<fieldset>';

        $output .= '<p><label>' . __('Name *', 'mo_theme') . '</label><input type="text" name="contact_name" placeholder="' . __("Name:", 'mo_theme') . '" class="text-input" required></p>';

        $output .= '<p><label>' . __('Email *', 'mo_theme') . '</label><input type="email" name="contact_email" placeholder="' . __("Email:", 'mo_theme') . '" class="text-input" required></p>';

        if (mo_to_boolean($phone))
            $output .= '<p><label>' . __('Phone Number', 'mo_theme') . '</label><input type="tel" name="contact_phone" placeholder="' . __("Phone:", 'mo_theme') . '"  class="text-input"></p>';

        if (mo_to_boolean($web_url))
            $output .= '<p><label>' . __('Web URL', 'mo_theme') . '</label><input type="url" name="contact_url" placeholder="' . __("URL:", 'mo_theme') . '" class="text-input"></p>';

        if (mo_to_boolean($subject))
            $output .= '<p><label>' . __('Subject', 'mo_theme') . '</label><input type="text" name="subject" placeholder="' . __("Subject:", 'mo_theme') . '" class="text-input"></p>';

        $output .= '<p class="text-area"><label>' . __('Message *', 'mo_theme') . '</label><textarea name="message" placeholder="' . __("Message:", 'mo_theme') . '"  rows="6" cols="40"></textarea></p>';

        if (mo_to_boolean($human_check))
            $output .= '<p class="human-check"><label>' . __('* I am a human and 4 + 9 = ', 'mo_theme') . '</label><input type="text" name="human_check" class="text-input" size="5" style="width: 50px;"></p>';

        $output .= '<button type="submit" class="button large ' . $button_color . '">' . __('Send', 'mo_theme') . '<i class="send"></i></button>';

        $output .= '<input type="hidden" name="mail_to" value="' . $mail_to . '"/>';

        $output .= '</fieldset>';

        $output .= '</form>';

        return $output;
    }
}

add_shortcode('contact_form', 'mo_contact_form_shortcode');