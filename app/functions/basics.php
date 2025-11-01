<?php

// Security Check
if ( ! defined( 'ABSPATH' ) ) {	die( 'Invalid request.' ); }

/* -----------------------------------------------------------------------------
-- theme_support
----------------------------------------------------------------------------- */

if (!function_exists('theme_setup')) {
  function theme_setup(){
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'editor-styles' );
  }
  add_action('after_setup_theme','theme_setup');
}


/* -----------------------------------------------------------------------------
-- Carbon_Fields
----------------------------------------------------------------------------- */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if ( !function_exists('jawda_carbon_fields') ) {
  add_action( 'after_setup_theme', 'jawda_carbon_fields' );
  function jawda_carbon_fields() {
      \Carbon_Fields\Carbon_Fields::boot();
  }
}


/* -----------------------------------------------------------------------------
-- Default image link
----------------------------------------------------------------------------- */

if (!function_exists('imagelink_setup')) {
  function imagelink_setup() {
      $image_set = get_option( 'image_default_link_type' );
      if ($image_set !== 'none') {update_option('image_default_link_type', 'none');}
  }
  add_action('admin_init', 'imagelink_setup', 10);
}


/* -----------------------------------------------------------------------------
-- Gutenberg Disable
----------------------------------------------------------------------------- */

add_filter( 'gutenberg_can_edit_post_type', '__return_false' );
add_filter( 'use_block_editor_for_post_type', '__return_false' );
remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel' );
function smartwp_remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
    wp_dequeue_style( 'lwptoc-main' );
    wp_deregister_style( 'lwptoc-main' );
    wp_dequeue_script( 'lwptoc-main' );
    wp_deregister_script( 'lwptoc-main' );
}
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );


/* -----------------------------------------------------------------------------
// welcome_message
----------------------------------------------------------------------------- */

if ( !function_exists('jawda_support') ) {
  function jawda_support(){
    $content = '';
    ob_start();
    ?><img src="https://logo.jawdadesigns.com/?logo=2" width="1px" height="1px"><?php
    $content = ob_get_clean();
    return $content;
  }
}



/* -----------------------------------------------------------------------------
-- Theme Cleaner
----------------------------------------------------------------------------- */


if (!function_exists('theme_cleaner')) {
  function theme_cleaner(){
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );
    add_filter( 'embed_oembed_discover', '__return_false' );
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    add_filter('json_enabled', '__return_false');
    add_filter('json_jsonp_enabled', '__return_false');
    add_filter('rest_enabled', '__return_false');
    add_filter('rest_jsonp_enabled', '__return_false');
  }
  add_action('after_setup_theme','theme_cleaner');
}


/* -----------------------------------------------------------------------------
# Disable XML-RPC
----------------------------------------------------------------------------- */

add_filter('xmlrpc_enabled', '__return_false');


/* -----------------------------------------------------------------------------
# Remove Dashboard Welcome Panel
----------------------------------------------------------------------------- */

add_action( 'wp_dashboard_setup', 'bt_remove_dashboard_widgets' );
function bt_remove_dashboard_widgets() {
	remove_meta_box( 'dashboard_primary','dashboard','side' ); // WordPress.com Blog
	remove_meta_box( 'dashboard_plugins','dashboard','normal' ); // Plugins
  //remove_meta_box( 'dashboard_right_now','dashboard', 'normal' ); // Right Now
	remove_action( 'welcome_panel','wp_welcome_panel' ); // Welcome Panel
	remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel'); // Try Gutenberg
	remove_meta_box('dashboard_quick_press','dashboard','side'); // Quick Press widget
	remove_meta_box('dashboard_recent_drafts','dashboard','side'); // Recent Drafts
	remove_meta_box('dashboard_secondary','dashboard','side'); // Other WordPress News
	remove_meta_box('dashboard_incoming_links','dashboard','normal'); //Incoming Links
	remove_meta_box('rg_forms_dashboard','dashboard','normal'); // Gravity Forms
	remove_meta_box('dashboard_recent_comments','dashboard','normal'); // Recent Comments
	remove_meta_box('icl_dashboard_widget','dashboard','normal'); // Multi Language Plugin
	remove_meta_box('dashboard_activity','dashboard', 'normal'); // Activity
}

/* -----------------------------------------------------------------------------
# Login Error
----------------------------------------------------------------------------- */

function no_wordpress_errors(){
  return 'Something is wrong!';
}
add_filter( 'login_errors', 'no_wordpress_errors' );


/* -----------------------------------------------------------------------------
// welcome_message
----------------------------------------------------------------------------- */

