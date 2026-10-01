<?php
/**
 * What search engines and assistants read: a description, how the page looks when shared,
 * and structured data. Written for every page from what the editor already wrote.
 * If an SEO plugin is active, the theme leaves all of it to the plugin.
 *
 * @package afrigovpress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether a plugin already writes these tags.
 *
 * @return bool
 */
function afrigovpress_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'SLIM_SEO_VER' );
}

/**
 * One or two sentences about this page.
 *
 * @return string
 */
function afrigovpress_description() {
	$text = '';
	if ( is_singular() ) {
		$post = get_queried_object();
		$text = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_strip_all_tags( strip_shortcodes( excerpt_remove_blocks( $post->post_content ) ) );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$text = term_description();
	} elseif ( is_search() ) {
		/* translators: %s: what was searched for. */
		$text = sprintf( __( 'Search results for %s.', 'afrigovpress' ), get_search_query() );
	}
	if ( ! $text ) {
		$text = get_bloginfo( 'description' );
	}
	$text = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $text ) ) );
	if ( function_exists( 'mb_strlen' ) && mb_strlen( $text ) > 158 ) {
		$cut  = mb_substr( $text, 0, 158 );
		$text = rtrim( mb_substr( $cut, 0, mb_strrpos( $cut, ' ' ) ), ',;:' ) . '…';
	}
	return $text;
}

/**
 * The image shown when the page is shared: the post's own, or the crest.
 *
 * @return string
 */
function afrigovpress_share_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		return (string) get_the_post_thumbnail_url( null, 'afrigovpress-lead' );
	}
	$logo = get_theme_mod( 'custom_logo' );
	return $logo ? (string) wp_get_attachment_image_url( $logo, 'full' ) : '';
}

/**
 * The organisation, for structured data.
 *
 * @return array
 */
function afrigovpress_organization() {
	$org  = array(
		'@type' => 'GovernmentOrganization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
	);
	$logo = get_theme_mod( 'custom_logo' );
	if ( $logo ) {
		$org['logo'] = wp_get_attachment_image_url( $logo, 'full' );
	}
	return $org;
}

/**
 * Prints the tags.
 */
function afrigovpress_head() {
	if ( afrigovpress_seo_plugin_active() ) {
		return;
	}
	$description = afrigovpress_description();
	$title       = wp_get_document_title();
	$url         = is_singular() ? get_permalink() : home_url( add_query_arg( array() ) );
	$image       = afrigovpress_share_image();

	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
	}
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
	if ( $description ) {
		echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
	}
	echo '<meta property="og:type" content="' . ( is_singular( 'post' ) ? 'article' : 'website' ) . '" />' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '" />' . "\n";
	}

	// Structured data.
	$graph = array();
	if ( is_front_page() ) {
		$org  = afrigovpress_organization();
		$pack = afrigovpress_pack();
		if ( $pack ) {
			$org['parentOrganization'] = array(
				'@type' => 'GovernmentOrganization',
				'name'  => $pack['government'],
			);
		}
		$address = trim( (string) get_theme_mod( 'afrigovpress_address', '' ) );
		if ( $address ) {
			$org['address'] = array(
				'@type'         => 'PostalAddress',
				'streetAddress' => preg_replace( '/\s*\n\s*/', ', ', $address ),
			);
			if ( afrigovpress_pack_code() ) {
				$org['address']['addressCountry'] = strtoupper( afrigovpress_pack_code() );
			}
		}
		$phone = get_theme_mod( 'afrigovpress_phone', '' );
		$email = get_theme_mod( 'afrigovpress_email', '' );
		if ( $phone || $email ) {
			$org['contactPoint'] = array_filter(
				array(
					'@type'       => 'ContactPoint',
					'contactType' => 'customer service',
					'telephone'   => $phone,
					'email'       => $email,
				)
			);
		}
		$same = array();
		foreach ( array_keys( afrigovpress_networks() ) as $slug ) {
			$account = get_theme_mod( 'afrigovpress_social_' . $slug, '' );
			if ( $account ) {
				$same[] = $account;
			}
		}
		if ( $same ) {
			$org['sameAs'] = $same;
		}
		$graph[] = $org;
		$graph[] = array(
			'@type'     => 'WebSite',
			'name'      => get_bloginfo( 'name' ),
			'url'       => home_url( '/' ),
			'publisher' => array(
				'@type' => 'GovernmentOrganization',
				'name'  => get_bloginfo( 'name' ),
			),
		);
	}

	$trail = afrigovpress_trail();
	if ( count( $trail ) > 1 ) {
		$items = array();
		foreach ( $trail as $i => $crumb ) {
			$item = array(
				'@type'    => 'ListItem',
				'position' => $i + 1,
				'name'     => $crumb[0],
			);
			if ( $crumb[1] ) {
				$item['item'] = $crumb[1];
			}
			$items[] = $item;
		}
		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $items,
		);
	}

	if ( is_singular( 'post' ) ) {
		$article = array(
			'@type'            => 'NewsArticle',
			'headline'         => get_the_title(),
			'datePublished'    => get_the_date( 'c' ),
			'dateModified'     => get_the_modified_date( 'c' ),
			'mainEntityOfPage' => get_permalink(),
			'publisher'        => afrigovpress_organization(),
		);
		if ( $description ) {
			$article['description'] = $description;
		}
		if ( $image ) {
			$article['image'] = $image;
		}
		$graph[] = $article;
	}

	if ( $graph ) {
		echo '<script type="application/ld+json">' . wp_json_encode(
			array(
				'@context' => 'https://schema.org',
				'@graph'   => $graph,
			),
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
		) . '</script>' . "\n";
	}
}
add_action( 'wp_head', 'afrigovpress_head', 5 );
