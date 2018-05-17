<?php

/**
 * Loop Meta Template
 *
 * Displays information at the top of the page about archive and search results when viewing those pages.  
 * This is not shown on the home page and singular views.
 *
 * @package Appdev
 * @subpackage Template
 */
function mo_get_date_text() {

    if (!is_date())
        return;
    $text = '';
    if (is_day())
        $text = get_the_time('j M,Y');
    elseif (is_month())
        $text = get_the_time('M,Y');
    elseif (is_year())
        $text = get_the_time('Y');

    return $text;
}
?>

<?php if (is_single()):
    return; ?>

<?php elseif (is_category()) : ?>

    <div class="loop-meta">

        <h1 class="loop-title"><?php single_cat_title(); ?></h1>

        <div class="loop-description">
            <?php echo category_description(); ?>
        </div><!-- .loop-description -->

    </div><!-- .loop-meta -->

<?php elseif (is_tag()) : ?>

    <div class="loop-meta">

        <h1 class="loop-title"><?php single_tag_title(); ?></h1>

        <div class="loop-description">
            <?php echo tag_description(); ?>
        </div><!-- .loop-description -->

    </div><!-- .loop-meta -->

<?php elseif (is_tax()) : ?>

    <div class="loop-meta">

        <h1 class="loop-title"><?php $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
    echo $term->name; ?></h1>

        <div class="loop-description">
            <?php echo term_description('', get_query_var('taxonomy')); ?>
        </div><!-- .loop-description -->

    </div><!-- .loop-meta -->

<?php elseif (is_author()) : ?>

    <?php $id = get_query_var('author'); ?>

    <div id="hcard-<?php the_author_meta('user_nicename', $id); ?>" class="loop-meta vcard">

        <h1 class="loop-title fn n"><?php the_author_meta('display_name', $id); ?></h1>

        <div class="loop-description">
            <?php echo get_avatar(get_the_author_meta('user_email', $id), '50', '', get_the_author_meta('display_name', $id)); ?>

            <p class="user-bio">
                <?php the_author_meta('description', $id); ?>
            </p><!-- .user-bio -->
        </div><!-- .loop-description -->

    </div><!-- .loop-meta -->

<?php elseif (is_search()) : ?>

    <div class="loop-meta">

        <h1 class="loop-title"><?php echo esc_attr(get_search_query()); ?></h1>

        <div class="loop-description">
            <p>
                <?php printf(__('You are browsing the search results for &quot;%1$s&quot;', 'mo_theme'), esc_attr(get_search_query())); ?>
            </p>
        </div><!-- .loop-description -->

    </div><!-- .loop-meta -->

<?php elseif (is_date()) : ?>

    <div class="loop-meta">
        <h1 class="loop-title"><?php _e('Archives for ' . mo_get_date_text(), 'mo_theme'); ?></h1>

        <div class="loop-description">
            <p>
                <?php _e('You are browsing the site archives by date.', 'mo_theme'); ?>
            </p>
        </div><!-- .loop-description -->

    </div><!-- .loop-meta -->

<?php elseif (function_exists('is_post_type_archive') && is_post_type_archive()) : ?>

    <?php $post_type = get_post_type_object(get_query_var('post_type')); ?>

    <div class="loop-meta">

        <h1 class="loop-title"><?php echo $post_type->labels->name; ?></h1>

        <div class="loop-description">
            <?php if (!empty($post_type->description))
                echo "<p>{$post_type->description}</p>"; ?>
        </div><!-- .loop-description -->

    </div><!-- .loop-meta -->

<?php elseif (is_archive()) : ?>

    <div class="loop-meta">

        <h1 class="loop-title"><?php _e('Archives', 'mo_theme'); ?></h1>

        <div class="loop-description">
            <p>
                <?php _e('You are browsing the site archives.', 'mo_theme'); ?>
            </p>
        </div><!-- .loop-description -->

    </div><!-- .loop-meta -->

<?php elseif (is_page_template('template-popular-posts.php')) : ?>

    <div class="loop-meta">

        <h1 class="loop-title"><?php _e('Most Popular', 'mo_theme'); ?></h1>

        <div class="loop-description">
            <p>
                <?php _e('You are browsing the site archives by popularity.', 'mo_theme'); ?>
            </p>
        </div><!-- .loop-description -->

    </div><!-- .loop-meta -->

<?php endif; ?>