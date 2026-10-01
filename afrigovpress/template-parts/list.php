<?php
/**
 * A dated list of posts with thumbnails: news, an archive, search results.
 *
 * @package afrigovpress
 */

if ( ! have_posts() ) : ?>
	<div class="ag-empty">
		<h2 class="ag-empty__title"><?php esc_html_e( 'Nothing here yet', 'afrigovpress' ); ?></h2>
		<p><?php esc_html_e( 'When something is published, it appears on this page.', 'afrigovpress' ); ?></p>
	</div>
	<?php
	return;
endif;
?>
<ul class="ag-list">
	<?php
	while ( have_posts() ) :
		the_post();
		$afrigovpress_thumb = has_post_thumbnail();
		?>
		<li class="ag-list__item<?php echo $afrigovpress_thumb ? ' ag-list__item--media' : ''; ?>">
			<?php if ( $afrigovpress_thumb ) : ?>
				<div class="ag-list__media">
					<?php
					// The headline is the link, so the thumbnail is decorative.
					the_post_thumbnail(
						'afrigovpress-thumb',
						array(
							'alt'     => '',
							'loading' => 'lazy',
						)
					);
					?>
				</div>
				<div class="ag-list__body">
			<?php endif; ?>
			<a class="ag-list__link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			<span class="ag-list__meta"><?php echo afrigovpress_date(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the function. ?></span>
			<?php if ( has_excerpt() ) : ?>
				<p class="ag-list__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
			<?php if ( $afrigovpress_thumb ) : ?>
				</div>
			<?php endif; ?>
		</li>
	<?php endwhile; ?>
</ul>
<?php
afrigovpress_pagination();
