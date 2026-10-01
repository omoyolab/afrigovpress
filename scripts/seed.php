<?php
/**
 * Fills the local site with a real organisation's content so the theme can be judged on real pages:
 * settings, pages, menus, and the ministry's latest news pulled through its own WordPress API.
 * Run: npm run seed. Safe to run again.
 */

update_option( 'blogname', 'Federal Ministry of Communications, Innovation and Digital Economy' );
update_option( 'blogdescription', '' );
update_option( 'date_format', 'j F Y' );
update_option( 'permalink_structure', '/%postname%/' );
update_option( 'default_comment_status', 'closed' );
update_option( 'posts_per_page', 6 );

set_theme_mod( 'afrigovpress_pack', 'ng' );
set_theme_mod( 'afrigovpress_banner', true );
set_theme_mod( 'afrigovpress_address', "Federal Secretariat Complex Phase I,\nAnnex III, Shehu Shagari Way,\nFCT Nigeria.\nP.M.B. 12578" );
set_theme_mod( 'afrigovpress_email', 'info@fmcide.gov.ng' );
set_theme_mod( 'afrigovpress_social_facebook', 'https://www.facebook.com/FMoCDE' );
set_theme_mod( 'afrigovpress_social_x', 'https://x.com/fmcidenigeria' );
set_theme_mod( 'afrigovpress_social_instagram', 'https://www.instagram.com/fmocde' );
set_theme_mod( 'afrigovpress_social_linkedin', 'https://www.linkedin.com/company/fmcidenigeria/' );

function seed_page( $title, $slug, $content, $excerpt = '' ) {
	$existing = get_page_by_path( $slug );
	$data     = array(
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_content' => $content,
		'post_excerpt' => $excerpt,
		'post_status'  => 'publish',
		'post_type'    => 'page',
	);
	if ( $existing ) {
		$data['ID'] = $existing->ID;
		return wp_update_post( $data );
	}
	return wp_insert_post( $data );
}
$p = function ( $text ) {
	return "<!-- wp:paragraph -->\n<p>$text</p>\n<!-- /wp:paragraph -->\n\n";
};
$h = function ( $text ) {
	return "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">$text</h2>\n<!-- /wp:heading -->\n\n";
};
$ul = function ( $items ) {
	$li = '';
	foreach ( $items as $item ) {
		$li .= "<!-- wp:list-item -->\n<li>$item</li>\n<!-- /wp:list-item -->\n";
	}
	return "<!-- wp:list -->\n<ul class=\"wp-block-list\">$li</ul>\n<!-- /wp:list -->\n\n";
};

$home = seed_page(
	'Driving economic growth through digital technology and innovation',
	'home',
	$p( 'The ministry leads Nigeria\'s digital economy: connectivity, technical talent, data, innovation and the digital transformation of government.' ) .
	$h( 'What we do' ) .
	$ul( array( 'Nationwide access to communications infrastructure.', 'ICT in every sector of the economy.', 'A growing ICT industry that adds to national output.', 'Technology for transparent government and better public services.' ) )
);
$about = seed_page(
	'About the ministry',
	'about',
	$h( 'Our journey' ) .
	$p( 'The ministry was established in 2011 as the Ministry of Communication Technology, at a turning point in Nigeria\'s move towards a knowledge-based economy and an inclusive information society. It became the Federal Ministry of Communications, Innovation and Digital Economy in 2023.' ) .
	$h( 'Why we exist' ) .
	$p( 'The ministry exists to use information and communication technology for three things: creating jobs, growing the economy, and making government transparent.' ) .
	$h( 'Our mandate' ) .
	$ul( array( '<strong>Enable universal access.</strong> Affordable access to communications infrastructure across the whole country.', '<strong>Advance ICT integration.</strong> ICT in every part of life.', '<strong>Grow the ICT industry.</strong> A larger share of national output from it.', '<strong>Drive transparency and efficiency.</strong> Open government and better public services.' ) ) .
	"<!-- wp:details -->\n<details class=\"wp-block-details\"><summary>How the ministry is organised</summary><!-- wp:paragraph -->\n<p>The ministry supervises seven agencies, including NITDA, the Nigerian Communications Commission and NIPOST.</p>\n<!-- /wp:paragraph --></details>\n<!-- /wp:details -->\n\n",
	'Pioneering Nigeria\'s digital future. The ministry was created in 2011 to drive economic growth through digital technology and innovation.'
);
$initiatives = seed_page(
	'Initiatives',
	'initiatives',
	$p( 'The programmes through which the ministry drives inclusive growth and the digital economy.' ) .
	"<!-- wp:table -->\n<figure class=\"wp-block-table\"><table><thead><tr><th>Initiative</th><th>What it does</th></tr></thead><tbody><tr><td>Project BRIDGE</td><td>90,000 km of fibre optic cable as the national backbone.</td></tr><tr><td>3 Million Technical Talent</td><td>Technical skills for three million Nigerians.</td></tr><tr><td>E-government</td><td>Connected government, open data and online services.</td></tr></tbody></table></figure>\n<!-- /wp:table -->\n\n" .
	"<!-- wp:buttons -->\n<div class=\"wp-block-buttons\"><!-- wp:button -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link wp-element-button\" href=\"/about/\">About the ministry</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->\n",
	'Seven programmes, from fibre to technical talent.'
);
$news          = seed_page( 'News', 'news', '' );
$contact       = seed_page( 'Contact', 'contact', $p( 'Email <a href="mailto:info@fmcide.gov.ng">info@fmcide.gov.ng</a>. We reply within five working days.' ), 'How to reach the ministry.' );
$accessibility = seed_page( 'Accessibility statement', 'accessibility', $p( 'This website is built to meet WCAG 2.2 at level AA. Tell us if something does not work for you.' ) );
$privacy       = seed_page( 'Privacy', 'privacy', $p( 'What we collect and why.' ) );

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home );
update_option( 'page_for_posts', $news );