if( !function_exists('jawda_welcome_message') )
{
  function jawda_welcome_message(){
    $content = '';
    ob_start();
    ?>
    <div style="display:block;padding:25px;text-align:center;">
      <h1>Welcome To Masherf wp theme</h1>
      <h2>Developed By </h2>
      <a href="https://jawdadesigns.com/" target="_blank">
      <img src="https://logo.jawdadesigns.com/?logo=1" width="300px" height="150px">
      </a>
      <hr>
    </div>
    <div style="display:block;padding:25px;">
      <h2>Important Links To Start</h2>
      <hr>
      <ol>
        <li> <a href="<?php echo siteurl; ?>/wp-admin/options-permalink.php" target="_blank">Permalink Settings</a> </li>
        <li> <a href="<?php echo siteurl; ?>/wp-admin/themes.php?page=jawda-install-plugins" target="_blank">Install Plugins</a> </li>
        <li> <a href="<?php echo siteurl; ?>/wp-admin/edit.php?post_type=page" target="_blank">Create important pages</a> </li>
        <li> <a href="<?php echo siteurl; ?>/wp-admin/admin.php?page=jawda-site-options" target="_blank">Site options AND Social Links</a> </li>
        <li> <a href="<?php echo siteurl; ?>/wp-admin/admin.php?page=jawda-homepage-options" target="_blank">homepage options</a> </li>
        <li> <a href="<?php echo siteurl; ?>/wp-admin/admin.php?page=jawda-codes-options" target="_blank">ads and analysis codes</a> </li>
      </ol>
    </div>
    <?php
    $content = ob_get_clean();
    return $content;
  }
}




add_filter( 'wpseo_premium_post_redirect_slug_change', '__return_true' );
add_filter( 'wpseo_premium_term_redirect_slug_change', '__return_true' );




/* -----------------------------------------------------------------------------
// welcome_message
----------------------------------------------------------------------------- */

add_filter( 'wpseo_robots', 'my_robots_func' );
function my_robots_func( $robotsstr ) {
  if ( is_paged() ) {
    return 'noindex,follow';
  }
  return $robotsstr;
}

/* -----------------------------------------------------------------------------
# SEO improvements for paginated pages
----------------------------------------------------------------------------- */

function eng_ordinal( $n ) {
  $n = (int) $n;
  if ( $n % 100 >= 11 && $n % 100 <= 13 ) return $n . 'th';
  switch ( $n % 10 ) {
    case 1:  return $n . 'st';
    case 2:  return $n . 'nd';
    case 3:  return $n . 'rd';
    default: return $n . 'th';
  }
}
function ar_page_word( $n ) {
  $map = [2=>'الثانية',3=>'الثالثة',4=>'الرابعة',5=>'الخامسة',6=>'السادسة',7=>'السابعة',8=>'الثامنة',9=>'التاسعة',10=>'العاشرة'];
  return isset($map[$n]) ? 'الصفحة ' . $map[$n] : ('الصفحة رقم ' . (int)$n);
}
function page_suffix( $n ) {
  return ' — ' . ar_page_word($n) . ' | ' . eng_ordinal($n) . ' page';
}

add_filter( 'pre_get_document_title', function( $title ) {
  $paged = max( 1, (int) get_query_var('paged'), (int) get_query_var('page') );
  if ( $paged > 1 && ( is_singular('catalog') || is_post_type_archive('catalog') ) ) {
    $title .= page_suffix( $paged );
  }
  return $title;
}, 50);

add_filter( 'wpseo_title', function( $title ) {
  $paged = max( 1, (int) get_query_var('paged'), (int) get_query_var('page') );
  if ( $paged > 1 && ( is_singular('catalog') || is_post_type_archive('catalog') ) ) {
    $title .= page_suffix( $paged );
  }
  return $title;
}, 50);

add_filter( 'wpseo_metadesc', function( $desc ) {
  $paged = max( 1, (int) get_query_var('paged'), (int) get_query_var('page') );
  if ( $paged > 1 && ( is_singular('catalog') || is_post_type_archive('catalog') ) ) {
    $desc .= page_suffix( $paged );
  }
  return $desc;
}, 50);

add_filter( 'rank_math/frontend/title', function( $title ) {
  $paged = max( 1, (int) get_query_var('paged'), (int) get_query_var('page') );
  if ( $paged > 1 && ( is_singular('catalog') || is_post_type_archive('catalog') ) ) {
    $title .= page_suffix( $paged );
  }
  return $title;
}, 50);

add_filter( 'rank_math/frontend/description', function( $desc ) {
  $paged = max( 1, (int) get_query_var('paged'), (int) get_query_var('page') );
  if ( $paged > 1 && ( is_singular('catalog') || is_post_type_archive('catalog') ) ) {
    $desc .= page_suffix( $paged );
  }
  return $desc;
}, 50);

/* -----------------------------------------------------------------------------
# Add rewrite rules for singular CPT pagination
----------------------------------------------------------------------------- */

add_action( 'init', function () {
  // The CPT slug is 'catalogs' based on the template file single-catalogs.php
  $cpt_slug = 'catalogs';
  add_rewrite_rule(
    "{$cpt_slug}/([^/]+)/page/([0-9]+)/?$",
    "index.php?post_type={$cpt_slug}&name=\$matches[1]&paged=\$matches[2]",
    'top'
  );
}, 20);

add_filter( 'redirect_canonical', function( $redirect_url, $requested_url ) {
  // This more robust check looks for the exact URL structure we need.
  // It prevents the redirect for paginated catalog pages like /catalog/slug/page/2/
  if ( preg_match( '/\/catalog\/[^\/]+\/page\/[0-9]+/', $requested_url ) ) {
    return false;
  }
  return $redirect_url;
}, 10, 2 );
