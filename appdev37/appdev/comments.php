<?php
/**
 * Comments Template
 *
 * Lists comments and calls the comment form.  Individual comments have their own templates.  The
 * hierarchy for these templates is $comment_type.php, comment.php.
 *
 * @package Appdev
 * @subpackage Template
 */
/* Kill the page if trying to access this template directly. */

if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die(__('This page is not supposed to be loaded directly. Thanks!', 'mo_theme'));

/* If a post password is required or no comments are given and comments/pings are closed, return. */
if (post_password_required() || (!have_comments() && !comments_open() && !pings_open()))
    return;
?>

<div id="comments-template">

    <div class="comments-wrap">

        <?php if (have_comments()) : ?>

        <div id="comments">

            <h3 id="comments-number"
                class="comments-header"><?php comments_number(esc_html__('No&nbsp;Comments', 'mo_theme'), '<span class="number">1</span>&nbsp;' . esc_html__('Comment', 'mo_theme'), '<span class="number">%</span>&nbsp;' . esc_html__('Comments', 'mo_theme')); ?></h3>

            <ol class="comment-list">
                <?php wp_list_comments(mo_list_comments_args()); ?>
            </ol>
            <!-- .comment-list -->

            <?php if (get_option('page_comments')) : ?>
            <div class="comment-navigation comment-pagination">
                <?php paginate_comments_links(); ?>
            </div><!-- .comment-navigation -->
            <?php endif; ?>

        </div><!-- #comments -->

        <?php else : ?>

        <?php if (pings_open() && !comments_open()) : ?>

            <p class="comments-closed pings-open">
                <?php echo __('Comments are closed, but trackbacks and pingbacks are open.', 'mo_theme'); ?>
            </p><!-- .comments-closed .pings-open -->

            <?php elseif (!comments_open()) : ?>

            <p class="comments-closed">
                <?php _e('Comments are closed.', 'mo_theme'); ?>
            </p><!-- .comments-closed -->

            <?php endif; ?>

        <?php endif; ?>

        <?php comment_form(array('title_reply' => __('Leave a Comment', 'mo_theme'), 'title_reply_to' => __('Leave a Comment to %s', 'mo_theme'), 'cancel_reply_link' => __('Cancel comment', 'mo_theme'))); // Loads the comment form.  ?>

    </div>
    <!-- .comments-wrap -->

</div><!-- #comments-template -->

<?php 

