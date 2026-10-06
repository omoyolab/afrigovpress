<?php
/**
 * The news page, archives, and anything without a template of its own.
 *
 * @package afrigovpress
 */

get_header();
afrigovpress_breadcrumb();

$afrigovpress_lead = '';
if ( is_home() ) {
	$afrigovpress_news  = (int) get_option( 'page_for_posts' );
	$afrigovpress_title = $afrigovpress_news ? get_the_title( $afrigovpress_news ) : __( 'News', 'afrigovpress' );
	// The news page's excerpt is the sentence under its title.
	$afrigovpress_lead = $afrigovpress_news && has_excerpt( $afrigovpress_news ) ? get_the_excerpt( $afrigovpress_news ) : '';
} elseif ( is_archive() ) {
	$afrigovpress_title = wp_strip_all_tags( get_the_archive_title() );
} else {
	$afrigovpress_title = get_bloginfo( 'name' );
}
?>
<div class="ag-prose">
	<h1 class="ag-heading-xl"><?php echo esc_html( $afrigovpress_title ); ?></h1>
	<?php if ( $afrigovpress_lead ) : ?>
		<p class="ag-lead"><?php echo esc_html( $afrigovpress_lead ); ?></p>
	<?php endif; ?>
	<?php if ( is_archive() && get_the_archive_description() ) : ?>
		<div class="ag-lead"><?php echo wp_kses_post( get_the_archive_description() ); ?></div>
	<?php endif; ?>
</div>
<?php
get_template_part( 'template-parts/list' );
get_footer();
