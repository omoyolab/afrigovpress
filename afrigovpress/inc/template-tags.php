<?php
/**
 * The pieces templates are built from: flag, banner, menus, breadcrumb, pagination, social links.
 *
 * @package afrigovpress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The country flag, drawn by the pack. Decorative: the words beside it carry the meaning.
 *
 * @param bool $large The header size.
 * @return string
 */
function afrigovpress_flag( $large = false ) {
	$pack = afrigovpress_pack();
	if ( ! $pack ) {
		return '';
	}
	$stripes = str_repeat( '<span></span>', max( 3, count( (array) $pack['flag'] ) ) );
	return '<span class="ag-flag' . ( $large ? ' ag-flag--lg' : '' ) . '" aria-hidden="true">' . $stripes . '</span>';
}

/**
 * The official website banner, above the header on every page.
 */
function afrigovpress_banner() {
	$copy_of = get_theme_mod( 'afrigovpress_copy_of', '' );
	if ( $copy_of ) {
		afrigovpress_demo_banner( $copy_of );
		return;
	}
	if ( ! get_theme_mod( 'afrigovpress_banner', true ) ) {
		return;
	}
	?>
	<section class="ag-banner" aria-label="<?php esc_attr_e( 'Official website notice', 'afrigovpress' ); ?>">
		<div class="ag-container ag-banner__inner">
			<?php echo afrigovpress_flag(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup. ?>
			<p class="ag-banner__text"><?php echo esc_html( afrigovpress_string( 'banner', __( 'An official government website', 'afrigovpress' ) ) ); ?></p>
			<details class="ag-banner__details">
				<summary><?php echo esc_html( afrigovpress_string( 'banner-how', __( 'How you know this is official', 'afrigovpress' ) ) ); ?></summary>
				<p><?php echo esc_html( afrigovpress_string( 'banner-domain', __( 'Official websites use a government domain', 'afrigovpress' ) ) ); ?></p>
				<p><?php echo esc_html( afrigovpress_string( 'banner-secure', __( 'Secure government websites use HTTPS', 'afrigovpress' ) ) ); ?></p>
			</details>
		</div>
	</section>
	<?php
}

/**
 * The banner of a demonstration copy of a real website: it says plainly that it is unofficial,
 * and where the real site is. Never the official banner, so a copy is never taken for the real thing.
 *
 * @param string $copy_of The real site's address.
 */
function afrigovpress_demo_banner( $copy_of ) {
	$pack       = afrigovpress_pack();
	$government = $pack['government'] ?? __( 'government', 'afrigovpress' );
	$domain     = preg_replace( '~^www\.~', '', (string) wp_parse_url( $copy_of, PHP_URL_HOST ) );
	$name       = get_theme_mod( 'afrigovpress_copy_name', '' );
	$name       = $name ? $name : get_bloginfo( 'name' );
	$tld        = $pack['domain'] ?? '';
	?>
	<section class="ag-banner" aria-label="<?php esc_attr_e( 'Unofficial website notice', 'afrigovpress' ); ?>">
		<div class="ag-container ag-banner__inner">
			<?php echo afrigovpress_flag(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup. ?>
			<p class="ag-banner__text">
				<?php
				/* translators: %s: the government, such as Federal Republic of Nigeria */
				echo esc_html( sprintf( __( 'An unofficial rebuild of a %s website', 'afrigovpress' ), $government ) );
				?>
			</p>
			<details class="ag-banner__details">
				<summary><?php esc_html_e( 'How you know this is unofficial', 'afrigovpress' ); ?></summary>
				<p>
					<?php
					/* translators: %s: the organisation's full name */
					echo esc_html( sprintf( __( 'This is a demonstration built on afrigov. It is not run by %s.', 'afrigovpress' ), $name ) );
					?>
					<?php esc_html_e( 'The real website is', 'afrigovpress' ); ?> <a href="<?php echo esc_url( $copy_of ); ?>"><?php echo esc_html( $domain ); ?></a>.
				</p>
				<p>
					<?php
					if ( $tld ) {
						/* translators: %s: the government domain, such as .gov.ng */
						echo esc_html( sprintf( __( 'Official websites use %s. This one does not, and it carries no government seal.', 'afrigovpress' ), $tld ) );
					}
					?>
					<?php esc_html_e( 'Nothing you type here is sent to the organisation.', 'afrigovpress' ); ?>
				</p>
			</details>
		</div>
	</section>
	<?php
}

/**
 * Header navigation. A top-level item with items under it becomes an afrigov section menu: a
 * details element that opens with no script, the item's name as its button, the items as its
 * links. The section's own page belongs among those items, since the name opens the menu.
 * In the footer the walker draws one level of plain links.
 */
class Afrigovpress_Nav_Walker extends Walker_Nav_Menu {
	/**
	 * True while drawing an item that opens a section menu.
	 *
	 * @param stdClass|null $args  wp_nav_menu arguments.
	 * @param int           $depth Depth.
	 * @return bool
	 */
	private function is_section( $args, $depth ) {
		return 0 === $depth && $this->has_children && isset( $args->depth ) && 1 !== (int) $args->depth;
	}

	/**
	 * Opens a section menu's list.
	 *
	 * @param string   $output Markup so far.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   wp_nav_menu arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<ul class="ag-nav__menu">';
	}

	/**
	 * Closes a section menu's list and its details.
	 *
	 * @param string   $output Markup so far.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   wp_nav_menu arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul></details>';
	}

	/**
	 * Opens an item.
	 *
	 * @param string   $output Markup so far.
	 * @param WP_Post  $item   The menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   wp_nav_menu arguments.
	 * @param int      $id     Unused.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes  = (array) $item->classes;
		$here     = in_array( 'current-menu-item', $classes, true );
		$ancestor = in_array( 'current-menu-ancestor', $classes, true ) || in_array( 'current-menu-parent', $classes, true ) || in_array( 'current_page_parent', $classes, true );
		if ( $this->is_section( $args, $depth ) ) {
			$output .= '<li class="ag-nav__section"><details class="ag-nav__details" data-ag-menu><summary class="ag-nav__link ag-nav__summary"' . ( $here || $ancestor ? ' aria-current="true"' : '' ) . '>' . esc_html( $item->title ) . '</summary>';
			return;
		}
		$class   = $depth > 0 ? 'ag-nav__menu-link' : ( isset( $args->link_class ) ? $args->link_class : 'ag-nav__link' );
		$output .= '<li><a' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . ' href="' . esc_url( $item->url ) . '"';
		if ( $here || ( 0 === $depth && $ancestor ) ) {
			$output .= ' aria-current="' . ( $here ? 'page' : 'true' ) . '"';
		}
		if ( ! empty( $item->target ) ) {
			$output .= ' target="' . esc_attr( $item->target ) . '" rel="noopener"';
		}
		$output .= '>' . esc_html( $item->title ) . '</a>';
	}

	/**
	 * Closes an item.
	 *
	 * @param string   $output Markup so far.
	 * @param WP_Post  $item   The menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   wp_nav_menu arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}

/**
 * With no menu chosen yet, the header lists the top-level pages.
 */
function afrigovpress_menu_fallback() {
	$pages = get_pages(
		array(
			'parent'      => 0,
			'sort_column' => 'menu_order,post_title',
			'number'      => 6,
		)
	);
	echo '<ul class="ag-nav">';
	printf( '<li><a class="ag-nav__link" href="%s"%s>%s</a></li>', esc_url( home_url( '/' ) ), is_front_page() ? ' aria-current="page"' : '', esc_html__( 'Home', 'afrigovpress' ) );
	foreach ( $pages as $page ) {
		if ( (int) get_option( 'page_on_front' ) === $page->ID ) {
			continue;
		}
		printf( '<li><a class="ag-nav__link" href="%s"%s>%s</a></li>', esc_url( get_permalink( $page ) ), is_page( $page->ID ) ? ' aria-current="page"' : '', esc_html( get_the_title( $page ) ) );
	}
	echo '</ul>';
}

/**
 * A footer column from a menu: the menu's name is the heading.
 *
 * @param string $location Menu location.
 */
function afrigovpress_footer_menu( $location ) {
	if ( ! has_nav_menu( $location ) ) {
		return;
	}
	$locations = get_nav_menu_locations();
	$menu      = wp_get_nav_menu_object( $locations[ $location ] );
	echo '<div>';
	if ( $menu ) {
		echo '<h2 class="ag-footer__heading">' . esc_html( $menu->name ) . '</h2>';
	}
	wp_nav_menu(
		array(
			'theme_location' => $location,
			'container'      => false,
			'menu_class'     => 'ag-footer__list',
			'depth'          => 1,
			'walker'         => new Afrigovpress_Nav_Walker(),
			'link_class'     => '',
		)
	);
	echo '</div>';
}

/**
 * Where this page sits: a list of name and address pairs, home first. The last has no address.
 *
 * @return array
 */
function afrigovpress_trail() {
	if ( is_front_page() ) {
		return array();
	}
	$trail = array( array( __( 'Home', 'afrigovpress' ), home_url( '/' ) ) );
	$news  = (int) get_option( 'page_for_posts' );

	if ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( get_queried_object_id() ) ) as $ancestor ) {
			$trail[] = array( get_the_title( $ancestor ), get_permalink( $ancestor ) );
		}
		$trail[] = array( get_the_title(), '' );
	} elseif ( is_home() ) {
		$trail[] = array( $news ? get_the_title( $news ) : __( 'News', 'afrigovpress' ), '' );
	} elseif ( is_singular( 'post' ) ) {
		$trail[] = array( $news ? get_the_title( $news ) : __( 'News', 'afrigovpress' ), $news ? get_permalink( $news ) : home_url( '/' ) );
		$trail[] = array( get_the_title(), '' );
	} elseif ( is_singular( 'afrigov_event' ) ) {
		// An event from the afrigov blocks plugin sits under the events page.
		$events = get_page_by_path( 'events' );
		if ( $events ) {
			$trail[] = array( get_the_title( $events ), get_permalink( $events ) );
		}
		$trail[] = array( get_the_title(), '' );
	} elseif ( is_singular() ) {
		$trail[] = array( get_the_title(), '' );
	} elseif ( is_search() ) {
		$trail[] = array( __( 'Search', 'afrigovpress' ), '' );
	} elseif ( is_archive() ) {
		$trail[] = array( wp_strip_all_tags( get_the_archive_title() ), '' );
	} elseif ( is_404() ) {
		$trail[] = array( __( 'Page not found', 'afrigovpress' ), '' );
	}
	return $trail;
}

