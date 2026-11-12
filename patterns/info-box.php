<?php
/**
 * Title: Info-Box mit Button (Download/Kontakt)
 * Slug: fvbb/info-box
 * Categories: fvbb
 * Keywords: download, datei, kontakt, ansprechpartner, box
 * Description: Titel und Untertitel links, Button rechts – wie bei Links & Downloads.
 */
?>
<!-- wp:group {"className":"is-style-fvbb-box","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group is-style-fvbb-box"><!-- wp:group {"style":{"spacing":{"blockGap":"0.2rem"}},"layout":{"type":"flex","orientation":"vertical","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Titel</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Stand: Monat Jahr</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fvbb-download"} -->
<div class="wp-block-button is-style-fvbb-download"><a class="wp-block-button__link wp-element-button" href="#">Herunterladen</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
