<?php
/**
 * The bottom of every page: link columns, contact details, and the bar.
 *
 * @package afrigovpress
 */

$afrigovpress_address = trim( (string) get_theme_mod( 'afrigovpress_address', '' ) );
$afrigovpress_phone   = get_theme_mod( 'afrigovpress_phone', '' );
$afrigovpress_email   = get_theme_mod( 'afrigovpress_email', '' );
$afrigovpress_contact = $afrigovpress_address || $afrigovpress_phone || $afrigovpress_email;
?>
</main>

<footer class="ag-footer">
	<div class="ag-container">
		<?php if ( has_nav_menu( 'footer-1' ) || has_nav_menu( 'footer-2' ) || $afrigovpress_contact ) : ?>
		<div class="ag-footer__columns">
			<?php
			afrigovpress_footer_menu( 'footer-1' );
			afrigovpress_footer_menu( 'footer-2' );
			?>
			<?php if ( $afrigovpress_contact ) : ?>
			<div>
				<h2 class="ag-footer__heading"><?php esc_html_e( 'Contact', 'afrigovpress' ); ?></h2>
				<?php if ( $afrigovpress_address ) : ?>
					<address class="ag-footer__address"><?php echo nl2br( esc_html( $afrigovpress_address ) ); ?></address>
				<?php endif; ?>
				<ul class="ag-footer__list">
					<?php if ( $afrigovpress_phone ) : ?>
						<li><a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $afrigovpress_phone ) ); ?>"><?php echo esc_html( $afrigovpress_phone ); ?></a></li>
					<?php endif; ?>
					<?php if ( $afrigovpress_email ) : ?>
						<li><a href="<?php echo esc_url( 'mailto:' . $afrigovpress_email ); ?>"><?php echo esc_html( $afrigovpress_email ); ?></a></li>
					<?php endif; ?>
				</ul>
				<?php afrigovpress_social(); ?>
			</div>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<div class="ag-footer__bar">
			<?php echo afrigovpress_flag(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed markup. ?>
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
			<?php
			if ( has_nav_menu( 'legal' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'legal',
						'container'      => false,
						'menu_class'     => 'ag-footer__legal',
						'depth'          => 1,
						'walker'         => new Afrigovpress_Nav_Walker(),
						'link_class'     => '',
					)
				);
			}
			?>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
