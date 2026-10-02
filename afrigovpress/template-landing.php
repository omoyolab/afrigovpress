<?php
/**
 * Template Name: Landing page, built from patterns
 * Template Post Type: page
 *
 * A page with no automatic title or breadcrumb. Start it with a Hero pattern, which carries the page's heading.
 *
 * @package afrigovpress
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<div class="agp-content agp-content--landing">
		<?php the_content(); ?>
	</div>
	<?php
endwhile;

get_footer();
