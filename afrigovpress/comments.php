<?php
/**
 * Comments, for sites that use them.
 *
 * @package afrigovpress
 */

if ( post_password_required() ) {
	return;
}
?>
<section class="ag-prose" aria-labelledby="comments-title">
	<?php if ( have_comments() ) : ?>
		<h2 id="comments-title"><?php esc_html_e( 'Comments', 'afrigovpress' ); ?></h2>
		<ol class="agp-comments">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 0,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php else : ?>
		<h2 id="comments-title" class="ag-visually-hidden"><?php esc_html_e( 'Comments', 'afrigovpress' ); ?></h2>
	<?php endif; ?>
	<?php
	comment_form(
		array(
			'class_submit' => 'ag-button',
			'title_reply'  => __( 'Leave a comment', 'afrigovpress' ),
		)
	);
	?>
</section>
