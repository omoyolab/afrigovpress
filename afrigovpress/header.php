<?php
/**
 * The top of every page: skip link, official banner, header and navigation.
 *
 * @package afrigovpress
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="ag-skip-link" href="#main"><?php esc_html_e( 'Skip to main content', 'afrigovpress' ); ?></a>

<?php afrigovpress_banner(); ?>

<header class="ag-header">
	<div class="ag-container ag-header__inner">
		<a class="ag-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php
			$afrigovpress_logo = get_theme_mod( 'custom_logo' );
			if ( $afrigovpress_logo ) {
				// The name is written beside it, so the crest is decorative.
				echo wp_get_attachment_image(
					$afrigovpress_logo,
					'thumbnail',
					false,
					array(
						'class'   => 'ag-header__logo ag-header__logo--lg',
						'alt'     => '',
						'loading' => 'eager',
					)
				);
			} else {
				echo afrigovpress_flag( true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup.
			}
			$afrigovpress_sub = get_theme_mod( 'afrigovpress_parent', '' );
			if ( ! $afrigovpress_sub ) {
				$afrigovpress_sub = get_bloginfo( 'description' );
			}
			?>
			<span>
				<span class="ag-header__org"><?php bloginfo( 'name' ); ?></span>
				<?php if ( $afrigovpress_sub ) : ?>
					<span class="ag-header__sub"><?php echo esc_html( $afrigovpress_sub ); ?></span>
				<?php endif; ?>
			</span>
		</a>
		<?php if ( get_theme_mod( 'afrigovpress_header_search', true ) ) : ?>
			<details class="ag-header__search" data-ag-menu>
				<summary class="ag-header__search-toggle"><span class="ag-search__icon" aria-hidden="true"></span><span class="ag-visually-hidden"><?php esc_html_e( 'Search', 'afrigovpress' ); ?></span></summary>
				<div class="ag-header__search-panel">
					<div class="ag-container">
						<form class="ag-search" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
							<label class="ag-search__label" for="header-q"><?php esc_html_e( 'Search this site', 'afrigovpress' ); ?></label>
							<div class="ag-search__row">
								<input class="ag-search__input" type="search" id="header-q" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" />
								<button class="ag-search__button" type="submit"><span class="ag-search__icon" aria-hidden="true"></span><?php esc_html_e( 'Search', 'afrigovpress' ); ?></button>
							</div>
						</form>
					</div>
				</div>
			</details>
		<?php endif; ?>
		<button class="ag-header__toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-ag-toggle><?php esc_html_e( 'Menu', 'afrigovpress' ); ?></button>
		<nav class="ag-header__nav" id="site-nav" aria-label="<?php esc_attr_e( 'Main', 'afrigovpress' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'ag-nav',
					'depth'          => 2,
					'walker'         => new Afrigovpress_Nav_Walker(),
					'fallback_cb'    => 'afrigovpress_menu_fallback',
				)
			);
			?>
		</nav>
	</div>
</header>

<main class="ag-main ag-container" id="main" tabindex="-1">
