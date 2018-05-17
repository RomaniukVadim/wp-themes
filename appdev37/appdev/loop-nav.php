<?php
/**
 * Loop Nav Template
 *
 * This template is used to show your your next/previous post links on singular pages and
 * the next/previous posts links on the home/posts page and archive pages.
 *
 * @package Appdev
 * @subpackage Template
 */
?>
<?php if (is_attachment()) : ?>

    <div class="loop-nav">
        <?php previous_post_link('<div class="previous">' . __('Return to ', 'mo_theme') . '%link</div>'); ?>
    </div><!-- .loop-nav -->


<?php elseif (is_singular('portfolio')) : ?>

    <div class="loop-nav">
        <?php previous_post_link('<div class="previous">&larr; ' . '%link' . '</div>', '<span class="portfolio-nav">' . __('Previous Project', 'mo_theme') . '</span>'); ?>
        <?php next_post_link('<div class="next">&rarr; ' . '%link' . '</div>', '<span class="portfolio-nav">' . __('Next Project', 'mo_theme') . '</span>'); ?>
    </div><!-- .loop-nav -->

<?php
elseif (is_singular('post')) : ?>

    <div class="loop-nav">
        <?php previous_post_link('<div class="previous">&larr; ' . __('%link', 'mo_theme') . '</div>', '%title'); ?>
        <?php next_post_link('<div class="next">&rarr; ' . __('%link', 'mo_theme') . '</div>', '%title'); ?>
    </div><!-- .loop-nav -->

<?php
elseif (mo_is_portfolio_context()) :
    mo_loop_pagination(array('prev_text' => '<i class="icon-arrow-left-2"></i>' . '', 'next_text' => '' . '<i class="icon-arrow-right-3"></i>')); ?>

<?php
elseif (!is_singular()) :
    mo_loop_pagination(array('prev_text' => '<i class="icon-arrow-left-2"></i>' . '', 'next_text' => '' . '<i class="icon-arrow-right-3"></i>')); ?>

<?php
elseif (!is_singular() && $nav = get_posts_nav_link(array('sep' => '', 'prelabel' => '<div class="previous">' . __('Previous Page', 'mo_theme') . '</div>', 'nxtlabel' => '<div class="next">' . __('Next Page', 'mo_theme') . '</div>'))) : ?>

    <div class="loop-nav">
        <?php echo $nav; ?>
    </div><!-- .loop-nav -->

<?php endif; ?>