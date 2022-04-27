<?php
/**
 * Promotion slider
 * 
 * @package AXi_System
 */

namespace AXi_System;

class Promotion_Widget extends \WP_Widget
{
    /**
     * Image sizes
     *
     * @var array
     */
    protected $possible_image_sizes;

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct(
            'axi_promotion_slider', // Base ID
            esc_html__( '[AXi] Promotion Slider', 'axi-system' ), // Name
            [
                'description' => esc_html__( 'Show promotion slider using its featured image.', 'axi-system' ),
                'customize_selective_refresh' => true
            ] // Args
        );
        $this->possible_image_sizes = apply_filters(
            'image_size_names_choose',
            [
                'thumbnail' => esc_html__( 'Thumbnail', 'axi-system' ),
                'medium'    => esc_html__( 'Medium', 'axi-system' ),
                'large'     => esc_html__( 'Large', 'axi-system' ),
                'full'      => esc_html__( 'Full Size', 'axi-system' )
            ]
        );
    }

    /**
     * Outputs the HTML for this widget.
     *
     * @param array $args An array of standard parameters for widgets in this theme
     * @param array $instance An array of settings for this widget instance
     * @return void Echoes it's output
     **/
    function widget( $args, $instance )
    {
        $instance = wp_parse_args( (array) $instance, [
            'title'      => '',
            'post_ids'   => '',
            'image_size' => 'medium'
        ] );

        $instance['post_ids'] = explode( ',', $instance['post_ids'] );
        $post_ids = array_filter( $instance['post_ids'], function( $entry ) {
            $entry_v = absint( $entry );
            return $entry_v > 0;
        } );
        $instance['image_size'] = array_key_exists( $instance['image_size'], $this->possible_image_sizes ) ? $instance['image_size'] : 'medium';

        echo $args['before_widget'];

        if ( ! $instance['title'] )
        {
            $instance['title'] = esc_html__( 'Promotions', 'axi-system' );
        }
        $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
        

        echo $args['before_title'] . $title . $args['after_title'];

        if ( ! $post_ids )
        {
            if ( current_user_can( 'edit_theme_options' ) )
            {
                echo '<p>' . esc_html__( 'There are no promotions to display.', 'axi-system' ) . '</p>';
            }

            echo $args['after_widget'];
            return;
        }

        $options = [
            'slidesToShow'   => 1,
            'slidesToScroll' => 1,
            'adaptiveHeight' => true,
            'arrows'         => false,
            'dots'           => true,
            'autoplay'       => false,
            'infinite'       => true
        ];

        echo '<div class="axi-carousel-wrapper">';
        echo    '<div class="axi-carousel promotion-slider" data-axiel="carousel" data-options="' . esc_attr( json_encode( $options ) ) . '">';
        $default_image_url = AXISYS_URL . 'assets/images/default-promotion.jpg';

        foreach ( $post_ids as $post_id )
        {
            echo '<div class="entry">';
            if ( has_post_thumbnail( $post_id ) )
            {
                echo get_the_post_thumbnail( $post_id, $instance['image_size'] );
            }
            else
            {
                echo '<img src="' . esc_url( $default_image_url ) . '" alt="" />';
            }
            printf( '<a href="%1$s"><span class="screen-reader-text">%2$s</span></a>', esc_url( get_permalink( $post_id ) ), esc_html__( 'View', 'axi-system' ) );
            echo '</div>';

        } // end foreach

        echo    '</div>';
        echo '</div>';

        echo $args['after_widget'];
    }

    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array $new_instance An array of new settings as submitted by the admin
     * @param array $old_instance An array of the previous settings
     * @return array The validated and (if necessary) amended settings
     **/
    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['post_ids'] = $new_instance['post_ids'];
        $instance['image_size'] = isset( $new_instance['image_size'] ) && array_key_exists( $new_instance['image_size'], $this->possible_image_sizes ) ? $new_instance['image_size'] : 'medium';
        return $instance;
    }

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array $instance An array of the current settings for this widget
     * @return void Echoes it's output
     **/
    function form( $instance )
    {
        $title      = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $post_ids   = isset( $instance['post_ids'] ) ? explode( ',', $instance['post_ids'] ) : [];
        $image_size = isset( $instance['image_size'] ) && array_key_exists( $instance['image_size'], $this->possible_image_sizes ) ? $instance['image_size'] : 'medium';
        $control_uid = 'dynamic-post-list-' . axisys_guid();
        $post_ids = array_filter( $post_ids, function( $entry ) {
            $entry_v = absint( $entry );
            return $entry_v > 0;
        } );
        $posts = get_posts([
            'post_type'   => 'axi_promotion',
            'numberposts' => -1
        ]);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'axi-system' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <div class="dynamic-post-list" data-axiel="dynamic-post-list" data-id="<?php echo esc_attr( $control_uid ); ?>">
            <p><?php esc_html_e( 'Promotions', 'axi-system' ); ?></p>
            <ul class="post-list-display" data-axiel-role="display">
                <?php foreach ( $post_ids as $post_id ) : ?>
                <li data-axiel-value="<?php echo esc_attr( $post_id ); ?>">
                    <a href="javascript:void(0)" onclick="AXiAdminWidget.dynamicPostList.remove(this,'<?php echo esc_attr( $control_uid ); ?>')" class="remove">×</a>
                    <span class="text"><?php echo esc_html( get_the_title( $post_id ) ); ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
            <select class="widefat" data-axiel-role="select" onchange="AXiAdminWidget.dynamicPostList.add(this,'<?php echo esc_attr( $control_uid ); ?>')">
                <option value="0"><?php esc_html_e( '&ndash; Add promotion &ndash;', 'axi-system' ); ?></option>
                <?php foreach ( $posts as $post ) : ?>
                <option value="<?php echo esc_attr( $post->ID ); ?>"<?php echo ( in_array( $post->ID, $post_ids ) ? ' disabled' : '' ); ?>><?php echo esc_html( get_the_title( $post ) ); ?></option>
                <?php endforeach; ?>
            </select>
            <input id="<?php echo $this->get_field_id( 'post_ids' ); ?>" name="<?php echo $this->get_field_name( 'post_ids' ); ?>" type="hidden" data-axiel-role="value" value="<?php echo esc_attr( implode( ',', $post_ids ) ); ?>"/>
        </div>
        <p>
            <label for="<?php echo $this->get_field_id( 'image_size' ); ?>"><?php esc_html_e( 'Image Size:', 'axi-system' ); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'image_size' ); ?>" name="<?php echo $this->get_field_name( 'image_size' ); ?>">
                <?php foreach( $this->possible_image_sizes as $imgsize => $sizename ) : ?>
                <option value="<?php echo esc_attr( $imgsize ); ?>" <?php selected( $image_size, $imgsize ); ?>><?php echo esc_html( $sizename ); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }
}