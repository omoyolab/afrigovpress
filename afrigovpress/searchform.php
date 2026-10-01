<?php
/**
 * The search form: a label, an input and a button.
 *
 * @package afrigovpress
 */

$afrigovpress_id = wp_unique_id( 'search-' );
?>
<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="ag-field">
		<label class="ag-label" for="<?php echo esc_attr( $afrigovpress_id ); ?>"><?php esc_html_e( 'Search this site', 'afrigovpress' ); ?></label>
		<div class="ag-input-group">
			<input class="ag-input" id="<?php echo esc_attr( $afrigovpress_id ); ?>" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" />
			<button class="ag-button" type="submit"><?php esc_html_e( 'Search', 'afrigovpress' ); ?></button>
		</div>
	</div>
</form>
