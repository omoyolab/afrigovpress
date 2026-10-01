<?php
/**
 * Page not found.
 *
 * @package afrigovpress
 */

get_header();
?>
<div class="ag-prose">
	<h1 class="ag-heading-xl"><?php esc_html_e( 'Page not found', 'afrigovpress' ); ?></h1>
	<p><?php esc_html_e( 'If you typed the web address, check it is correct. If you pasted it, check you copied all of it.', 'afrigovpress' ); ?></p>
	<p><?php esc_html_e( 'You can search for what you need, or start again from the home page.', 'afrigovpress' ); ?></p>
	<?php get_search_form(); ?>
	<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to the home page', 'afrigovpress' ); ?></a></p>
</div>
<?php
get_footer();
