<?php
/**
 * A news article: date, headline, standfirst, lead image, body.
 *
 * @package afrigovpress
 */

get_header();
afrigovpress_breadcrumb();

while ( have_posts() ) :
	the_post();
	$afrigovpress_category = get_the_category();
	?>
	<article>
		<div class="ag-prose">
			<p class="ag-caption">
				<?php
				if ( $afrigovpress_category && 'uncategorized' !== $afrigovpress_category[0]->slug ) {
					echo esc_html( $afrigovpress_category[0]->name ) . ' · ';
				}
				echo afrigovpress_date(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the function.
				?>
			</p>
			<h1 class="ag-heading-xl"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="ag-lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>
		<?php
		if ( has_post_thumbnail() ) :
			$afrigovpress_caption = wp_get_attachment_caption( get_post_thumbnail_id() );
			?>
			<figure class="ag-figure ag-figure--16-9 agp-lead-image">
				<?php
				// With a caption the caption describes the image; without one the image's own description does.
				the_post_thumbnail(
					'afrigovpress-lead',
					array_merge(
						array(
							'class'   => 'ag-figure__image',
							'loading' => 'eager',
						),
						$afrigovpress_caption ? array( 'alt' => '' ) : array()
					)
				);
				?>
				<?php if ( $afrigovpress_caption ) : ?>
					<figcaption class="ag-figure__caption"><?php echo esc_html( $afrigovpress_caption ); ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php endif; ?>
		<div class="agp-content">
			<?php the_content(); ?>
		</div>
	</article>

	<nav aria-label="<?php esc_attr_e( 'More news', 'afrigovpress' ); ?>">
		<ul class="ag-pagination ag-pagination--simple">
			<?php
			$afrigovpress_older = get_previous_post();
			$afrigovpress_newer = get_next_post();
			if ( $afrigovpress_older ) {
				/* translators: %s: the title of an older article. */
				printf( '<li><a class="ag-pagination__link" rel="prev" href="%s">%s</a></li>', esc_url( get_permalink( $afrigovpress_older ) ), esc_html( sprintf( __( 'Older: %s', 'afrigovpress' ), get_the_title( $afrigovpress_older ) ) ) );
			}
			if ( $afrigovpress_newer ) {
				/* translators: %s: the title of a newer article. */
				printf( '<li><a class="ag-pagination__link" rel="next" href="%s">%s</a></li>', esc_url( get_permalink( $afrigovpress_newer ) ), esc_html( sprintf( __( 'Newer: %s', 'afrigovpress' ), get_the_title( $afrigovpress_newer ) ) ) );
			}
			?>
		</ul>
	</nav>
	<?php
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
endwhile;

get_footer();
