<?php
/**
 * Patterns and block styles: how an editor builds a page without typing a class name.
 * Patterns are the files in /patterns. Block styles appear in the Styles panel of a block.
 *
 * @package afrigovpress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The pattern categories shown in the inserter.
 */
function afrigovpress_pattern_categories() {
	register_block_pattern_category( 'afrigovpress', array( 'label' => __( 'afrigov: sections', 'afrigovpress' ) ) );
	register_block_pattern_category( 'afrigovpress-pages', array( 'label' => __( 'afrigov: whole pages', 'afrigovpress' ) ) );
}
add_action( 'init', 'afrigovpress_pattern_categories' );

/**
 * Variants an editor picks from the Styles panel.
 */
function afrigovpress_block_styles() {
	$styles = array(
		'core/paragraph' => array(
			'agp-lead' => __( 'Lead', 'afrigovpress' ),
		),
		'core/button'    => array(
			'agp-secondary' => __( 'Secondary', 'afrigovpress' ),
			'agp-start'     => __( 'Start', 'afrigovpress' ),
			'agp-warning'   => __( 'Warning', 'afrigovpress' ),
		),
		'core/group'     => array(
			'agp-band-tint'    => __( 'Band, tinted', 'afrigovpress' ),
			'agp-band-primary' => __( 'Band, primary colour', 'afrigovpress' ),
			'agp-band-dark'    => __( 'Band, dark', 'afrigovpress' ),
			'agp-inset'        => __( 'Inset', 'afrigovpress' ),
			'agp-alert'        => __( 'Alert', 'afrigovpress' ),
		),
		'core/table'     => array(
			'agp-striped' => __( 'Striped', 'afrigovpress' ),
		),
		'core/list'      => array(
			'agp-downloads' => __( 'Downloads', 'afrigovpress' ),
		),
	);
	foreach ( $styles as $block => $variants ) {
		foreach ( $variants as $name => $label ) {
			register_block_style(
				$block,
				array(
					'name'  => $name,
					'label' => $label,
				)
			);
		}
	}
}
add_action( 'init', 'afrigovpress_block_styles' );

/**
 * The address of a placeholder image shipped with the theme, for patterns.
 *
 * @param string $ratio 4x3, 3x2 or 1x1.
 * @return string
 */
function afrigovpress_placeholder( $ratio ) {
	return esc_url( get_template_directory_uri() . '/assets/placeholder-' . $ratio . '.svg' );
}
