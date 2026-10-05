<?php
/**
 * A page.
 *
 * @package afrigovpress
 */

get_header();
afrigovpress_breadcrumb();

while ( have_posts() ) :
	the_post();
	?>
	<?php if ( ! afrigovpress_hero_is_title() ) : ?>
		<div class="ag-prose">
			<h1 class="ag-heading-xl"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="ag-lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="agp-content">
		<?php
		the_content();
		wp_link_pages(
			array(
				'before' => '<p>' . esc_html__( 'Pages:', 'afrigovpress' ),
				'after'  => '</p>',
			)
		);
		?>
	</div>
	<?php
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
endwhile;

get_footer();
