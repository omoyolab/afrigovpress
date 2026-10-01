<?php
/**
 * Search results.
 *
 * @package afrigovpress
 */

get_header();
afrigovpress_breadcrumb();
?>
<div class="ag-prose">
	<h1 class="ag-heading-xl">
		<?php
		/* translators: %s: what was searched for. */
		printf( esc_html__( 'Search results for "%s"', 'afrigovpress' ), esc_html( get_search_query() ) );
		?>
	</h1>
	<?php get_search_form(); ?>
</div>
<?php
get_template_part( 'template-parts/list' );
get_footer();
