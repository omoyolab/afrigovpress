<?php
/**
 * Site settings, in Appearance, Customise: the country, the organisation, contact details.
 *
 * @package afrigovpress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The networks the footer can link to, in the order they appear.
 *
 * @return array Slug => name as the network calls itself.
 */
function afrigovpress_networks() {
	return array(
		'facebook'  => 'Facebook',
		'x'         => 'X',
		'instagram' => 'Instagram',
		'linkedin'  => 'LinkedIn',
		'youtube'   => 'YouTube',
		'whatsapp'  => 'WhatsApp',
	);
}

/**
 * Only a pack that exists, or the neutral core.
 *
 * @param string $value Submitted value.
 */
function afrigovpress_sanitize_pack( $value ) {
	$packs = afrigovpress_packs();
	return isset( $packs[ $value ] ) ? $value : '';
}

/**
 * A checkbox.
 *
 * @param mixed $value Submitted value.
 */
function afrigovpress_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Registers the settings.
 *
 * @param WP_Customize_Manager $wp_customize The customiser.
 */
function afrigovpress_customize( $wp_customize ) {
	$wp_customize->add_section(
		'afrigovpress',
		array(
			'title'       => __( 'Government and country', 'afrigovpress' ),
			'description' => __( 'The site title is the organisation name in the header. Set it in Site Identity, with the crest as the logo.', 'afrigovpress' ),
			'priority'    => 25,
		)
	);

	$choices = array( '' => __( 'Neutral, no country', 'afrigovpress' ) );
	foreach ( afrigovpress_packs() as $code => $pack ) {
		$choices[ $code ] = $pack['country'];
	}
	$wp_customize->add_setting(
		'afrigovpress_pack',
		array(
			'default'           => '',
			'sanitize_callback' => 'afrigovpress_sanitize_pack',
		)
	);
	$wp_customize->add_control(
		'afrigovpress_pack',
		array(
			'label'       => __( 'Country', 'afrigovpress' ),
			'description' => __( 'Sets the colours, the flag and the words of the official banner.', 'afrigovpress' ),
			'section'     => 'afrigovpress',
			'type'        => 'select',
			'choices'     => $choices,
		)
	);

	$wp_customize->add_setting(
		'afrigovpress_banner',
		array(
			'default'           => true,
			'sanitize_callback' => 'afrigovpress_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'afrigovpress_banner',
		array(
			'label'       => __( 'Show the official website banner', 'afrigovpress' ),
			'description' => __( 'For official government websites only.', 'afrigovpress' ),
			'section'     => 'afrigovpress',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'afrigovpress_header_search',
		array(
			'default'           => true,
			'sanitize_callback' => 'afrigovpress_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'afrigovpress_header_search',
		array(
			'label'       => __( 'Search in the header', 'afrigovpress' ),
			'description' => __( 'A search button after the menu that opens a search box.', 'afrigovpress' ),
			'section'     => 'afrigovpress',
			'type'        => 'checkbox',
		)
	);

	$text = array(
		'afrigovpress_copy_of'   => array( __( 'Demonstration copy of', 'afrigovpress' ), __( 'Only for a demonstration that copies a real website: its address. The banner and the footer then say this site is an unofficial rebuild of it. Leave empty on a real site.', 'afrigovpress' ), 'url' ),
		'afrigovpress_copy_name' => array( __( 'Full name of the copied organisation', 'afrigovpress' ), __( 'For the banner of a demonstration copy, such as: the Federal Ministry of Health.', 'afrigovpress' ), 'text' ),
		'afrigovpress_parent'  => array( __( 'Line under the organisation name', 'afrigovpress' ), __( 'For an agency: "An agency of the Ministry of Health". Leave empty to use the tagline.', 'afrigovpress' ), 'text' ),
		'afrigovpress_address' => array( __( 'Postal address', 'afrigovpress' ), __( 'One line per line. Shown in the footer.', 'afrigovpress' ), 'textarea' ),
		'afrigovpress_phone'   => array( __( 'Phone', 'afrigovpress' ), '', 'text' ),
		'afrigovpress_email'   => array( __( 'Email', 'afrigovpress' ), '', 'email' ),
	);
	foreach ( $text as $id => $field ) {
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => '',
				'sanitize_callback' => 'textarea' === $field[2] ? 'sanitize_textarea_field' : ( 'email' === $field[2] ? 'sanitize_email' : ( 'url' === $field[2] ? 'esc_url_raw' : 'sanitize_text_field' ) ),
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'       => $field[0],
				'description' => $field[1],
				'section'     => 'afrigovpress',
				'type'        => $field[2],
			)
		);
	}

	foreach ( afrigovpress_networks() as $slug => $name ) {
		$wp_customize->add_setting(
			'afrigovpress_social_' . $slug,
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control(
			'afrigovpress_social_' . $slug,
			array(
				/* translators: %s: the name of a social network. */
				'label'   => sprintf( __( '%s address', 'afrigovpress' ), $name ),
				'section' => 'afrigovpress',
				'type'    => 'url',
			)
		);
	}
}
add_action( 'customize_register', 'afrigovpress_customize' );