function seed_menu( $name, $location, $pages ) {
	$menu = wp_get_nav_menu_object( $name );
	if ( $menu ) {
		wp_delete_nav_menu( $menu->term_id );
	}
	$id = wp_create_nav_menu( $name );
	foreach ( $pages as $label => $page ) {
		wp_update_nav_menu_item(
			$id,
			0,
			array(
				'menu-item-title'     => $label,
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page,
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
			)
		);
	}
	$locations              = get_theme_mod( 'nav_menu_locations', array() );
	$locations[ $location ] = $id;
	set_theme_mod( 'nav_menu_locations', $locations );
}
seed_menu( 'Main', 'primary', array( 'Home' => $home, 'About' => $about, 'Initiatives' => $initiatives, 'News' => $news, 'Contact' => $contact ) );
seed_menu( 'Ministry', 'footer-1', array( 'About' => $about, 'Initiatives' => $initiatives ) );
seed_menu( 'Media', 'footer-2', array( 'News' => $news ) );
seed_menu( 'Legal', 'legal', array( 'Accessibility' => $accessibility, 'Privacy' => $privacy ) );

// The ministry's latest news, through its own WordPress API.
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
$hello = get_page_by_path( 'hello-world', OBJECT, 'post' );
if ( $hello ) {
	wp_delete_post( $hello->ID, true );
}
$response = wp_remote_get( 'https://fmcide.gov.ng/wp-json/wp/v2/posts?per_page=9&_embed=wp:featuredmedia&_fields=slug,title,date,excerpt,link,_links,_embedded', array( 'timeout' => 40, 'user-agent' => 'afrigovPress seed' ) );
$posts    = is_wp_error( $response ) ? array() : json_decode( wp_remote_retrieve_body( $response ), true );
$count    = 0;
foreach ( (array) $posts as $remote ) {
	if ( empty( $remote['slug'] ) || get_page_by_path( $remote['slug'], OBJECT, 'post' ) ) {
		continue;
	}
	$title   = html_entity_decode( wp_strip_all_tags( $remote['title']['rendered'] ), ENT_QUOTES );
	$excerpt = trim( html_entity_decode( wp_strip_all_tags( $remote['excerpt']['rendered'] ), ENT_QUOTES ) );
	$excerpt = preg_replace( '/\s*\[?…\]?$|\s*\[&hellip;\]$/u', '', $excerpt );
	$id      = wp_insert_post(
		array(
			'post_title'   => $title,
			'post_name'    => $remote['slug'],
			'post_date'    => str_replace( 'T', ' ', $remote['date'] ),
			'post_excerpt' => wp_trim_words( $excerpt, 28, '…' ),
			'post_content' => ( $excerpt ? $p( esc_html( $excerpt ) ) : '' ) . $p( 'Read the full announcement on <a href="' . esc_url( $remote['link'] ) . '">the ministry\'s website</a>.' ),
			'post_status'  => 'publish',
			'post_type'    => 'post',
		)
	);
	$image   = isset( $remote['_embedded']['wp:featuredmedia'][0]['source_url'] ) ? $remote['_embedded']['wp:featuredmedia'][0]['source_url'] : '';
	if ( $id && $image ) {
		$media = media_sideload_image( $image, $id, $title, 'id' );
		if ( ! is_wp_error( $media ) ) {
			set_post_thumbnail( $id, $media );
		}
	}
	++$count;
}
flush_rewrite_rules();
echo "Seeded: pages, menus, settings, and $count news posts from fmcide.gov.ng (" . ( is_wp_error( $response ) ? $response->get_error_message() : 'API ok' ) . ").\n";
