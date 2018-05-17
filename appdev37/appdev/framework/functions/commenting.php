<?php
/**
 * Comment Template
 *
 * Credit - Awesome Hybrid Core Framework by Justin Tadlock - http://www.themehybrid.com
 *
 * @package Livemesh_Framework
 */

if (!function_exists('mo_entry_comments_link')) {
    function mo_entry_comments_link($args = array()) {

        $comments_link = '';
        $num_of_comments = doubleval(get_comments_number());
        $defaults = array('zero' => __('No Comments', 'mo_theme'), 'one' => __('%1$s Comment', 'mo_theme'), 'more' => __('%1$s Comments', 'mo_theme'), 'css_class' => 'comments-link', 'none' => '', 'before' => '', 'after' => '');

        /* Merge the input arguments and the defaults. */
        $args = wp_parse_args($args, $defaults);

        $comments_link = '<span class="' . esc_attr($args['css_class']) . '">';

        if (0 == $num_of_comments && !comments_open() && !pings_open()) {
            if ($args['none'])
                $comments_link .= sprintf($args['none'], number_format_i18n($num_of_comments));
        }
        elseif (0 == $num_of_comments)
            $comments_link .= '<a href="' . get_permalink() . '#respond" title="' . sprintf(esc_attr__('Comment on %1$s', 'mo_theme'), the_title_attribute('echo=0')) . '">' . sprintf($args['zero'], number_format_i18n($num_of_comments)) . '</a>';
        elseif (1 == $num_of_comments)
            $comments_link .= '<a href="' . get_comments_link() . '" title="' . sprintf(esc_attr__('Comment on %1$s', 'mo_theme'), the_title_attribute('echo=0')) . '">' . sprintf($args['one'], number_format_i18n($num_of_comments)) . '</a>';
        elseif (1 < $num_of_comments)
            $comments_link .= '<a href="' . get_comments_link() . '" title="' . sprintf(esc_attr__('Comment on %1$s', 'mo_theme'), the_title_attribute('echo=0')) . '">' . sprintf($args['more'], number_format_i18n($num_of_comments)) . '</a>';

        $comments_link .= '</span>';

        $comments_link = $args['before'] . $comments_link . $args['after'];

        return $comments_link;
    }
}

if (!function_exists('mo_entry_comments_number')) {

    function mo_entry_comments_number($args = array()) {
        $comments_text = '';
        $number = get_comments_number();
        $defaults = array('zero' => __('No Comments', 'mo_theme'), 'one' => __('%1$s Comment', 'mo_theme'), 'more' => __('%1$s Comments', 'mo_theme'), 'css_class' => 'comments-number', 'none' => '', 'before' => '', 'after' => '');

        /* Merge the input arguments and the defaults. */
        $args = wp_parse_args($args, $defaults);

        $comments_text = '<span class="' . esc_attr($args['css_class']) . '"><i class="icon-bubbles"></i>';

        if (0 == $number && !comments_open() && !pings_open()) {
            if ($args['none'])
                $comments_text .= sprintf($args['none'], number_format_i18n($number));
        }
        elseif ($number == 0)
            $comments_text .= sprintf($args['zero'], number_format_i18n($number));
        elseif ($number == 1)
            $comments_text .= sprintf($args['one'], number_format_i18n($number));
        elseif ($number > 1)
            $comments_text .= sprintf($args['more'], number_format_i18n($number));

        $comments_text .= '</span>';

        if ($comments_text)
            $comments_text = $args['before'] . $comments_text . $args['after'];

        return $comments_text;
    }
}

if (!function_exists('mo_comment_author')) {
    function mo_comment_author() {
        global $comment;

        $author = esc_html(get_comment_author($comment->comment_ID));
        $url = esc_url(get_comment_author_url($comment->comment_ID));

        /* Display link and cite if URL is set. Also, properly cites trackbacks/pingbacks. */
        if ($url)
            $output = '<cite class="fn" title="' . $url . '"><a href="' . $url . '" title="' . esc_attr($author) . '" class="url" rel="external nofollow">' . $author . '</a></cite>';
        else
            $output = '<cite class="fn">' . $author . '</cite>';

        $output = '<span class="comment-author vcard">' . apply_filters('get_comment_author_link', $output) . '</span><!-- .comment-author .vcard -->';

        return $output;
    }

}

