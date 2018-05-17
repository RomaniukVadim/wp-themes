<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package commercegurus
 */
?>
<div id="secondary" class="widget-area" role="complementary">
    <?php do_action( 'before_sidebar' ); ?>
    <?php if ( !dynamic_sidebar( 'sidebar-1' ) ) : ?>

        <aside id="archives" class="widget">
            <h4 class="widget-title"><?php _e( 'Archives', 'commercegurus' ); ?></h4>
            <ul>
                <?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
            </ul>
        </aside>
    <?php endif; // end sidebar widget area ?>
</div>