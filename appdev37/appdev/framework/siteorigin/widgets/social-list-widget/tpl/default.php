<?php
/**
 * @var $email
 * @var $googleplus_url
 * @var $facebook_url
 * @var $twitter_url
 * @var $youtube_url
 * @var $linkedin_url
 * @var $flickr_url
 * @var $include_rss
 */


echo do_shortcode('[social_list googleplus_url="' . $googleplus_url . '" facebook_url="' . $facebook_url . '" twitter_url="' . $twitter_url . '" youtube_url="' . $youtube_url . '" linkedin_url="' . $linkedin_url . '" flickr_url="' . $flickr_url . '" include_rss="' . ($include_rss ? 'true' : 'false') . '" ]');