if (!function_exists('mo_comment_published')) {

    /**
     * Provides the published date and time of an individual comment.
     */
    function mo_comment_published() {
        $link = '<span class="published">' . sprintf(__('%1$s at %2$s', 'mo_theme'), '<abbr class="comment-date" title="' . get_comment_date(esc_attr__('l, F jS, Y, g:i a', 'mo_theme')) . '">' . get_comment_date() . '</abbr>', '<abbr class="comment-time" title="' . get_comment_date(esc_attr__('l, F jS, Y, g:i a', 'mo_theme')) . '">' . get_comment_time() . '</abbr>') . '</span>';
        return $link;
    }
}

if (!function_exists('mo_comment_reply_link')) {

    /**
     * Displays a reply link for the 'comment' comment_type if threaded comments are enabled.
     */
    function mo_comment_reply_link() {

        if (!get_option('thread_comments') || 'comment' !== get_comment_type())
            return '';

        $args = array(
            'reply_text' => __('Reply', 'mo_theme'),
            'login_text' => __('Log in to reply.', 'mo_theme'),
            'depth' => intval($GLOBALS['comment_depth']),
            'max_depth' => get_option('thread_comments_depth'),
            'before' => ''
        );

        return get_comment_reply_link($args);
    }
}

if (!function_exists('mo_comment_edit_link')) {

    /**
     * Displays a comment's edit link to help logged in users to edit the comments.
     */
    function mo_comment_edit_link() {
        global $comment;

        $edit_link = get_edit_comment_link($comment->comment_ID);

        if (!$edit_link)
            return '';

        $before = '';
        $after = '';

        $link = '<a class="comment-edit-link" href="' . esc_url($edit_link) . '" title="' . sprintf(esc_attr__('Edit %1$s', 'mo_theme'), $comment->comment_type) . '"><span class="edit">' . __('Edit', 'mo_theme') . '</span></a>';
        $link = apply_filters('edit_comment_link', $link, $comment->comment_ID);

        return $before . $link . $after;
    }

}

if (!function_exists('mo_list_comments_args')) {

    function mo_list_comments_args() {

        /* Set the default arguments for listing comments. */
        $args = array(
            'style' => 'ol',
            'type' => 'all',
            'avatar_size' => 80,
            'callback' => 'mo_comments_callback',
            'end-callback' => 'mo_comments_end_callback'
        );

        return $args;
    }
}

if (!function_exists('mo_comments_callback')) {
    function mo_comments_callback($comment, $args, $depth) {

        global $post;

        $GLOBALS['comment'] = $comment;
        $GLOBALS['comment_depth'] = $depth;
        ?>

    <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>

        <div class="comment-wrap clearfix">

            <div class="avatar-wrap">
                <?php echo mo_avatar(); ?>
            </div>

            <div class="comment-arrow"></div>

            <div class="comment-box-wrap">


                <div class="comment-meta-section">

                    <div class="comment-meta"><?php echo mo_comment_author(); ?>
                        <div
                            class="comment-byline"><?php echo(mo_comment_published() . mo_comment_edit_link() . mo_comment_reply_link()); ?></div>
                    </div>

                </div>
                <div class="comment-text-wrap">

                    <div class="entry-content comment-text">

                        <?php if ($comment->comment_approved == '0') : ?>
                            <?php echo '<p class="alert moderation">' . __('Your comment is awaiting moderation.', 'mo_theme') . '</p>'; ?>
                        <?php endif; ?>

                        <?php comment_text($comment->comment_ID); ?>

                    </div>

                </div>
            </div>
            <!-- comment-box-wrap -->

        </div>
        <!-- .comment-wrap -->

        <?php /* No closing </li> is needed.  WordPress will know where to add it. */

    }
}

if (!function_exists('mo_comments_end_callback')) {

    function mo_comments_end_callback() {
        echo '</li><!-- .comment -->';
    }
}

if (!function_exists('mo_avatar')) {

    function mo_avatar() {
        global $comment;

        /* Make sure avatars are allowed before proceeding. */
        if (!get_option('show_avatars'))
            return false;

        /* Get/set some comment variables. */
        $comment_type = get_comment_type($comment->comment_ID);
        $author = get_comment_author($comment->comment_ID);
        $url = get_comment_author_url($comment->comment_ID);
        $avatar = '';
        $default_avatar = '';

        $comment_list_args = mo_list_comments_args();

        /* Get the avatar provided by the get_avatar() function. */
        $avatar = get_avatar($comment, $comment_list_args['avatar_size']);

        /* If URL input, wrap avatar in hyperlink. */
        if (!empty($url) && !empty($avatar))
            $avatar = '<a href="' . esc_url($url) . '" rel="external nofollow" title="' . esc_attr($author) . '">' . $avatar . '</a>';

        echo $avatar;
    }
}