<?php
/**
 * A generic page content template part
 *
 * A reusable page template part for displaying contents of a page without the usual hooks.
 * Useful for displaying bare content with no sidebars, before and after hooks etc. 
 * One example would be display of snippets for home page templates. 
 *
 * @package Appdev
 * @subpackage Template
 */

?>

<div id="content" class="<?php echo mo_get_content_class();?>">

  <?php while ( have_posts() ) : the_post(); ?>
  
    	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
  			<div class="entry-content clearfix">
  				<?php the_content(); ?>
  			</div><!-- .entry-content -->
  
    	</div><!-- .hentry -->
    
  <?php endwhile; ?>

</div><!-- #content -->
