<?php
/**
 * Title: Hero with image
 * Slug: afrigovpress/hero-image
 * Categories: afrigovpress
 * Keywords: hero, image, photo
 * Description: A title, a lead and one action, with a photograph beside the text. Never behind it.
 */
?>
<!-- wp:group {"className":"ag-hero ag-hero--image","layout":{"type":"default"}} -->
<div class="wp-block-group ag-hero ag-hero--image"><!-- wp:group {"className":"ag-hero__inner","layout":{"type":"default"}} -->
<div class="wp-block-group ag-hero__inner"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1,"className":"ag-heading-xl ag-hero__title"} -->
<h1 class="wp-block-heading ag-heading-xl ag-hero__title">Clean water for every community</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ag-lead ag-hero__lead"} -->
<p class="ag-lead ag-hero__lead">The agency that builds, inspects and regulates public water supply.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"ag-hero__actions"} -->
<div class="wp-block-buttons ag-hero__actions"><!-- wp:button {"className":"is-style-agp-start"} -->
<div class="wp-block-button is-style-agp-start"><a class="wp-block-button__link wp-element-button" href="#">Our programmes</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"ag-hero__media"} -->
<figure class="wp-block-image size-full ag-hero__media"><img src="<?php echo afrigovpress_placeholder( '4x3' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
