<?php
/**
 * Promotion slider
 * 
 * @package AXi_System
 */

namespace AXi_System;

class Image_Slider_Widget extends \WP_Widget
{
    /**
     * Constructor
     *
     * @return void
     **/
    function __construct()
    {
        parent::__construct(
            'axisys_img_slider', // Base ID
            esc_html__( '[AXi] Image Slider', 'axi-system' ), // Name
            array(
                'description' => esc_html__( 'Add multiple images with some options and optional links.', 'axi-system' ),
                'customize_selective_refresh' => true
            ) // Args
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
        extract( $args, EXTR_SKIP );
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title' => '',
                'images' => '',
                'columns' => '4',
                'links' => '',
                'target' => '',
                'size' => 'medium'
            )
        );

        $instance['target'] = (bool) $instance['target'];

        if ( ! empty( $instance['title'] ) )
        {
            $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
        }
        else {
            $title = '';
        }

        echo $before_widget;

        if ( ! empty( $title ) )
        {
            echo $before_title . $title . $after_title;
        }

        $images = $links = array();

        if ( ! empty( $instance['images'] ) )
        {
            $images = explode( ",", $instance['images'] );
        }

        if ( ! empty( $instance['links'] ) )
        {
            $links = explode( "|", $instance['links'] );
        }

        if ( ! empty( $images ) )
        {
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
            echo    '<div class="axi-carousel axi-wpwidget-img-slider" data-axiel="carousel" data-options="' . esc_attr( json_encode( $options ) ) . '">';

            foreach ( $images as $image_key => $image_id )
            {
                echo '<div class="entry">';

                // Open <a>
                if ( ! empty( $links[ $image_key ] ) )
                {
                    printf(
                        '<a href="%1$s" target="%2$s">',
                        esc_url( $links[ $image_key ] ),
                        $instance['target'] ? '_blank' : '_self'
                    );
                }

                echo wp_get_attachment_image( $image_id, $instance['size'] );

                // Close </a>
                if ( ! empty( $links[ $image_key ] ) )
                {
                    echo '</a>';
                }

                echo '</div>';

            } // end foreach

            echo    '</div>';
            echo '</div>';
        }

        echo $after_widget;
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
        $instance['images'] = sanitize_text_field( $new_instance['images'] );

        $links = array();
        $links_arr = explode( "\n", $new_instance['links'] );

        foreach ( $links_arr as $link )
        {
            $link = trim( $link );
            if ( empty( $link ) )
            {
                $links[] = '';
                continue;
            }
            $links[] = esc_url( $link );
        }

        $instance['links'] = implode( "|", $links );
        $instance['size'] = sanitize_text_field( $new_instance['size'] );
        $instance['target'] = isset( $new_instance['target'] ) ? (bool) $new_instance['target'] : false;

        return $instance;
    }

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  An array of the current settings for this widget
     * @return void Echoes it's output
     **/
    function form( $instance )
    {
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'title' => '',
                'images' => '',
                'links' => '',
                'target' => true,
                'size' => 'medium'
            )
        );

        $this_id    = $this->get_field_id( '' );
        $title      = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $image_ids  = explode( ",", $instance['images'] );
        $links      = explode( "|", $instance['links'] );
        $target     = isset( $instance['target'] ) ? (bool) $instance['target'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'axi-system' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <div class="axisys-wpwidget-imgslider" id="<?php echo esc_attr( $this_id ); ?>">
            <label><?php esc_html_e( 'Images', 'axi-system' ); ?></label>
            <ul class="images"><?php
            foreach ( $image_ids as $image ) {
                $attachment_image = wp_get_attachment_image_src( $image, 'thumbnail' );
                if ( ! empty( $attachment_image ) ) {
                    echo '<li data-id="' . esc_attr( $image ) . '"'. 
                        ' style="background-image:url(' . esc_url( $attachment_image[0] ) . ');">';
                    echo '<a class="image-edit" href="#" onclick="AXiAdminWidget.imgSliderWidget.edit_image(event,\'' . esc_attr( $this_id ) . '\',' . esc_attr( $image ) . ')">' .
                            '<i class="dashicons dashicons-edit"></i>' .
                        '</a>';
                    echo '<a class="image-delete" href="#" onclick="AXiAdminWidget.imgSliderWidget.remove_image(event,\'' . esc_attr( $this_id ) . '\',' . esc_attr( $image ) . ');return false;">' .
                            '<i class="dashicons dashicons-trash"></i>' .
                        '</a>';
                    echo '</li>';
                }
            }
            echo '<li data-id="0">';
            echo '<a class="image-add" href="#" onclick="AXiAdminWidget.imgSliderWidget.add_images(event,\'' . esc_attr( $this_id ) . '\');">' .
                    '<i class="dashicons dashicons-plus-alt"></i>' .
                '</a>';
            echo '</li>';
            ?></ul>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Image Size:', 'axi-system' ); ?></label>
                <select class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>">
                <?php
                    $image_sizes = apply_filters( 'image_size_names_choose', array(
                        'thumbnail' => esc_html__( 'Thumbnail', 'axi-system' ),
                        'medium'    => esc_html__( 'Medium', 'axi-system' ),
                        'large'     => esc_html__( 'Large', 'axi-system' ),
                        'full'      => esc_html__( 'Full Size', 'axi-system' )
                    ) );
                    foreach ( $image_sizes as $size => $text )
                    {
                        printf(
                            '<option value="%1$s" %2$s">%3$s</option>',
                            esc_attr( $size ),
                            selected( $size, $instance['size'], false ),
                            esc_html( $text )
                        );
                    }
                ?>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'links' ) ); ?>"><?php echo esc_html__( 'Links', 'axi-system' ); ?></label>
                <textarea class="image-links widefat" id="<?php echo esc_attr( $this->get_field_id( 'links' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'links' ) ); ?>" rows="10"><?php
                    echo implode( "\n", $links );
                ?></textarea>
            </p>
            <p class="howto"><?php echo esc_html__( 'Add links for images, seperate by newline.', 'axi-system' ); ?></p>
            <input type="hidden" name="<?php echo $this->get_field_name( 'images' ); ?>" id="<?php echo $this->get_field_id( 'images' ); ?>" value="<?php echo esc_attr( $instance['images'] ); ?>"/>
        </div>
        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" type="checkbox" <?php checked( $target );  ?>/><label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>"><?php esc_html_e( 'Open links in new tab?', 'axi-system' ); ?></label>
        </p>
        <?php
    }
}