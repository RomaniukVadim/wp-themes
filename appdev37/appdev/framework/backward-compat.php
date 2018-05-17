<?php

global $mo_theme;

/* Sets the path to the parent theme directory. */
define('MO_THEME_DIR', get_template_directory());

/* Sets the path to the parent theme directory URI. */
define('MO_THEME_URL', get_template_directory_uri());

/* Sets the path to the core Livemesh Framework directory. */
define('MO_FRAMEWORK_DIR', get_template_directory() . '/framework');

/* Sets the path to the theme scripts directory. */
define('MO_SCRIPTS_URL', MO_THEME_URL . '/js');

/* Sets the path to the theme third party library scripts directory. */
define('MO_SCRIPTS_LIB_URL', MO_THEME_URL . '/js/libs');

/* Sets the path to the theme images directory. */
define('MO_IMAGES_URL', MO_THEME_URL . '/images');