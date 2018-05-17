<?php

$wp_include = "../wp-load.php";
$i = 0;
/*  Loop through until you find the wp-load.php file */
while (!file_exists($wp_include) && $i++ < 10) {
	$wp_include = "../$wp_include";
}

// let's load WordPress
require($wp_include);