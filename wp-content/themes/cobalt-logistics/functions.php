<?php
/**
 * Cobalt Logistics theme functions and definitions.
 *
 * @package Cobalt_Logistics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/icons.php';

/**
 * Theme setup.
 */
function cobalt_logistics_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
	add_theme_support( 'automatic-feed-links' );

	register_nav_menus(
		array(
			'primary' => __( 'プライマリメニュー', 'cobalt-logistics' ),
		)
	);
}
add_action( 'after_setup_theme', 'cobalt_logistics_setup' );

/**
 * Enqueue theme styles and scripts.
 */
function cobalt_logistics_scripts() {
	$style_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'cobalt-logistics-style', get_stylesheet_uri(), array(), $style_version );

	wp_enqueue_script(
		'cobalt-logistics-main',
		get_template_directory_uri() . '/js/main.js',
		array(),
		$style_version,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'cobalt_logistics_scripts' );

/**
 * Fallback menu output when no primary menu is assigned yet.
 */
function cobalt_logistics_fallback_menu() {
	echo '<ul class="main-nav__list">';
	echo '<li class="main-nav__item"><a href="' . esc_url( home_url( '/' ) ) . '">HOME</a></li>';
	echo '</ul>';
}

/**
 * Look up a page's permalink by slug, with a per-request cache so repeated
 * lookups (e.g. footer sitemap, in-page links) don't each hit the database.
 * Falls back to a guessed /{slug}/ URL if no page with that slug exists yet.
 *
 * @param string $slug Page slug (post_name).
 * @return string Permalink URL.
 */
function cobalt_logistics_page_url( $slug ) {
	static $cache = array();
	if ( ! isset( $cache[ $slug ] ) ) {
		$page = get_page_by_path( $slug );
		$cache[ $slug ] = $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
	}
	return $cache[ $slug ];
}
