<?php
add_action( 'widgets_init', 'register_pricat_widget' );
function register_pricat_widget() {
  register_widget( 'pricat_widget' );
}

// Creating the widget
class pricat_widget extends WP_Widget {

  function __construct() {
    parent::__construct(
      'pricat-widget',
      __( '10up Primary Category', '10pricat' ),
      array( 'description' => __( 'Show posts from a specific Primary Category', '10pricat' ), )
    );
  }

  public function widget( $args, $instance ) {
    $title = apply_filters( 'widget_title', $instance['title'] );

    echo $args['before_widget'];
    if ( ! empty( $title ) )
      echo $args['before_title'] . $title . $args['after_title'];

    $query_args = array(
      'meta_key'   => 'pricat_get_categories',
      'meta_value' => $instance["select"]
    );
    $the_query = new WP_Query( $query_args );

    if ( $the_query->have_posts() ) {
      echo '<ul class="primary-category-list category-' . $instance["select"] .'">';
      while ( $the_query->have_posts() ) {
        $the_query->the_post();
        printf( '<li><a href="%1$s">%2$s</a></li>', get_the_permalink(), get_the_title() );
      }
      echo '</ul>';
    } else {
    }
    wp_reset_postdata();
    echo $args['after_widget'];
  }

  public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) {
      $title = $instance[ 'title' ];
    }
    else {
      $title = "";
    }

    if ( isset( $instance['select'] ) ):
      $select = $instance['select'];
    else:
      $select = "";
      endif; ?>

      <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
      </p>
      <p>
        <select name="<?php echo $this->get_field_name( 'select' ); ?>" id="<?php echo $this->get_field_id( 'select' ); ?>">
          <?php $primary_posts = get_all_primary(); ?>
          <?php foreach ( $primary_posts as $key => $pp ) {
            $category = get_category_by_slug( $pp['primary'] );
            ?>
            <option value="<?php echo $pp['primary']; ?>" <?php selected( $select, $pp['primary'], true ); ?>>
              <?php echo $category->name; ?>

            </option>
            <?php
          }
          ?>
        </select>
      </p>
      <?php
    }

// Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
      $instance = array();
      $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
      $instance['select'] = ( ! empty( $new_instance['select'] ) ) ? strip_tags( $new_instance['select'] ) : '';
      return $instance;
    }
  }