/**
 * The breadcrumb.
 */
function afrigovpress_breadcrumb() {
	$trail = afrigovpress_trail();
	if ( count( $trail ) < 2 ) {
		return;
	}
	echo '<nav aria-label="' . esc_attr__( 'Breadcrumb', 'afrigovpress' ) . '"><ol class="ag-breadcrumb">';
	$last = count( $trail ) - 1;
	foreach ( $trail as $i => $crumb ) {
		if ( $i === $last || '' === $crumb[1] ) {
			echo '<li><span aria-current="page">' . esc_html( $crumb[0] ) . '</span></li>';
		} else {
			echo '<li><a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a></li>';
		}
	}
	echo '</ol></nav>';
}

/**
 * Pagination for a list of posts.
 */
function afrigovpress_pagination() {
	global $wp_query;
	$total = (int) $wp_query->max_num_pages;
	if ( $total < 2 ) {
		return;
	}
	$current = max( 1, (int) get_query_var( 'paged' ) );
	echo '<nav aria-label="' . esc_attr__( 'Pagination', 'afrigovpress' ) . '"><ul class="ag-pagination">';
	if ( $current > 1 ) {
		echo '<li><a class="ag-pagination__link" rel="prev" href="' . esc_url( get_pagenum_link( $current - 1 ) ) . '">' . esc_html__( 'Previous', 'afrigovpress' ) . '</a></li>';
	}
	$gap = false;
	for ( $i = 1; $i <= $total; $i++ ) {
		if ( 1 === $i || $i === $total || abs( $i - $current ) <= 1 ) {
			echo '<li><a class="ag-pagination__link" href="' . esc_url( get_pagenum_link( $i ) ) . '"' . ( $i === $current ? ' aria-current="page"' : '' ) . '>';
			/* translators: %d: page number. */
			echo '<span class="ag-visually-hidden">' . esc_html__( 'Page', 'afrigovpress' ) . ' </span>' . (int) $i . '</a></li>';
			$gap = false;
		} elseif ( ! $gap ) {
			echo '<li><span class="ag-pagination__ellipsis" aria-hidden="true">…</span></li>';
			$gap = true;
		}
	}
	if ( $current < $total ) {
		echo '<li><a class="ag-pagination__link" rel="next" href="' . esc_url( get_pagenum_link( $current + 1 ) ) . '">' . esc_html__( 'Next', 'afrigovpress' ) . '</a></li>';
	}
	echo '</ul></nav>';
}

