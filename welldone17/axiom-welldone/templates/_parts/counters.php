<?php
// Get template args
extract(axiom_welldone_template_get_args('counters'));

$show_all_counters = !empty($post_options['counters']);
$counters_tag = is_single() ? 'span' : 'a';

// Views
if ($show_all_counters && axiom_welldone_strpos($post_options['counters'], 'views')!==false) {
	?>
	<<?php axiom_welldone_show_layout($counters_tag); ?> class="post_counters_item post_counters_views icon-eye-1" title="<?php echo esc_attr( sprintf(__('Views - %s', 'axiom-welldone'), $post_data['post_views']) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php axiom_welldone_show_layout($post_data['post_views']); ?></span><?php if (axiom_welldone_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Views', 'axiom-welldone'); ?></<?php axiom_welldone_show_layout($counters_tag); ?>>
	<?php
}

// Comments
if ($show_all_counters && axiom_welldone_strpos($post_options['counters'], 'comments')!==false) {
	?>
	<a class="post_counters_item post_counters_comments icon-comment-1" title="<?php echo esc_attr( sprintf(__('Comments - %s', 'axiom-welldone'), $post_data['post_comments']) ); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><span class="post_counters_number"><?php axiom_welldone_show_layout($post_data['post_comments']); ?></span><?php if (axiom_welldone_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Comments', 'axiom-welldone'); ?></a>
	<?php 
}
 
// Rating
$rating = $post_data['post_reviews_'.(axiom_welldone_get_theme_option('reviews_first')=='author' ? 'author' : 'users')];
if ($rating > 0 && ($show_all_counters && axiom_welldone_strpos($post_options['counters'], 'rating')!==false)) {
	?>
	<<?php axiom_welldone_show_layout($counters_tag); ?> class="post_counters_item post_counters_rating icon-star" title="<?php echo esc_attr( sprintf(__('Rating - %s', 'axiom-welldone'), $rating) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php axiom_welldone_show_layout($rating); ?></span></<?php axiom_welldone_show_layout($counters_tag); ?>>
	<?php
}

// Likes
if ($show_all_counters && axiom_welldone_strpos($post_options['counters'], 'likes')!=false) {
	// Load core messages
	axiom_welldone_enqueue_messages();
	$likes = isset($_COOKIE['axiom_welldone_likes']) ? $_COOKIE['axiom_welldone_likes'] : '';
	$allow = axiom_welldone_strpos($likes, ','.($post_data['post_id']).',')===false;
	?>
	<a class="post_counters_item post_counters_likes icon-heart <?php echo !empty($allow) ? 'enabled' : 'disabled'; ?>" title="<?php echo !empty($allow) ? esc_attr__('Like', 'axiom-welldone') : esc_attr__('Dislike', 'axiom-welldone'); ?>" href="#"
		data-postid="<?php echo esc_attr($post_data['post_id']); ?>"
		data-likes="<?php echo esc_attr($post_data['post_likes']); ?>"
		data-title-like="<?php esc_attr_e('Like', 'axiom-welldone'); ?>"
		data-title-dislike="<?php esc_attr_e('Dislike', 'axiom-welldone'); ?>"><span class="post_counters_number"><?php axiom_welldone_show_layout($post_data['post_likes']); ?></span><?php if (axiom_welldone_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Likes', 'axiom-welldone'); ?></a>
	<?php
}

// Edit page link
if (axiom_welldone_strpos($post_options['counters'], 'edit')!==false) {
	edit_post_link( esc_html__( 'Edit', 'axiom-welldone' ), '<span class="post_edit edit-link">', '</span>' );
}

// Markup for search engines
if (is_single() && axiom_welldone_strpos($post_options['counters'], 'markup')!==false) {
	?>
	<meta itemprop="interactionCount" content="User<?php echo esc_attr(axiom_welldone_strpos($post_options['counters'],'comments')!==false ? 'Comments' : 'PageVisits'); ?>:<?php echo esc_attr(axiom_welldone_strpos($post_options['counters'], 'comments')!==false ? $post_data['post_comments'] : $post_data['post_views']); ?>" />
	<?php
}
?>