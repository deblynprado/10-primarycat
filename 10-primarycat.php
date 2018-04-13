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

/**
 * Get all itens on category taxonomy and get current post Primary Category
 * @param  array $post Global WordPress $post object
 * @return  null
 */
function pricat_get_categories( $post ) {
  $meta_element_class = get_post_meta( $post->ID, 'pricat_get_categories', true );
  $param = array(
    'hide_empty' => false
  );
  $categories = get_categories( $param );
  pricat_select( $categories, $meta_element_class );
  return;
}

/**
 * Getting all posts that has a Primary Category setup
 * @return array Array that contains PostID and Primary Category
 */
function get_all_primary() {
  $args = array(
    'numberposts' => -1
  );
  $latest_posts = get_posts( $args );
  $posts_pricat = array();
  $i = 0;

  foreach ( $latest_posts as $post ) {
    if ( get_post_meta( $post->ID, 'pricat_get_categories', true ) ) :
      $posts_pricat[$i]['id'] = $post->ID;
      $posts_pricat[$i]['primary'] = get_post_meta( $post->ID, 'pricat_get_categories', true );
      $i++;
    endif;
  }
  return $posts_pricat;
}

/**
 * Show a select that contains all category taxonomy itens
 * @param  array $categories Object from pricat_get_categories function
 * @param  string $meta The current Primary Category setup for the post
 * @return null
 */
function pricat_select( $categories, $meta ) {
  wp_nonce_field( 'pricat_get_categories', 'event-dropdown' );
  ?>
  <select name="pricat-dropdown" id="pricat-dropdown">
    <?php if ( !( $meta ) ): ?>
      <option value=""><?php echo esc_attr_e( 'Primary Category', '10pricat' ); ?></option>
    <?php endif;

    foreach ( $categories as $category ) { ?>
    <option value="<?php _e( $category->category_nicename ); ?>" <?php selected( $category->category_nicename, $meta, true ) ?>>
      <?php _e( $category->cat_name ); ?>

    </option>
    <?php
  }
  ?>
</select>
<?php
return;
}

add_action( 'save_post', 'pricat_save' );
/**
 * Save the Primary Category
 * @return null
 */
function pricat_save(){
  global $post;
  if( !isset( $_POST["pricat-dropdown"] ) || ! wp_verify_nonce( $_POST['event-dropdown'], 'pricat_get_categories' ) ) {
    return;
  } else {
    $meta_element_class = $_POST['pricat-dropdown'];
    update_post_meta( $post->ID, 'pricat_get_categories', $meta_element_class );
  }
  return;
}


// Includes our metabox on edit.php page
include( PLUGIN_PATH . 'classes/10-primarycat-metabox.php' );

// Includes custom Widget
include( PLUGIN_PATH . 'classes/10-primarycat-widget.php' );

// Includes [10primary] shortcode
include( PLUGIN_PATH . 'classes/10-primarycat-shortcode.php' );
