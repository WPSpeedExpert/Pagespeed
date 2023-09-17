<?php
// Common used snippets to manually remove bloat in WordPress
// Just copy what you need in the Theme's functions.php

// Unload assets
function my_deregister_scripts_and_styles() {
  // Dequeue Gutenberg styles
  wp_dequeue_style( 'wc-block-style' );
  wp_dequeue_style( 'wp-block-library'); //deregister style
  wp_dequeue_style( 'wp-block-library-theme' );

  // dequeue WP Core files not needed
  wp_deregister_script('wp-embed');
  wp_dequeue_style('classic-theme-styles');
  wp_dequeue_style('global-styles');

  // dequeue lasso wp_scripts
  wp_dequeue_style('lasso-lives');
  wp_dequeue_style('lasso-table-frontend');

  // Dequeue comment reply
  wp_deregister_script('al-comment-reply');
  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('al-comment-reply');
  }
}
add_action( 'wp_enqueue_scripts', 'my_deregister_scripts_and_styles', 999);

// Remove WordPress Version Number
remove_action('wp_head', 'wp_generator'); // Remove WordPress Generator Version
add_filter('the_generator', '__return_false'); // Remove Generator Name From Rss Feeds.

// Remove Link rel=shortlink from http
remove_action('template_redirect', 'wp_shortlink_header', 11);

// Remove Feeds
remove_action('wp_head', 'feed_links_extra', 3); // Remove Every Extra Links to Rss Feeds.
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'wc_products_rss_feed');

/**
 * Disable the emoji's
 */
function disable_emojis() {
 remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
 remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
 remove_action( 'wp_print_styles', 'print_emoji_styles' );
 remove_action( 'admin_print_styles', 'print_emoji_styles' );
 remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
 remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
 remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
 add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
 add_filter( 'wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2 );
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param array $plugins
 * @return array Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
 if ( is_array( $plugins ) ) {
 return array_diff( $plugins, array( 'wpemoji' ) );
 } else {
 return array();
 }
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference betwen the two arrays.
 */
function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
 if ( 'dns-prefetch' == $relation_type ) {
 /** This filter is documented in wp-includes/formatting.php */
 $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );

$urls = array_diff( $urls, array( $emoji_svg_url ) );
 }

return $urls;
}

/** Remove Dashicons from Admin Bar for non logged in users **/
add_action('wp_print_styles', 'jltwp_adminify_remove_dashicons', 100);

/** Remove Dashicons from Admin Bar for non logged in users **/
function jltwp_adminify_remove_dashicons()
{
    if (!is_admin_bar_showing() && !is_customize_preview()) {
        wp_dequeue_style('dashicons');
        wp_deregister_style('dashicons');
    }
}

// disable contact form 7 everywhere except where it is used
add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );
add_action('wp_enqueue_scripts', 'load_wpcf7_scripts');
function load_wpcf7_scripts() {
  if ( is_page('contact') ) {
    if ( function_exists( 'wpcf7_enqueue_scripts' ) ) {
      wpcf7_enqueue_scripts();
    }
    if ( function_exists( 'wpcf7_enqueue_styles' ) ) {
      wpcf7_enqueue_styles();
    }
  }
}
