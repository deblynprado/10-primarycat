<?php
/**
 * 10up Primary Category Shortcode
 * Create a Shortcode that shows a list of posts from selected Primary Category
 * @since 1.0.0
 */

/**
 * Create a [10primary] that shows a list of posts from Primary Category
 * @param  array $atts shortocde attributes
 * @return object       HTML list that contains all posts from specific category
 */
function primary_cat_shortcode( $atts ) {
  $atts = shortcode_atts(
    array(
      'cat' => '',
    ),
    $atts,
    '10primary'
  );

  $query_args = array(
        'meta_key'   => 'pricat_get_categories',
        'meta_value' => $atts['cat']
      );
      $the_query = new WP_Query( $query_args );

      if ( $the_query->have_posts() ) {
        echo '<ul class="primary-category-list category-' . $atts['cat'] .'">';
        while ( $the_query->have_posts() ) {
          $the_query->the_post();
          printf( '<li><a href="%1$s">%2$s</a></li>', get_the_permalink(), get_the_title() );
        }
        echo '</ul>';
      } else {
      }
      wp_reset_postdata();

}
add_shortcode( '10primary', 'primary_cat_shortcode' );