/**
 * Links to the organisation's accounts. The network's name is the text; the icon is decorative.
 */
function afrigovpress_social() {
	static $icons = null;
	if ( null === $icons ) {
		$file  = get_template_directory() . '/inc/social-icons.json';
		$icons = file_exists( $file ) ? json_decode( file_get_contents( $file ), true ) : array(); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- a local file shipped with the theme.
	}
	$items = '';
	foreach ( afrigovpress_networks() as $slug => $name ) {
		$url = get_theme_mod( 'afrigovpress_social_' . $slug, '' );
		if ( ! $url ) {
			continue;
		}
		$icon   = isset( $icons[ $slug ] ) ? '<svg class="ag-social__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24"><path d="' . esc_attr( $icons[ $slug ] ) . '"/></svg>' : '';
		$items .= '<li><a class="ag-social__link" href="' . esc_url( $url ) . '" rel="me">' . $icon . esc_html( $name ) . '</a></li>';
	}
	if ( $items ) {
		echo '<ul class="ag-social">' . $items . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts.
	}
}

/**
 * A post's date as afrigov writes dates: day, month in words, year, in a time element.
 *
 * @param int|null $post_id The post.
 * @return string
 */
function afrigovpress_date( $post_id = null ) {
	return '<time datetime="' . esc_attr( get_the_date( 'Y-m-d', $post_id ) ) . '">' . esc_html( get_the_date( 'j F Y', $post_id ) ) . '</time>';
}

/**
 * True when the page opens with a block that is the page's main heading: an afrigov hero, page title or event header. The theme then
 * leaves out its own title, so the page has one main heading, not two.
 *
 * @param int|WP_Post|null $post The page. Default the current one.
 * @return bool
 */
function afrigovpress_hero_is_title( $post = null ) {
	$post = get_post( $post );
	if ( ! $post || ! has_blocks( $post->post_content ) ) {
		return false;
	}
	foreach ( parse_blocks( $post->post_content ) as $block ) {
		if ( empty( $block['blockName'] ) ) {
			continue; // Whitespace between blocks.
		}
		if ( in_array( $block['blockName'], array( 'afrigov/page-title', 'afrigov/event-header' ), true ) ) {
			return true;
		}
		if ( 'afrigov/panel' === $block['blockName'] ) {
			return ! empty( $block['attrs']['isPageTitle'] );
		}
		return 'afrigov/hero' === $block['blockName'] && false !== ( $block['attrs']['isPageTitle'] ?? true );
	}
	return false;
}
