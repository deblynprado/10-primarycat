<?php
/**
* Plugin Name: 10up Primary Category
* Description: This Plugin allows you set a Primary Category for any Post Type
* Version: 1.0
* Author: Deblyn Prado
* Text Domain: 10pricat
* Domain Path: /languages
* Author URI: http://deblynprado.com
* License: GPL2 or later
*
* 10up Primary Category is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 2 of the License, or
* any later version.
*
* 10up Primary Category is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with 10up Primary Category. If not, see https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html.
*/

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

function __construct() {
}

function pricat_get_categories( $post ) {
  $meta_element_class = get_post_meta( $post->ID, 'pricat_get_categories', true );
  $param = array(
    'hide_empty' => false
  );
  $categories = get_categories( $param );
  wp_nonce_field( 'pricat_get_categories', 'event-dropdown' );
  ?>

  <select name="pricat-dropdown" id="pricat-dropdown">
    <?php if ( !( $meta_element_class ) ): ?>
      <option value=""><?php echo esc_attr_e( 'Primary Category', '10pricat' ); ?></option>
    <?php endif;

    foreach ( $categories as $category ) { ?>
    <option value="<?php _e( $category->category_nicename ); ?>" <?php selected( $category->category_nicename, $meta_element_class, true ) ?>>
      <?php _e( $category->cat_name ); ?>

    </option>
    <?php
  }
  ?>
</select>
<?php }

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

add_action('save_post', 'so_save_metabox');
function so_save_metabox(){
  global $post;
  if( !isset( $_POST["pricat-dropdown"] ) || ! wp_verify_nonce( $_POST['event-dropdown'], 'pricat_get_categories' ) ) {
    return;
  } else {
    $meta_element_class = $_POST['pricat-dropdown'];
    update_post_meta( $post->ID, 'pricat_get_categories', $meta_element_class );
  }
}
