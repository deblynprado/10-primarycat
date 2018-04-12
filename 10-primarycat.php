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

function pricat_get_categories() { ?>
<select name="event-dropdown">
  <option value=""><?php echo esc_attr_e( 'Select Event', '10pricat' ); ?></option>
  <?php
  $param = array(
    'hide_empty' => false
  ) ;
  $categories = get_categories( $param );

  foreach ( $categories as $category ) {
    printf( '<option value="%1$s">%2$s</option>',
      esc_attr( '/category/archives/' . $category->category_nicename ),
      esc_html( $category->cat_name )
    );
  }
  ?>
</select>
<?php }

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


add_action( 'add_meta_boxes', 'pricat_metaboxes' );
