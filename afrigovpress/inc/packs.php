<?php
/**
 * Country packs. The data comes from the afrigov package, copied in by `npm run sync`.
 *
 * @package afrigovpress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Everything the sync wrote: the afrigov version and the packs.
 *
 * @return array
 */
function afrigovpress_data() {
	static $data = null;
	if ( null === $data ) {
		$file = get_template_directory() . '/inc/packs-data.json';
		$data = file_exists( $file ) ? json_decode( file_get_contents( $file ), true ) : array(); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- a local file shipped with the theme.
		if ( ! is_array( $data ) ) {
			$data = array();
		}
	}
	return $data;
}

/**
 * The version of afrigov the theme carries.
 *
 * @return string
 */
function afrigovpress_afrigov_version() {
	$data = afrigovpress_data();
	return isset( $data['afrigov'] ) ? $data['afrigov'] : AFRIGOVPRESS_VERSION;
}

/**
 * All packs, by country code.
 *
 * @return array
 */
function afrigovpress_packs() {
	$data = afrigovpress_data();
	return isset( $data['packs'] ) ? $data['packs'] : array();
}

/**
 * The chosen pack's code, or an empty string for the neutral core.
 *
 * @return string
 */
function afrigovpress_pack_code() {
	$code  = get_theme_mod( 'afrigovpress_pack', '' );
	$packs = afrigovpress_packs();
	return isset( $packs[ $code ] ) ? $code : '';
}

/**
 * The chosen pack, or null.
 *
 * @return array|null
 */
function afrigovpress_pack() {
	$code  = afrigovpress_pack_code();
	$packs = afrigovpress_packs();
	return $code ? $packs[ $code ] : null;
}

/**
 * A pack string in the site's language, falling back to the pack's default language, then English.
 *
 * @param string $key      Like "banner" or "banner-how".
 * @param string $fallback Used when no pack is chosen.
 * @return string
 */
function afrigovpress_string( $key, $fallback ) {
	$pack = afrigovpress_pack();
	if ( ! $pack || empty( $pack['strings'] ) ) {
		return $fallback;
	}
	$site = substr( get_bloginfo( 'language' ), 0, 2 );
	foreach ( array( $site, $pack['language'], 'en' ) as $lang ) {
		if ( ! empty( $pack['strings'][ $lang ][ $key ] ) ) {
			return $pack['strings'][ $lang ][ $key ];
		}
	}
	return $fallback;
}
