<?php
$wp_include = "../wp-load.php";
$i = 0;
while (!file_exists($wp_include) && $i++ < 10) {
    $wp_include = "../$wp_include";
}
//TODO - Get rid of these for speed. Use GET request to pass data to this file
require_once($wp_include);

function cs_validate_email($email) {
    /*
       (Name) Letters, Numbers, Dots, Hyphens and Underscores
       (@ sign)
       (Domain) (with possible subdomain(s) ).
       Contains only letters, numbers, dots and hyphens (up to 255 characters)
       (. sign)
       (Extension) Letters only (up to 10 (can be increased in the future) characters)
       */

    $regex = '/([a-z0-9_.-]+)' . # name

        '@' . # at

        '([a-z0-9.-]+){2,255}' . # domain & possibly subdomains

        '.' . # period

        '([a-z]+){2,10}/i'; # domain extension

    if ($email == '') {
        return false;
    }
    else {
        $eregi = preg_replace($regex, '', $email);
    }

    return empty($eregi) ? true : false;
}


$post = (!empty($_POST)) ? true : false;

if ($post) {
    $name = stripslashes($_POST['contact_name']);
    $contact_url = trim($_POST['contact_url']);
    $subject = $name;
    if (empty($contact_url))
        $subject .= __(' tried to reach you', 'mo_theme');
    else
        $subject .= __(' tried to reach you from ', 'mo_theme') . $contact_url;
    $email = trim($_POST['contact_email']);
    $to = trim($_POST['mail_to']);

    $message = '';


    if (!empty($contact_reason))
        $message .= "\n" . __('Name: ', 'mo_theme') . $name;
    $phone_number = trim($_POST['contact_phone']);
    if (!empty($phone_number))
        $message .= "\n\n" . __('Contact Number: ', 'mo_theme') . $phone_number;
    if (!empty ($email))
        $message .= "\n\n" . __('Contact Email: ', 'mo_theme') . $email;
    if (!empty($contact_url))
        $message .= "\n\n" . __('URL: ', 'mo_theme') . $contact_url;
    $contact_reason = trim($_POST['subject']);
    if (!empty($contact_reason))
        $message .= "\n\n" . __('Subject: ', 'mo_theme') . $contact_reason;

    $message .= "\n\n" . __('Contact Message: ', 'mo_theme') . stripslashes($_POST['message']);

    $error = '';

    // Check name

    if (!$name) {
        $error .= __('Please enter your name.', 'mo_theme') . '<br />';
    }

    // Check email

    if (!$email) {
        $error .= __('Please enter an e-mail address.', 'mo_theme') . '<br />';
    }

    if ($email && !cs_validate_email($email)) {
        $error .= __('Please enter a valid e-mail address.', 'mo_theme') . '<br />';
    }

    // Check message (length)

    if (!$message || strlen($message) < 15) {
        $error .= __('Please enter your message. It should have at least 15 characters.<br />', 'mo_theme');
    }

    if (!$error) // send email
    {
        $headers = 'From: ' . $name . ' <' . $email . '>' . "\n" . 'Reply-To: ' . $name . ' <' . $email . '>';
        $mail_sent = wp_mail($to, $subject, $message, $headers);

    }
}

?>