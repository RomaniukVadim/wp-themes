<?php
/**
 * Footer Template
 *
 * The footer template is generally used on every page of your site. Nearly all other
 * templates call it somewhere near the bottom of the file. It is used mostly as a closing
 * wrapper, which is opened with the header.php file. It also executes key functions needed
 * by the theme, child themes, and plugins.
 *
 * @package Appdev
 * @subpackage Template
 */
?>

</div><!-- #main .inner -->

<?php mo_exec_action('end_main'); ?>

</div><!-- #main -->

<?php
$sidebar_manager = mo_get_sidebar_manager();

if ($sidebar_manager->is_footer_area_active()):
    ?>
    <?php mo_exec_action('before_footer'); ?>

    <div id="footer">

        <div class="inner">

            <?php mo_exec_action('start_footer'); ?>

            <div id="sidebars-footer" class="clearfix">

                <?php
                mo_exec_action('start_sidebar_footer');

                $sidebar_manager->populate_footer_sidebars();

                mo_exec_action('end_sidebar_footer');
                ?>

            </div>
            <!-- #sidebars-footer -->

            <?php mo_exec_action('end_footer'); ?>

        </div>

    </div>  <!--#footer-->

    <?php mo_exec_action('after_footer'); ?>

<?php endif; ?>

<div id="footer-bottom">

    <div class="inner">

        <?php get_template_part('menu', 'footer'); // Loads the menu-footer.php template.    ?>

        <?php mo_footer_content(); ?>

        <?php echo '<a id="go-to-top" href="#" title="' . __('Back to top', 'mo_theme') . '">' . __('Go Top', 'mo_theme') . '</a>'; ?>

    </div>

</div><!-- #footer-bottom -->

</div><!-- #container -->

<?php mo_exec_action('end_body'); ?>

<?php wp_footer(); // wp_footer    ?>

</body>
</html>