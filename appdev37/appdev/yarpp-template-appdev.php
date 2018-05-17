<?php /*
  Template to use with Appdev Theme
  Author: LiveMesh
 */
?>
<div id="yarpp-related-posts" class="clearfix">
    <h3 class="title">Related Posts</h3>
    <?php
    if ($related_query->have_posts()):
        if (function_exists('mo_display_post_nuggets_grid_style')):
            $layout_manager = mo_get_layout_manager();

            /* Set the default arguments. */
            $args = array(
                'loop' => $related_query,
                'number_of_columns' => 2,
                'image_width' => 186,
                'image_height' => 105,
                'image_size' => 'medium',
                'excerpt_count' => 0
            );

            if ($layout_manager->is_full_width_layout())
                $args['number_of_columns'] = 4;
            else
                $args['number_of_columns'] = 3;

            mo_display_post_nuggets_grid_style($args);
        endif;
    else:
        echo '<p>No related posts.</p>';
    endif;
    ?>
</div>
