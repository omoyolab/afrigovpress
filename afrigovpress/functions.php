<?php
/**
 * afrigovPress: the afrigov design system for WordPress.
 *
 * @package afrigovpress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AFRIGOVPRESS_VERSION', '0.2.0' );

require get_template_directory() . '/inc/packs.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/head.php';
require get_template_directory() . '/inc/patterns.php';

/**
 * What the theme supports, and its menus.
 */
function afrigovpress_setup() {
	load_theme_textdomain( 'afrigovpress', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 112,
			'width'       => 112,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	// Tells the afrigov blocks plugin that this theme already carries afrigov's styles.
	add_theme_support( 'afrigov' );

	// The editor shows content the way the site does.
	add_theme_support( 'editor-styles' );
	$styles = array( 'assets/afrigov/core.min.css' );
	$pack   = afrigovpress_pack_code();
	if ( $pack ) {
		$styles[] = 'assets/afrigov/' . $pack . '.min.css';
	}
	$styles[] = 'assets/afrigovpress.css';
	$styles[] = 'assets/editor.css';
	add_editor_style( $styles );

	add_image_size( 'afrigovpress-thumb', 240, 160, true );
	add_image_size( 'afrigovpress-lead', 1200, 675, true );

	register_nav_menus(
		array(
			'primary'  => __( 'Main navigation, in the header', 'afrigovpress' ),
			'footer-1' => __( 'Footer, first column', 'afrigovpress' ),
			'footer-2' => __( 'Footer, second column', 'afrigovpress' ),
			'legal'    => __( 'Footer bar: privacy, terms, accessibility', 'afrigovpress' ),
		)
	);
}
add_action( 'after_setup_theme', 'afrigovpress_setup' );

/**
 * The text column is afrigov's reading measure.
 */
function afrigovpress_content_width() {
	$GLOBALS['content_width'] = 720;
}
add_action( 'after_setup_theme', 'afrigovpress_content_width', 0 );

/**
 * Stylesheets and the script. The afrigov version is in the address, so browsers fetch new files after an update.
 */
function afrigovpress_assets() {
	$uri     = get_template_directory_uri() . '/assets/';
	$afrigov = afrigovpress_afrigov_version();

	wp_enqueue_style( 'afrigov-core', $uri . 'afrigov/core.min.css', array(), $afrigov );
	$deps = array( 'afrigov-core' );
	$pack = afrigovpress_pack_code();
	if ( $pack ) {
		wp_enqueue_style( 'afrigov-pack', $uri . 'afrigov/' . $pack . '.min.css', $deps, $afrigov );
		$deps[] = 'afrigov-pack';
	}
	wp_enqueue_style( 'afrigovpress', $uri . 'afrigovpress.css', $deps, AFRIGOVPRESS_VERSION );

	wp_enqueue_script( 'afrigov', $uri . 'afrigov/afrigov.iife.js', array(), $afrigov, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'afrigovpress_assets' );

/**
 * Collapses the menu on phones from the first paint, so the page does not jump when the script arrives.
 * If the script never arrives, the menu is shown in full again.
 */
function afrigovpress_early_class() {
	echo "<script>document.documentElement.classList.add('ag-js');addEventListener('load',function(){if(!window.AfriGov)document.documentElement.classList.remove('ag-js')});</script>\n";
}
add_action( 'wp_head', 'afrigovpress_early_class', 1 );

/**
 * The reading direction follows the pack when WordPress has not been told otherwise.
 *
 * @param string $output Language attributes for the html element.
 */
function afrigovpress_language_attributes( $output ) {
	$pack = afrigovpress_pack();
	if ( $pack && 'rtl' === $pack['direction'] && false === strpos( $output, 'dir=' ) ) {
		$output .= ' dir="rtl"';
	}
	return $output;
}
add_filter( 'language_attributes', 'afrigovpress_language_attributes' );

/**
 * An excerpt is one or two sentences, and ends without "[…]".
 */
function afrigovpress_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'afrigovpress_excerpt_more' );

/**
 * Block patterns from WordPress.org are not part of the system.
 */
function afrigovpress_patterns() {
	remove_theme_support( 'core-block-patterns' );
}
add_action( 'after_setup_theme', 'afrigovpress_patterns' );
