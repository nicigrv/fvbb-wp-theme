<?php
/**
 * Title: Abschnitt: Text + Bild
 * Slug: fvbb/text-bild
 * Categories: fvbb
 * Keywords: abschnitt, bild, text, startseite
 * Description: Abschnitt in voller Breite mit Überschrift (Welle), Text, Button und Foto.
 */
?>
<!-- wp:group {"tagName":"section","align":"full","backgroundColor":"water","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull has-water-background-color has-background"><!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:heading {"className":"is-style-fvbb-wave"} -->
<h2 class="wp-block-heading is-style-fvbb-wave">Überschrift</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Ein kurzer Text zum Thema.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#">Mehr erfahren</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"className":"is-style-fvbb-photo"} -->
<figure class="wp-block-image is-style-fvbb-photo"><img alt=""/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
