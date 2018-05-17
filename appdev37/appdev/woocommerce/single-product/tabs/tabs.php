<?php
/**
 * Single Product tabs - overriding to leverage tab function part of the theme
 *
 * @author 		LiveMesh/WooThemes
 * @package 	Appdev
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters('woocommerce_product_tabs', array());

if (!empty($tabs)) : ?>
    <div class="clear"></div>
    <div class="woocommerce-custom-tabs">
        <ul class="tabs">
            <?php foreach ($tabs as $key => $tab) : ?>

                <li class="<?php echo $key ?>_tab">
                    <a href="#tab-<?php echo $key ?>"><?php echo apply_filters('woocommerce_product_' . $key . '_tab_title', $tab['title'], $key) ?></a>
                </li>

            <?php endforeach; ?>
        </ul>
        <div class="panes">
            <?php foreach ($tabs as $key => $tab) : ?>

                <div class="pane entry-content" id="tab-<?php echo $key ?>">
                    <?php call_user_func($tab['callback'], $key, $tab) ?>
                </div>

            <?php endforeach; ?>
        </div>
    </div>

<?php endif; ?>