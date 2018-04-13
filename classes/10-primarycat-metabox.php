<?php
/**
 * 10up Primary Category Metabox
 * Create and shows a metabox on top right of edit.php page for all Post Types.
 * @since 1.0.0
 */

add_action( 'add_meta_boxes', 'pricat_metaboxes' );
function pricat_metaboxes() {
  add_meta_box(
    'pricat-metabox',
    __( 'Primary Category', '10pricat' ),
    'pricat_get_categories',
    null,
    'side',
    'high',
    null
  );
}
